<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create() { return view('admin.banners.form'); }

    public function store(Request $request)
    {
        $request->validate([
            'image'      => 'required|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:width=1400,height=609',
            'link_url'   => 'nullable|url|max:500',
            'sort_order' => 'integer|min:0',
            'is_active'  => 'boolean',
        ]);

        Banner::create([
            'image_path' => $request->file('image')->store('banners', 'public'),
            'link_url'   => $request->link_url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        Cache::forget('home.banners');
        return redirect()->route('admin.banners.index')->with('success', 'เพิ่ม Banner แล้ว');
    }

    public function edit(Banner $banner) { return view('admin.banners.form', compact('banner')); }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:width=1400,height=609',
            'link_url'   => 'nullable|url|max:500',
            'sort_order' => 'integer|min:0',
            'is_active'  => 'boolean',
        ]);

        $data = [
            'link_url'   => $request->link_url,
            'sort_order' => $request->sort_order ?? $banner->sort_order,
            'is_active'  => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            \Storage::disk('public')->delete($banner->image_path);
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);
        Cache::forget('home.banners');
        return redirect()->route('admin.banners.index')->with('success', 'อัปเดต Banner แล้ว');
    }

    public function destroy(Banner $banner)
    {
        \Storage::disk('public')->delete($banner->image_path);
        $banner->delete();
        Cache::forget('home.banners');
        return redirect()->route('admin.banners.index')->with('success', 'ลบ Banner แล้ว');
    }

    public function reorder(Request $request)
    {
        foreach ($request->items as $item) {
            Banner::where('id', $item['id'])->update(['sort_order' => $item['order']]);
        }
        Cache::forget('home.banners');
        return response()->json(['success' => true]);
    }
}
