<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    // Izinkan mass assignment untuk kolom-kolom ini
    protected $fillable = [
        'order_id',
        'game_id',
        'price',
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
        'price' => 'integer',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
