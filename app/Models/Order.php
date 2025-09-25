<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasUuids;
    // Izinkan mass assignment untuk kolom-kolom ini
    protected $fillable = [
        'order_id',
        'game_id', // keep for backward compatibility
        'total_amount',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'midtrans_transaction_id',
        'qr_url',
        'qr_string',
    ];

    // (opsional) casting tipe data
    protected $casts = [
        'total_amount' => 'integer',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Helper untuk mendapatkan total dari order items
    public function getTotalFromItems(): int
    {
        return $this->orderItems->sum('total');
    }

    // Specify which columns should be treated as UUIDs
    public function uniqueIds(): array
    {
        return ['order_id'];
    }

    // Generate UUID for order_id when creating new order
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_id)) {
                $order->order_id = (string) Str::uuid();
            }
        });
    }
}
