<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;

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
        $products = Product::active()->with('images')->get();
        return view('admin.marketing.form', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'starts_at'  => 'required|date',
            'ends_at'    => 'required|date|after:starts_at',
            'products'   => 'required|array|min:1',
        ]);

        $flashSale = FlashSale::create($request->only('name', 'starts_at', 'ends_at', 'is_active'));

        foreach ($request->products as $productId => $data) {
            FlashSaleItem::create([
                'flash_sale_id' => $flashSale->id,
                'product_id'    => $productId,
                'sale_price'    => $data['price'],
                'stock_limit'   => $data['stock_limit'] ?? null,
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
        $flashSale->update($request->only('name', 'starts_at', 'ends_at', 'is_active'));
        return redirect()->route('admin.marketing.index')->with('success', 'อัปเดต Flash Sale แล้ว');
    }

    public function destroy(FlashSale $flashSale)
    {
        $flashSale->delete();
        return redirect()->route('admin.marketing.index')->with('success', 'ลบ Flash Sale แล้ว');
    }
}
