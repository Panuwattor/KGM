<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\QuoteRequest;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /** สถานะที่ไม่นับเป็นยอดขาย */
    private const EXCLUDED = ['cancelled', 'refunded'];

    public function index(Request $request)
    {
        // ----- ช่วงเวลา -----
        $period = $request->input('period', '30');
        if ($request->filled('from') && $request->filled('to')) {
            $start = Carbon::parse($request->from)->startOfDay();
            $end   = Carbon::parse($request->to)->endOfDay();
            $period = 'custom';
        } else {
            $end   = now();
            $start = now()->subDays((int) $period);
        }
        $lengthDays = max(1, $start->diffInDays($end));
        $prevEnd    = (clone $start);
        $prevStart  = (clone $start)->subDays($lengthDays);

        $range     = [$start, $end];
        $prevRange = [$prevStart, $prevEnd];
        $from = $start->toDateString();
        $to   = $end->toDateString();

        // ===== 1) KPI สรุป + เทียบช่วงก่อนหน้า =====
        $totalRevenue = (float) Order::whereNotIn('status', self::EXCLUDED)->whereBetween('created_at', $range)->sum('total');
        $paidOrders   = Order::whereNotIn('status', self::EXCLUDED)->whereBetween('created_at', $range)->count();
        $totalOrders  = Order::whereBetween('created_at', $range)->count();
        $newCustomers = Customer::whereBetween('created_at', $range)->count();
        $avgOrderValue = $paidOrders > 0 ? $totalRevenue / $paidOrders : 0;
        $totalDiscount = (float) Order::whereNotIn('status', self::EXCLUDED)->whereBetween('created_at', $range)->sum('discount_amount');

        $prevRevenue = (float) Order::whereNotIn('status', self::EXCLUDED)->whereBetween('created_at', $prevRange)->sum('total');
        $prevOrders  = Order::whereBetween('created_at', $prevRange)->count();
        $revenueChange = $prevRevenue > 0 ? (($totalRevenue - $prevRevenue) / $prevRevenue) * 100 : null;
        $ordersChange  = $prevOrders > 0 ? (($totalOrders - $prevOrders) / $prevOrders) * 100 : null;

        // ===== 2) ยอดขายรายวัน =====
        $dailyRevenue = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total'),
            DB::raw('COUNT(*) as orders')
        )
            ->whereNotIn('status', self::EXCLUDED)
            ->whereBetween('created_at', $range)
            ->groupBy('date')->orderBy('date')->get();

        // ===== 3) สินค้าขายดี =====
        $topStats = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotIn('orders.status', self::EXCLUDED)
            ->whereBetween('orders.created_at', $range)
            ->select('order_items.product_id', DB::raw('SUM(order_items.quantity) as total_qty'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
            ->groupBy('order_items.product_id')->orderByDesc('total_qty')->take(10)->get()->keyBy('product_id');

        $topProducts = Product::withTrashed()->whereIn('id', $topStats->keys())->get()
            ->map(function ($p) use ($topStats) {
                $s = $topStats->get($p->id);
                $p->total_qty = $s->total_qty;
                $p->total_revenue = $s->total_revenue;
                return $p;
            })->sortByDesc('total_qty')->values();

        // ===== 4) ยอดขายตามหมวดหมู่ / ประเภท / ไซส์ =====
        $salesByCategory = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->whereNotIn('orders.status', self::EXCLUDED)->whereBetween('orders.created_at', $range)
            ->select(DB::raw('COALESCE(categories.name, "ไม่ระบุหมวดหมู่") as name'),
                DB::raw('SUM(order_items.quantity) as qty'), DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('categories.id', 'name')->orderByDesc('revenue')->get();

        $salesByType = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('product_types', 'products.product_type_id', '=', 'product_types.id')
            ->whereNotIn('orders.status', self::EXCLUDED)->whereBetween('orders.created_at', $range)
            ->select(DB::raw('COALESCE(product_types.name, "ไม่ระบุประเภท") as name'),
                DB::raw('SUM(order_items.quantity) as qty'), DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('product_types.id', 'name')->orderByDesc('revenue')->get();

        $salesBySize = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotIn('orders.status', self::EXCLUDED)->whereBetween('orders.created_at', $range)
            ->whereNotNull('order_items.variant_label')->where('order_items.variant_label', '!=', '')
            ->select('order_items.variant_label as size',
                DB::raw('SUM(order_items.quantity) as qty'), DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('order_items.variant_label')->orderByDesc('qty')->get();

        // ===== 5) สต๊อก =====
        $inventoryValue  = (float) Product::where('manage_stock', true)->sum(DB::raw('stock_quantity * price'));
        $outOfStockCount = Product::where('manage_stock', true)->where('stock_quantity', '<=', 0)->count();
        $lowStockCount   = Product::lowStock()->count();

        $lowStockVariants = ProductVariant::where('is_active', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->with('product:id,name')->orderBy('stock_quantity')->take(25)->get()
            ->map(fn ($v) => (object) [
                'name'      => $v->product->name ?? '-',
                'size'      => $v->size,
                'stock'     => $v->stock_quantity,
                'threshold' => $v->low_stock_threshold,
            ]);

        $lowStockSimple = Product::where('manage_stock', true)->doesntHave('variants')
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->orderBy('stock_quantity')->take(25)->get()
            ->map(fn ($p) => (object) [
                'name'      => $p->name,
                'size'      => null,
                'stock'     => $p->stock_quantity,
                'threshold' => $p->low_stock_threshold,
            ]);

        $lowStockList = $lowStockSimple->concat($lowStockVariants)->sortBy('stock')->take(25)->values();

        $soldIds = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotIn('orders.status', self::EXCLUDED)->whereBetween('orders.created_at', $range)
            ->distinct()->pluck('order_items.product_id');

        $slowMovingQuery = Product::active()->where('manage_stock', true)->where('stock_quantity', '>', 0)->whereNotIn('id', $soldIds);
        $slowMovingCount = (clone $slowMovingQuery)->count();
        $slowMoving = $slowMovingQuery->orderByDesc('stock_quantity')->take(15)->get(['id', 'name', 'stock_quantity']);

        // ===== 6) ลูกค้า =====
        $topCustomers = Order::whereNotIn('status', self::EXCLUDED)->whereBetween('created_at', $range)
            ->whereNotNull('customer_id')
            ->select('customer_id', DB::raw('SUM(total) as spent'), DB::raw('COUNT(*) as orders'))
            ->groupBy('customer_id')->orderByDesc('spent')->take(10)->get();
        $customerNames = Customer::whereIn('id', $topCustomers->pluck('customer_id'))->pluck('name', 'id');

        $buyerIds     = Order::whereBetween('created_at', $range)->whereNotNull('customer_id')->distinct()->pluck('customer_id');
        $returningIds = Order::whereIn('customer_id', $buyerIds)->where('created_at', '<', $start)->distinct()->pluck('customer_id');
        $returningCount = $returningIds->count();
        $newBuyerCount  = max(0, $buyerIds->count() - $returningCount);

        $topProvinces = Order::whereNotIn('status', self::EXCLUDED)->whereBetween('created_at', $range)
            ->whereNotNull('ship_province')->where('ship_province', '!=', '')
            ->select('ship_province', DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as orders'))
            ->groupBy('ship_province')->orderByDesc('revenue')->take(10)->get();

        // ===== 7) ออเดอร์ตามสถานะ =====
        $ordersByStatus = Order::whereBetween('created_at', $range)
            ->select('status', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->groupBy('status')->get()->keyBy('status');
        $cancelledCount = ($ordersByStatus['cancelled']->count ?? 0) + ($ordersByStatus['refunded']->count ?? 0);
        $cancellationRate = $totalOrders > 0 ? ($cancelledCount / $totalOrders) * 100 : 0;

        // ===== 8) คูปอง =====
        $couponPerformance = Order::whereNotIn('status', self::EXCLUDED)->whereBetween('created_at', $range)
            ->whereNotNull('coupon_id')
            ->select('coupon_id', DB::raw('COUNT(*) as uses'), DB::raw('SUM(discount_amount) as discount'), DB::raw('SUM(total) as revenue'))
            ->groupBy('coupon_id')->orderByDesc('uses')->take(15)->get();
        $couponInfo = Coupon::whereIn('id', $couponPerformance->pluck('coupon_id'))->get(['id', 'code', 'name'])->keyBy('id');

        // ===== 9) B2B: ใบเสนอราคา =====
        $quoteCounts   = QuoteRequest::whereBetween('created_at', $range)->select('status', DB::raw('COUNT(*) as c'))->groupBy('status')->pluck('c', 'status');
        $quoteTotal    = (int) $quoteCounts->sum();
        $quoteAccepted = (int) ($quoteCounts['accepted'] ?? 0);
        $quoteRate     = $quoteTotal > 0 ? ($quoteAccepted / $quoteTotal) * 100 : 0;
        $quoteValue    = (float) QuoteRequest::whereBetween('created_at', $range)->where('status', 'accepted')->sum('quoted_price');

        // ===== 10) รีวิว + ความต้องการ (Wishlist) =====
        $reviewAvg     = (float) Review::where('is_approved', true)->avg('rating');
        $reviewCount   = Review::where('is_approved', true)->count();
        $reviewPending = Review::where('is_approved', false)->count();

        $topWishlist = DB::table('wishlists')->select('product_id', DB::raw('COUNT(*) as c'))
            ->groupBy('product_id')->orderByDesc('c')->take(8)->get();
        $wishlistNames = Product::whereIn('id', $topWishlist->pluck('product_id'))->pluck('name', 'id');

        return view('admin.reports.index', compact(
            'period', 'from', 'to',
            'totalRevenue', 'paidOrders', 'totalOrders', 'newCustomers', 'avgOrderValue', 'totalDiscount',
            'revenueChange', 'ordersChange',
            'dailyRevenue', 'topProducts',
            'salesByCategory', 'salesByType', 'salesBySize',
            'inventoryValue', 'outOfStockCount', 'lowStockCount', 'lowStockList', 'slowMoving', 'slowMovingCount',
            'topCustomers', 'customerNames', 'newBuyerCount', 'returningCount', 'topProvinces',
            'ordersByStatus', 'cancelledCount', 'cancellationRate',
            'couponPerformance', 'couponInfo',
            'quoteCounts', 'quoteTotal', 'quoteAccepted', 'quoteRate', 'quoteValue',
            'reviewAvg', 'reviewCount', 'reviewPending', 'topWishlist', 'wishlistNames'
        ));
    }
}
