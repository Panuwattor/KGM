<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'post_category_id', 'author_id', 'title', 'slug', 'excerpt', 'body',
        'featured_image', 'status', 'published_at', 'meta_title', 'meta_description', 'og_image', 'is_top',
    ];
    protected $casts = ['published_at' => 'datetime', 'is_top' => 'boolean'];

    public function postCategory() { return $this->belongsTo(PostCategory::class); }
    public function author() { return $this->belongsTo(User::class, 'author_id'); }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }
}
