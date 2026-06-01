<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'admin_id', 'admin_name', 'action', 'model_type', 'model_id',
        'description', 'old_values', 'new_values', 'ip_address', 'user_agent',
    ];
    protected $casts = ['old_values' => 'array', 'new_values' => 'array', 'created_at' => 'datetime'];

    public function admin() { return $this->belongsTo(User::class, 'admin_id'); }

    public static function record(string $action, string $description, ?Model $model = null, array $old = [], array $new = []): void
    {
        $admin = auth()->user();
        static::create([
            'admin_id' => $admin?->id,
            'admin_name' => $admin?->name,
            'action' => $action,
            'model_type' => $model ? class_basename($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'old_values' => $old ?: null,
            'new_values' => $new ?: null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
