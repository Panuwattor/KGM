<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\DealerApplication;
use App\Models\JobApplication;
use App\Models\LandingPage;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\QuoteRequest;
use App\Models\Review;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.bootstrap-5');
        Paginator::defaultSimpleView('vendor.pagination.bootstrap-5');

        View::composer('layouts.app', function ($view) {
            $view->with([
                'navProductTypes'   => ProductType::active()->orderBy('sort_order')->get(),
                'navCategories'     => Category::active()->parents()->with(['children' => fn($q) => $q->active()->orderBy('sort_order')])->orderBy('sort_order')->get(),
                'activeLandingPage' => LandingPage::where('is_active', true)->first(),
            ]);
        });

        // แจ้งเตือนหลังบ้าน (กระดิ่งบน topbar admin) — รวมงานที่ "รอแอดมินจัดการ"
        View::composer('layouts.admin', function ($view) {
            $items = collect([
                [
                    'key'   => 'orders',
                    'label' => 'ออเดอร์อัปโหลดสลิป รอตรวจสอบ',
                    'icon'  => 'bi-receipt-cutoff',
                    'color' => '#3498db',
                    'count' => Order::where('status', 'payment_uploaded')->count(),
                    'url'   => Route::has('admin.orders.index') ? route('admin.orders.index', ['status' => 'payment_uploaded']) : '#',
                ],
                [
                    'key'   => 'quotes',
                    'label' => 'คำขอใบเสนอราคาใหม่',
                    'icon'  => 'bi-file-earmark-text',
                    'color' => '#8b6914',
                    'count' => QuoteRequest::where('status', 'pending')->count(),
                    'url'   => Route::has('admin.quotes.index') ? route('admin.quotes.index') : '#',
                ],
                [
                    'key'   => 'jobs',
                    'label' => 'ใบสมัครงานรอพิจารณา',
                    'icon'  => 'bi-briefcase',
                    'color' => '#2d6a4f',
                    'count' => JobApplication::where('status', 'new')->count(),
                    'url'   => Route::has('admin.careers.index') ? route('admin.careers.index') : '#',
                ],
                [
                    'key'   => 'dealers',
                    'label' => 'ใบสมัครตัวแทนจำหน่ายใหม่',
                    'icon'  => 'bi-shop',
                    'color' => '#9b59b6',
                    'count' => DealerApplication::where('status', 'new')->count(),
                    'url'   => Route::has('admin.dealers.index') ? route('admin.dealers.index') : '#',
                ],
                [
                    'key'   => 'reviews',
                    'label' => 'รีวิวสินค้ารออนุมัติ',
                    'icon'  => 'bi-star-half',
                    'color' => '#c9a84c',
                    'count' => Review::where('is_approved', false)->count(),
                    'url'   => Route::has('admin.reviews.index') ? route('admin.reviews.index', ['status' => 'pending']) : '#',
                ],
                [
                    'key'   => 'contacts',
                    'label' => 'ข้อความติดต่อยังไม่อ่าน',
                    'icon'  => 'bi-chat-left-text',
                    'color' => '#16a085',
                    'count' => ContactMessage::where('is_read', false)->count(),
                    'url'   => Route::has('admin.contacts.index') ? route('admin.contacts.index') : '#',
                ],
                [
                    'key'   => 'stock',
                    'label' => 'สินค้าใกล้หมด/หมดสต็อก',
                    'icon'  => 'bi-box-seam',
                    'color' => '#e74c3c',
                    'count' => Product::where('manage_stock', true)
                        ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')->count(),
                    'url'   => Route::has('admin.products.index') ? route('admin.products.index', ['status' => 'low_stock']) : '#',
                ],
            ])->filter(fn ($i) => $i['count'] > 0)->values();

            $view->with([
                'adminNotifications' => $items,
                'adminNotifTotal'    => $items->sum('count'),
            ]);
        });
    }
}
