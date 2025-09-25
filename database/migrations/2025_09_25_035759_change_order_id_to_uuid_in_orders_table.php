<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update existing order_ids to UUID format
        $orders = DB::table('orders')->get();
        foreach ($orders as $order) {
            $uuid = (string) \Illuminate\Support\Str::uuid();
            DB::table('orders')
                ->where('id', $order->id)
                ->update(['order_id' => $uuid]);
        }
        
        // Then change the column type to UUID
        DB::statement('ALTER TABLE orders ALTER COLUMN order_id TYPE uuid USING order_id::uuid');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_id')->change();
        });
    }
};
