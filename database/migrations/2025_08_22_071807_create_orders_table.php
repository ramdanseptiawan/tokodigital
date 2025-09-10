<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->string('order_id')->unique();   // untuk Midtrans
            $t->foreignId('game_id')->constrained();
            $t->unsignedInteger('price');
            $t->string('status')->default('pending'); // pending|settlement|expire|cancel
            $t->string('customer_name');
            $t->string('customer_email');
            $t->string('customer_phone')->nullable();
            $t->string('midtrans_transaction_id')->nullable();
            $t->text('qr_url')->nullable();
            $t->longText('qr_string')->nullable();
            $t->timestamps();
          });
          
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
