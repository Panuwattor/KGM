<?php

namespace App\Models;

use App\Helpers\Slugger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'image',
        'sort_order', 'is_active', 'meta_title', 'meta_description', 'og_image',
    ];

    protected $casts = ['is_active' => 'boolean'];

    protected static function booted(): void
    {
        static::saving(function (self $category) {
            // แอดมินกรอก slug เอง → normalize ตามที่พิมพ์ (เก็บไทยไว้)
            // เว้นว่าง → สร้างจากชื่อหมวดหมู่
            $source = filled($category->slug) ? $category->slug : (string) $category->name;

            $category->slug = Slugger::forModel($category, $source)
                // normalize แล้วเหลือว่าง (เช่นชื่อเป็นอีโมจิล้วน) และยังไม่มี id
                // → ใส่ค่าชั่วคราวกัน NOT NULL ไว้ก่อน แล้วให้ created() เขียนทับด้วย id
                ?? ($category->exists ? 'category-' . $category->getKey() : uniqid('tmp-', true));
        });

        static::created(function (self $category) {
            if (str_starts_with($category->slug, 'tmp-')) {
                $category->slug = 'category-' . $category->getKey();
                $category->saveQuietly();
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }
}
