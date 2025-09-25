<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'type',
        'cover_url',
        'cover_image',
        'metadata',
        'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'metadata' => 'array',
        'is_active' => 'boolean',
    ];

    // Relasi: satu product bisa punya banyak order
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Route key untuk slug
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Scope untuk produk aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope berdasarkan type
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helper method untuk mendapatkan URL gambar cover
    public function getCoverImageUrlAttribute(): ?string
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        
        return $this->cover_url;
    }

    // Accessor untuk memastikan metadata selalu berupa array
    public function getMetadataAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?: [];
        }
        
        return $value ?: [];
    }
}
