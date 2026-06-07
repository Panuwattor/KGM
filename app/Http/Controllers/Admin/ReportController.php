<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30');
        $startDate = now()->subDays($period);

        $totalRevenue = Order::whereNotIn('status', ['cancelled', 'refunded'])
            ->where('created_at', '>=', $startDate)->sum('total');
        $totalOrders = Order::where('created_at', '>=', $startDate)->count();
        $newCustomers = User::where('role', 'customer')->where('created_at', '>=', $startDate)->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $dailyRevenue = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total'),
            DB::raw('COUNT(*) as orders')
        )
        ->whereNotIn('status', ['cancelled', 'refunded'])
        ->where('created_at', '>=', $startDate)
        ->groupBy('date')->orderBy('date')->get();

        $topStats = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNotIn('orders.status', ['cancelled', 'refunded'])
            ->where('orders.created_at', '>=', $startDate)
            ->select(
                'order_items.product_id',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('order_items.product_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get()
            ->keyBy('product_id');

        $topProducts = Product::whereIn('id', $topStats->keys())
            ->get()
            ->map(function ($product) use ($topStats) {
                $stat = $topStats->get($product->id);
                $product->total_qty = $stat->total_qty;
                $product->total_revenue = $stat->total_revenue;
                return $product;
            })
            ->sortByDesc('total_qty')
            ->values();

        return view('admin.reports.index', compact(
            'totalRevenue', 'totalOrders', 'newCustomers', 'avgOrderValue',
            'dailyRevenue', 'topProducts', 'period'
        ));
    }
}
