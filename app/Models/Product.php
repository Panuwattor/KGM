<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProductType;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'product_type_id', 'brand_id', 'name', 'slug', 'short_description', 'description',
        'price', 'sale_price', 'wholesale_price', 'sku', 'stock_quantity',
        'low_stock_threshold', 'manage_stock', 'is_active', 'is_featured',
        'is_new', 'is_bestseller', 'sort_order',
        'meta_title', 'meta_description', 'og_image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'manage_stock' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_bestseller' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->where('is_active', true);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function getCurrentPriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->sale_price && $this->price > 0) {
            return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return null;
    }

    public function getMainImageAttribute(): string
    {
        if ($this->relationLoaded('images')) {
            $primary = $this->images->firstWhere('is_primary', true);
            if ($primary) return $primary->image_path;
            $first = $this->images->first();
            return $first ? $first->image_path : 'images/no-product.png';
        }
        $primary = $this->primaryImage;
        if ($primary) return $primary->image_path;
        $first = $this->images()->first();
        return $first ? $first->image_path : 'images/no-product.png';
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBestseller($query)
    {
        return $query->where('is_bestseller', true);
    }

    public function isLowStock(): bool
    {
        return $this->manage_stock && $this->stock_quantity <= $this->low_stock_threshold;
    }
}
