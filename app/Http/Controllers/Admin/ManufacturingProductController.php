<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManufacturingProduct;
use App\Models\ManufacturingProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManufacturingProductController extends Controller
{
    public function index()
    {
        $products = ManufacturingProduct::withCount('images')
            ->orderByDesc('is_active')
            ->orderBy('sort_order')
            ->get();
        return view('admin.manufacturing-products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.manufacturing-products.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('manufacturing-products', config('filesystems.media'));
        }

        $product = ManufacturingProduct::create($data);

        $this->storeGalleryImages($request, $product);

        return redirect()->route('admin.manufacturing-products.index')->with('success', 'เพิ่มสินค้าที่รับผลิตแล้ว');
    }

    public function edit(ManufacturingProduct $manufacturingProduct)
    {
        $manufacturingProduct->load('images');
        return view('admin.manufacturing-products.form', ['product' => $manufacturingProduct]);
    }

    public function update(Request $request, ManufacturingProduct $manufacturingProduct)
    {
        $data = $request->validate($this->rules(), $this->messages());
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($manufacturingProduct->image) {
                Storage::disk(config('filesystems.media'))->delete($manufacturingProduct->image);
            }
            $data['image'] = $request->file('image')->store('manufacturing-products', config('filesystems.media'));
        } else {
            unset($data['image']);
        }

        $manufacturingProduct->update($data);

        $this->storeGalleryImages($request, $manufacturingProduct);

        return redirect()->route('admin.manufacturing-products.index')->with('success', 'อัปเดตสินค้าที่รับผลิตแล้ว');
    }

    public function destroy(ManufacturingProduct $manufacturingProduct)
    {
        // ลบรูปหลัก
        if ($manufacturingProduct->image) {
            Storage::disk(config('filesystems.media'))->delete($manufacturingProduct->image);
        }
        // ลบรูปในแกลเลอรี
        foreach ($manufacturingProduct->images as $img) {
            Storage::disk(config('filesystems.media'))->delete($img->image_path);
        }
        $manufacturingProduct->delete();

        return redirect()->route('admin.manufacturing-products.index')->with('success', 'ลบสินค้าที่รับผลิตแล้ว');
    }

    /** ลบรูปในแกลเลอรีทีละรูป (AJAX) */
    public function deleteImage(ManufacturingProduct $manufacturingProduct, ManufacturingProductImage $image)
    {
        if ($image->manufacturing_product_id !== $manufacturingProduct->id) {
            abort(403);
        }
        Storage::disk(config('filesystems.media'))->delete($image->image_path);
        $image->delete();

        return response()->json(['success' => true]);
    }

    /** บันทึกรูปเพิ่มเติม (แกลเลอรี) จาก input name="images[]" */
    private function storeGalleryImages(Request $request, ManufacturingProduct $product): void
    {
        if (!$request->hasFile('images')) {
            return;
        }

        $sort = (int) $product->images()->max('sort_order');
        foreach ($request->file('images') as $file) {
            $path = $file->store('manufacturing-products', config('filesystems.media'));
            ManufacturingProductImage::create([
                'manufacturing_product_id' => $product->id,
                'image_path' => $path,
                'sort_order' => ++$sort,
            ]);
        }
    }

    private function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'boolean',
            // รูปหลัก: ไม่เกิน 2MB และควรมีขนาดอย่างน้อย 200x200
            'image'       => 'nullable|image|max:2048|dimensions:min_width=200,min_height=200',
            'images'      => 'nullable|array',
            'images.*'    => 'image|max:2048',
        ];
    }

    private function messages(): array
    {
        return [
            'image.max'        => 'รูปหลักต้องมีขนาดไฟล์ไม่เกิน 2 MB',
            'image.image'      => 'รูปหลักต้องเป็นไฟล์รูปภาพเท่านั้น',
            'image.dimensions' => 'รูปหลักต้องมีขนาดอย่างน้อย 200 x 200 พิกเซล',
            'images.*.max'     => 'รูปเพิ่มเติมแต่ละรูปต้องมีขนาดไฟล์ไม่เกิน 2 MB',
            'images.*.image'   => 'รูปเพิ่มเติมต้องเป็นไฟล์รูปภาพเท่านั้น',
        ];
    }
}
