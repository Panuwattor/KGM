<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent', 'children')->withCount('products')->orderBy('sort_order')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->active()->get();
        return view('admin.categories.form', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $this->validateCategory($request);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', config('filesystems.media'));
        }

        Category::create($data);
        Cache::forget('home.categories');
        return redirect()->route('admin.categories.index')->with('success', 'สร้างหมวดหมู่แล้ว');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')->where('id', '!=', $category->id)->active()->get();
        return view('admin.categories.form', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validateCategory($request);

        // เว้นช่อง slug ว่างไว้ = ไม่แก้ URL เดิม (ป้องกันลิงก์เก่าพังโดยไม่ตั้งใจ)
        if ($request->filled('slug')) {
            $data['slug'] = $request->slug;
        } else {
            unset($data['slug']);
        }

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk(config('filesystems.media'))->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', config('filesystems.media'));
        }

        if ($request->boolean('remove_image') && $category->image) {
            Storage::disk(config('filesystems.media'))->delete($category->image);
            $data['image'] = null;
        }

        $category->update($data);
        Cache::forget('home.categories');
        return redirect()->route('admin.categories.index')->with('success', 'อัปเดตหมวดหมู่แล้ว');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:categories,id',
        ]);

        foreach ($request->ids as $index => $id) {
            Category::where('id', $id)->update(['sort_order' => $index]);
        }

        Cache::forget('home.categories');
        return response()->json(['success' => true]);
    }

    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk(config('filesystems.media'))->delete($category->image);
        }
        $category->delete();
        Cache::forget('home.categories');
        return redirect()->route('admin.categories.index')->with('success', 'ลบหมวดหมู่แล้ว');
    }

    private function validateCategory(Request $request): array
    {
        return $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255',
            'parent_id'        => 'nullable|exists:categories,id',
            'description'      => 'nullable|string',
            'sort_order'       => 'integer',
            'is_active'        => 'boolean',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'image'            => 'nullable|image|max:2048',
        ]);
    }
}
