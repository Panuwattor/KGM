<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductTypeController extends Controller
{
    public function index()
    {
        $types = ProductType::withCount('products')->orderBy('sort_order')->get();
        return view('admin.product-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.product-types.form');
    }

    public function store(Request $request)
    {
        $data = $this->validateType($request);
        $data['slug'] = Str::slug($request->name);
        $data['has_embroidery'] = $request->boolean('has_embroidery');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('product-types', 'public');
        }

        ProductType::create($data);
        Cache::forget('home.product_types');
        return redirect()->route('admin.product-types.index')->with('success', 'สร้างประเภทสินค้าแล้ว');
    }

    public function edit(ProductType $productType)
    {
        return view('admin.product-types.form', compact('productType'));
    }

    public function update(Request $request, ProductType $productType)
    {
        $data = $this->validateType($request);
        $data['has_embroidery'] = $request->boolean('has_embroidery');

        if ($request->hasFile('image')) {
            if ($productType->image) {
                Storage::disk('public')->delete($productType->image);
            }
            $data['image'] = $request->file('image')->store('product-types', 'public');
        }

        if ($request->boolean('remove_image') && $productType->image) {
            Storage::disk('public')->delete($productType->image);
            $data['image'] = null;
        }

        $productType->update($data);
        Cache::forget('home.product_types');
        return redirect()->route('admin.product-types.index')->with('success', 'อัปเดตประเภทสินค้าแล้ว');
    }

    public function destroy(ProductType $productType)
    {
        if ($productType->image) {
            Storage::disk('public')->delete($productType->image);
        }
        $productType->delete();
        Cache::forget('home.product_types');
        return redirect()->route('admin.product-types.index')->with('success', 'ลบประเภทสินค้าแล้ว');
    }

    private function validateType(Request $request): array
    {
        return $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'image'        => 'nullable|image|max:2048',
            'show_on_home'   => 'boolean',
            'sort_order'     => 'integer',
            'is_active'      => 'boolean',
            'has_embroidery' => 'boolean',
        ]);
    }
}
