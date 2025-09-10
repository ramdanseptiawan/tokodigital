<?php

namespace App\Http\Controllers;

use App\Models\Order; // <-- WAJIB
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    public function show(Order $order)   // <-- route model binding by id
    {
        return view('orders.show', compact('order'));
    }

    public function json(Order $order)
    {
        return response()->json(['status' => $order->status]);
    }
}
