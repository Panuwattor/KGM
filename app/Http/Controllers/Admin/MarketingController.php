<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarketingController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::with('items.product')->latest()->get();
        $promotions = Promotion::latest()->get();
        return view('admin.marketing.index', compact('flashSales', 'promotions'));
    }

    public function create()
    {
        $products = Product::active()->with(['images', 'variants' => fn($q) => $q->where('is_active', true)->orderBy('size')])->get();
        return view('admin.marketing.form', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'starts_at' => 'required|date',
            'ends_at'   => 'required|date|after:starts_at',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data = $request->only('name', 'starts_at', 'ends_at');
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('flash-sales', 'public');
        }

        $flashSale = FlashSale::create($data);

        foreach ($request->input('products', []) as $productId => $data) {
            if (empty($data['selected']) || !isset($data['price']) || $data['price'] === '') continue;
            FlashSaleItem::create([
                'flash_sale_id' => $flashSale->id,
                'product_id'    => $productId,
                'sale_price'    => $data['price'],
                'stock_limit'   => ($data['stock_limit'] !== '' ? $data['stock_limit'] : null),
            ]);
        }

        return redirect()->route('admin.marketing.index')->with('success', 'สร้าง Flash Sale แล้ว');
    }

    public function edit(FlashSale $flashSale)
    {
        $flashSale->load('items.product');
        $products = Product::active()->with('images')->get();
        return view('admin.marketing.form', compact('flashSale', 'products'));
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'starts_at' => 'required|date',
            'ends_at'   => 'required|date|after:starts_at',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $data = $request->only('name', 'starts_at', 'ends_at');
        $data['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            if ($flashSale->image) {
                Storage::disk('public')->delete($flashSale->image);
            }
            $data['image'] = $request->file('image')->store('flash-sales', 'public');
        }

        $flashSale->update($data);

        $flashSale->items()->delete();
        foreach ($request->input('products', []) as $productId => $data) {
            if (empty($data['selected']) || !isset($data['price']) || $data['price'] === '') continue;
            FlashSaleItem::create([
                'flash_sale_id' => $flashSale->id,
                'product_id'    => $productId,
                'sale_price'    => $data['price'],
                'stock_limit'   => ($data['stock_limit'] !== '' ? $data['stock_limit'] : null),
            ]);
        }

        return redirect()->route('admin.marketing.index')->with('success', 'อัปเดต Flash Sale แล้ว');
    }

    public function destroy(FlashSale $flashSale)
    {
        if ($flashSale->image) {
            Storage::disk('public')->delete($flashSale->image);
        }
        $flashSale->delete();
        return redirect()->route('admin.marketing.index')->with('success', 'ลบ Flash Sale แล้ว');
    }
}
