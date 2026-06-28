<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Helpers\ThaiSlug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'productType')->withTrashed();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('product_type_id')) {
            $query->where('product_type_id', $request->product_type_id);
        }
        if ($request->filled('status')) {
            match($request->status) {
                'active'    => $query->where('is_active', true)->whereNull('deleted_at'),
                'inactive'  => $query->where('is_active', false)->whereNull('deleted_at'),
                'deleted'   => $query->onlyTrashed(),
                'low_stock' => $query->lowStock(),
                default     => null,
            };
        }
        if ($request->filled('flag')) {
            match($request->flag) {
                'featured'   => $query->where('is_featured', true),
                'new'        => $query->where('is_new', true),
                'bestseller' => $query->where('is_bestseller', true),
                default      => null,
            };
        }
        $products     = $query->orderBy('sort_order')->latest()->paginate(20)->withQueryString();
        $categories   = Category::active()->get();
        $productTypes = ProductType::active()->orderBy('sort_order')->get();
        return view('admin.products.index', compact('products', 'categories', 'productTypes'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $productTypes = ProductType::active()->orderBy('sort_order')->get();
        return view('admin.products.form', compact('categories', 'productTypes'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['free_embroidery'] = $request->boolean('free_embroidery');
        $data['slug'] = $request->filled('slug')
            ? $this->manualSlug($request->slug)          // แอดมินกรอกเอง → เก็บตามนั้น (รวมไทย)
            : $this->generateSlug($request->name);       // เว้นว่าง → สร้างจากชื่อ (แปลงเป็นอังกฤษ)

        $product = Product::create($data);

        $this->saveVariants($product, $request);
        AdminLog::record('created', "สร้างสินค้า: {$product->name}", $product);
        $this->clearProductCache();

        return redirect()->route('admin.products.edit', $product)->with('success', 'สร้างสินค้าเรียบร้อยแล้ว');
    }

    public function show(Product $product)
    {
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(Product $product)
    {
        $product->load('images', 'variants');
        $categories = Category::active()->get();
        $productTypes = ProductType::active()->orderBy('sort_order')->get();
        return view('admin.products.form', compact('product', 'categories', 'productTypes'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product->id);
        $data['free_embroidery'] = $request->boolean('free_embroidery');

        // Only update slug if admin explicitly changed it (เก็บตามที่กรอก รวมไทย)
        if ($request->filled('slug') && $request->slug !== $product->slug) {
            $data['slug'] = $this->manualSlug($request->slug, $product->id);
        }

        $old = $product->toArray();
        $product->update($data);
        $this->saveVariants($product, $request);
        AdminLog::record('updated', "อัปเดตสินค้า: {$product->name}", $product, $old, $data);
        $this->clearProductCache();
        return redirect()->route('admin.products.edit', $product)->with('success', 'อัปเดตสินค้าเรียบร้อยแล้ว');
    }

    public function destroy(Product $product)
    {
        AdminLog::record('deleted', "ลบสินค้า: {$product->name}", $product);
        $product->delete();
        $this->clearProductCache();
        return redirect()->route('admin.products.index')->with('success', 'ลบสินค้าแล้ว');
    }

    public function uploadImages(Request $request, Product $product)
    {
        $request->validate(['images.*' => 'required|image|max:5120']);
        $isPrimary = $product->images()->count() === 0;

        foreach ($request->file('images') as $file) {
            $path = $file->store('products', config('filesystems.media'));
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'sort_order' => $product->images()->max('sort_order') + 1,
                'is_primary' => $isPrimary,
            ]);
            $isPrimary = false;
        }

        return response()->json(['success' => true]);
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) abort(403);
        Storage::disk(config('filesystems.media'))->delete($image->image_path);
        $image->delete();
        if ($image->is_primary) {
            $product->images()->first()?->update(['is_primary' => true]);
        }
        return response()->json(['success' => true]);
    }

    /** สร้าง slug จากชื่อสินค้าอัตโนมัติ (แปลงไทย→อังกฤษ RTGS) */
    private function generateSlug(string $name, ?int $ignoreId = null): string
    {
        return $this->uniqueSlug(ThaiSlug::make($name), $ignoreId);
    }

    /**
     * slug ที่แอดมินกรอกเอง — เก็บตามที่พิมพ์ รองรับภาษาไทย
     * normalize เบาๆ: ตัวพิมพ์เล็ก, เว้นวรรค→'-', ตัดอักขระที่ไม่ปลอดภัยใน URL ทิ้ง
     */
    private function manualSlug(string $input, ?int $ignoreId = null): string
    {
        $slug = mb_strtolower(trim($input));
        $slug = preg_replace('/\s+/u', '-', $slug);                 // เว้นวรรค → -
        $slug = preg_replace('/[^\p{L}\p{N}\p{M}_-]+/u', '', $slug); // เก็บตัวอักษร(รวมไทย)/ตัวเลข/สระวรรณยุกต์/-/_
        $slug = preg_replace('/-+/u', '-', $slug);                  // ยุบ - ซ้ำ
        $slug = trim($slug, '-_');

        // ถ้าพิมพ์มามีแต่อักขระพิเศษจนเหลือว่าง → ใช้ค่า fallback
        if ($slug === '') {
            $slug = 'product';
        }

        return $this->uniqueSlug($slug, $ignoreId);
    }

    /** ต่อท้ายเลขลำดับถ้า slug ซ้ำกับสินค้าตัวอื่น */
    private function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = $base;
        $counter = 2;
        while (Product::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    private function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'               => 'required|string|max:255',
            'category_id'        => 'nullable|exists:categories,id',
            'product_type_id'    => 'nullable|exists:product_types,id',
            'short_description'  => 'nullable|string|max:500',
            'description'        => 'nullable|string',
            'price'              => 'required|numeric|min:0',
            'sale_price'         => 'nullable|numeric|min:0',
            'wholesale_price'    => 'nullable|numeric|min:0',
            'sku'                => 'nullable|string|max:100|unique:products,sku,' . $ignoreId,
            'stock_quantity'     => 'required|integer|min:0',
            'low_stock_threshold'=> 'integer|min:0',
            'manage_stock'       => 'boolean',
            'is_active'          => 'boolean',
            'is_featured'        => 'boolean',
            'is_new'             => 'boolean',
            'is_bestseller'      => 'boolean',
            'free_embroidery'    => 'boolean',
            'sort_order'         => 'integer',
            'meta_title'         => 'nullable|string|max:255',
            'meta_description'   => 'nullable|string|max:500',
        ]);
    }

    private function clearProductCache(): void
    {
        Cache::forget('home.featured');
        Cache::forget('home.bestseller');
        Cache::forget('home.new_products');
    }

    private function saveVariants(Product $product, Request $request): void
    {
        if (!$request->filled('variants')) {
            $product->variants()->delete();
            return;
        }

        $product->variants()->delete();
        $totalStock = 0;
        $hasVariant = false;
        foreach ($request->variants as $variant) {
            if (empty($variant['size'])) continue;
            ProductVariant::create([
                'product_id'       => $product->id,
                'size'             => $variant['size'] ?? null,
                'sku'              => $variant['sku'] ?? null,
                'price_adjustment'    => $variant['price_adjustment'] ?? 0,
                'stock_quantity'      => $variant['stock_quantity'] ?? 0,
                'low_stock_threshold' => $variant['low_stock_threshold'] ?? 5,
            ]);
            $totalStock += (int) ($variant['stock_quantity'] ?? 0);
            $hasVariant = true;
        }

        // เมื่อมี variants สต๊อกรวมของสินค้า = ผลรวมสต๊อกของทุก variant
        if ($hasVariant) {
            $product->update(['stock_quantity' => $totalStock]);
        }
    }
}
