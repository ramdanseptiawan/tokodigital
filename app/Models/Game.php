<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    // Izinkan mass assignment
    protected $fillable = [
        'title',
        'slug',
        'price',
        'cover_url',
        'description',
    ];

    // Relasi: satu game bisa punya banyak order
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Supaya route {game:slug} bisa dipakai
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
