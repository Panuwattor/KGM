<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\CustomerCoupon;
use App\Models\FlashSaleItem;
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
        $product = Product::where('slug', $slug)->active()->with(['images', 'variants', 'category', 'reviews.customer'])->firstOrFail();
        $product->increment('view_count');

        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('images')
            ->take(4)->get();

        $inWishlist = auth('customer')->check()
            ? auth('customer')->user()->wishlists()->where('product_id', $product->id)->exists()
            : false;

        $flashSaleItem = FlashSaleItem::where('product_id', $product->id)
            ->whereHas('flashSale', fn($q) => $q->where('is_active', true)
                ->where('starts_at', '<=', now())
                ->where('ends_at', '>=', now()))
            ->with('flashSale')
            ->first();

        // คูปองที่ใช้กับสินค้านี้ได้ (เฉพาะสินค้านี้ / หมวดของสินค้านี้ / ใช้ได้ทั้งร้าน)
        $coupons = Coupon::publiclyVisible()
            ->with(['products', 'categories'])
            ->where(function ($q) use ($product) {
                $q->whereHas('products', fn($p) => $p->where('products.id', $product->id))
                  ->orWhereHas('categories', fn($c) => $c->where('categories.id', $product->category_id))
                  ->orWhere(fn($noScope) => $noScope->whereDoesntHave('products')->whereDoesntHave('categories'));
            })
            ->latest()
            ->get();

        $collectedIds = auth('customer')->check()
            ? CustomerCoupon::where('customer_id', auth('customer')->id())->pluck('coupon_id')->toArray()
            : [];

        return view('frontend.shop.show', compact('product', 'relatedProducts', 'inWishlist', 'flashSaleItem', 'coupons', 'collectedIds'));
    }

    public function submitReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string|max:1000',
        ]);

        $customer = auth('customer')->user();

        $hasPurchased = $customer->orders()
            ->whereIn('status', ['delivered', 'payment_verified', 'shipped', 'processing'])
            ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'คุณต้องซื้อสินค้านี้ก่อนจึงจะรีวิวได้');
        }

        Review::updateOrCreate(
            ['product_id' => $product->id, 'customer_id' => $customer->id],
            ['rating' => $request->rating, 'title' => $request->title, 'body' => $request->body, 'is_approved' => $request->rating >= 4]
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

        match($request->get('filter')) {
            'featured'   => $query->where('is_featured', true),
            'new'        => $query->where('is_new', true),
            'bestseller' => $query->where('is_bestseller', true),
            default      => null,
        };

        match($request->get('sort')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'bestseller' => $query->orderBy('sale_count', 'desc'),
            'newest'     => $query->orderBy('created_at', 'desc'),
            default      => $query->orderBy('sort_order')->orderBy('created_at', 'desc'),
        };
    }
}
