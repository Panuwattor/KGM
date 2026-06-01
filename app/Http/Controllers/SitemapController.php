<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $products = Product::active()->latest()->get(['slug', 'updated_at']);
        $categories = Category::active()->get(['slug', 'updated_at']);
        $posts = Post::published()->latest('published_at')->get(['slug', 'published_at']);

        $content = view('sitemap', compact('products', 'categories', 'posts'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }
}
