<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Review;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->get();
        $categories = Category::active()->whereNull('parent_id')->whereNotNull('image')->orderBy('sort_order')->get();
        $productTypes = ProductType::active()->where('show_on_home', true)->whereNotNull('image')->orderBy('sort_order')->get();
        $featuredProducts = Product::active()->featured()->with('images')->latest()->take(10)->get();
        $bestsellerProducts = Product::active()->bestseller()->with('images')->latest()->take(10)->get();
        $newProducts = Product::active()->where('is_new', true)->with('images')->latest()->take(10)->get();
        $testimonials = Review::where('is_approved', true)->with('user', 'product')->latest()->take(6)->get();
        $latestPosts = Post::published()->with('postCategory')->latest('published_at')->take(3)->get();

        return view('frontend.home', compact(
            'banners', 'categories', 'productTypes', 'featuredProducts', 'bestsellerProducts', 'newProducts', 'testimonials', 'latestPosts'
        ));
    }
}
