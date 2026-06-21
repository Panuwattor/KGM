<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ContactMessage;
use App\Models\QuoteRequest;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->whereNotIn('status', ['cancelled', 'refunded'])->sum('total');
        $pendingOrders = Order::whereIn('status', ['pending_payment', 'payment_uploaded'])->count();
        $totalCustomers = Customer::count();
        $lowStockProducts = Product::where('manage_stock', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->count();
        $unreadMessages = ContactMessage::where('is_read', false)->count();
        $pendingQuotes = QuoteRequest::where('status', 'pending')->count();

        // Monthly revenue chart (last 6 months)
        $monthlyRevenue = Order::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total) as total')
        )
        ->whereNotIn('status', ['cancelled', 'refunded'])
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year')->orderBy('month')
        ->get();

        // Recent orders
        $recentOrders = Order::with('customer')->latest()->take(10)->get();

        // Top products
        $topProducts = Product::withSum(['orderItems as total_sold' => fn($q) =>
            $q->whereHas('order', fn($q2) => $q2->whereNotIn('status', ['cancelled']))
        ], 'quantity')
        ->orderByDesc('total_sold')->take(5)->get();

        return view('admin.dashboard', compact(
            'todayOrders', 'todayRevenue', 'pendingOrders', 'totalCustomers',
            'lowStockProducts', 'unreadMessages', 'pendingQuotes',
            'monthlyRevenue', 'recentOrders', 'topProducts'
        ));
    }
}
