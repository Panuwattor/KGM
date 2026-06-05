<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Review;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories   = Category::active()->parents()->with('children')->get();
        $productTypes = ProductType::active()->orderBy('sort_order')->get();
        $query = Product::active()->with(['images', 'category']);

        $this->applyFilters($query, $request);

        $products         = $query->paginate(16)->withQueryString();
        $selectedCategory = null;
        $selectedType     = $request->filled('type')
            ? ProductType::where('slug', $request->type)->first()
            : null;

        return view('frontend.shop.index', compact('products', 'categories', 'productTypes', 'selectedCategory', 'selectedType'));
    }

    public function category(Request $request, string $slug)
    {
        $selectedCategory = Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $categories   = Category::active()->parents()->with('children')->get();
        $productTypes = ProductType::active()->orderBy('sort_order')->get();

        $categoryIds = collect([$selectedCategory->id]);
        if ($selectedCategory->children->isNotEmpty()) {
            $categoryIds = $categoryIds->merge($selectedCategory->children->pluck('id'));
        }

        $query = Product::active()->whereIn('category_id', $categoryIds)->with(['images', 'category']);
        $this->applyFilters($query, $request);

        $products     = $query->paginate(16)->withQueryString();
        $selectedType = null;

        return view('frontend.shop.index', compact('products', 'categories', 'productTypes', 'selectedCategory', 'selectedType'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->active()->with(['images', 'variants', 'category', 'reviews.user'])->firstOrFail();
        $product->increment('view_count');

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('images')
            ->take(4)->get();

        $inWishlist = auth('customer')->check()
            ? auth('customer')->user()->wishlists()->where('product_id', $product->id)->exists()
            : false;

        return view('frontend.shop.show', compact('product', 'relatedProducts', 'inWishlist'));
    }

    public function submitReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string|max:1000',
        ]);

        $hasPurchased = auth()->user()->orders()
            ->whereIn('status', ['delivered', 'payment_verified'])
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'คุณต้องซื้อสินค้านี้ก่อนจึงจะรีวิวได้');
        }

        Review::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => auth()->id()],
            ['rating' => $request->rating, 'title' => $request->title, 'body' => $request->body, 'is_approved' => false]
        );

        return back()->with('success', 'ขอบคุณสำหรับรีวิวของคุณ จะแสดงหลังผ่านการตรวจสอบ');
    }

    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('type')) {
            $query->whereHas('productType', fn($q) => $q->where('slug', $request->type));
        }
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        if ($request->filled('size')) {
            $query->whereHas('variants', fn($q) => $q->where('size', $request->size));
        }

        match($request->get('sort')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'bestseller' => $query->orderBy('sale_count', 'desc'),
            'newest'     => $query->orderBy('created_at', 'desc'),
            default      => $query->orderBy('sort_order')->orderBy('created_at', 'desc'),
        };
    }
}
