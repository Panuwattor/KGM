<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $coupon     = null;
        $products   = Product::active()->orderBy('name')->get(['id', 'name']);
        $categories = Category::active()->orderBy('name')->get(['id', 'name']);
        return view('admin.coupons.form', compact('coupon', 'products', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateCoupon($request);
        $data['code']      = strtoupper($data['code']);
        $data['is_active']    = $request->boolean('is_active');
        $data['is_public']    = $request->boolean('is_public');
        $data['is_stackable'] = $request->boolean('is_stackable');
        if ($data['type'] === 'free_shipping') $data['value'] = 0;
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('coupons', 'public');
        }
        $coupon = Coupon::create($data);
        $coupon->products()->sync($request->input('product_ids', []));
        $coupon->categories()->sync($request->input('category_ids', []));
        Cache::forget('home.coupons');
        return redirect()->route('admin.coupons.index')->with('success', 'สร้างคูปองแล้ว');
    }

    public function edit(Coupon $coupon)
    {
        $coupon->load(['products', 'categories']);
        $products   = Product::active()->orderBy('name')->get(['id', 'name']);
        $categories = Category::active()->orderBy('name')->get(['id', 'name']);
        return view('admin.coupons.form', compact('coupon', 'products', 'categories'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $this->validateCoupon($request, $coupon->id);
        $data['is_active']    = $request->boolean('is_active');
        $data['is_public']    = $request->boolean('is_public');
        $data['is_stackable'] = $request->boolean('is_stackable');
        if ($data['type'] === 'free_shipping') $data['value'] = 0;
        unset($data['image']);
        if ($request->hasFile('image')) {
            if ($coupon->image) Storage::disk('public')->delete($coupon->image);
            $data['image'] = $request->file('image')->store('coupons', 'public');
        }
        $coupon->update($data);
        $coupon->products()->sync($request->input('product_ids', []));
        $coupon->categories()->sync($request->input('category_ids', []));
        Cache::forget('home.coupons');
        return redirect()->route('admin.coupons.index')->with('success', 'อัปเดตคูปองแล้ว');
    }

    public function destroy(Coupon $coupon)
    {
        if ($coupon->image) Storage::disk('public')->delete($coupon->image);
        $coupon->delete();
        Cache::forget('home.coupons');
        return redirect()->route('admin.coupons.index')->with('success', 'ลบคูปองแล้ว');
    }

    private function validateCoupon(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'code'             => 'required|string|max:50|unique:coupons,code,' . $ignoreId,
            'name'             => 'required|string|max:255',
            'image'            => 'nullable|image|max:2048',
            'type'             => 'required|in:percent,fixed,free_shipping',
            'value'            => 'required|numeric|min:0',
            'minimum_order'    => 'numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:1',
            'per_user_limit'   => 'integer|min:1',
            'is_active'        => 'boolean',
            'starts_at'        => 'nullable|date',
            'expires_at'       => 'nullable|date',
        ]);
    }
}
