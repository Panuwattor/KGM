<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\CustomerCoupon;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Review;
use App\Models\Post;
use App\Models\Video;
use App\Models\FlashSale;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $banners          = Cache::remember('home.banners',          300, fn() => Banner::active()->get());
        $categories       = Cache::remember('home.categories',       300, fn() => Category::active()->whereNull('parent_id')->whereNotNull('image')->orderBy('sort_order')->get());
        $productTypes     = Cache::remember('home.product_types',    300, fn() => ProductType::active()->where('show_on_home', true)->whereNotNull('image')->orderBy('sort_order')->get());
        $featuredProducts = Cache::remember('home.featured',         300, fn() => Product::active()->featured()->with(['images', 'category'])->latest()->take(10)->get());
        $bestsellerProducts = Cache::remember('home.bestseller',     300, fn() => Product::active()->bestseller()->with(['images', 'category'])->latest()->take(10)->get());
        $newProducts      = Cache::remember('home.new_products',     300, fn() => Product::active()->where('is_new', true)->with(['images', 'category'])->latest()->take(10)->get());
        $testimonials     = Cache::remember('home.testimonials',     600, fn() => Review::where('is_approved', true)->with('customer', 'product')->latest()->take(6)->get());
        $latestPosts      = Cache::remember('home.latest_posts',     300, fn() => Post::published()->with('postCategory')->latest('published_at')->take(3)->get());
        $homeCoupons      = Cache::remember('home.coupons',          300, fn() => Coupon::publiclyVisible()->latest()->get());
        $featuredVideos   = Cache::remember('home.videos',           300, fn() => Video::active()->featured()->orderBy('sort_order')->latest()->take(6)->get());
        $activeFlashSales = Cache::remember('home.flash_sales', 60, fn() => FlashSale::where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->with('items.product:id,price')
            ->latest()
            ->get());

        $collectedIds = auth('customer')->check()
            ? CustomerCoupon::where('customer_id', auth('customer')->id())->pluck('coupon_id')->toArray()
            : [];

        return view('frontend.home', compact(
            'banners', 'categories', 'productTypes', 'featuredProducts', 'bestsellerProducts', 'newProducts', 'testimonials', 'latestPosts', 'homeCoupons', 'collectedIds', 'featuredVideos', 'activeFlashSales'
        ));
    }
}
