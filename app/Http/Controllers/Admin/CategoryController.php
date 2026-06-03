<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);
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

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        if ($request->boolean('remove_image') && $category->image) {
            Storage::disk('public')->delete($category->image);
            $data['image'] = null;
        }

        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'อัปเดตหมวดหมู่แล้ว');
    }

    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'ลบหมวดหมู่แล้ว');
    }

    private function validateCategory(Request $request): array
    {
        return $request->validate([
            'name'             => 'required|string|max:255',
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
