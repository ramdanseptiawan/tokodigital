<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Game;            // <-- penting
use App\Models\Order;           // <-- penting
use App\Services\MidtransService; // <-- penting
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentPending;
use Illuminate\Support\Facades\Log;


class CheckoutController extends Controller
{
    public function store(Request $r, Game $game, MidtransService $mid)
    {
        $data = $r->validate([
            'name'  => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
        ]);

        $order = Order::create([
            'order_id'       => 'GM-'.Str::ulid(),
            'game_id'        => $game->id,
            'price'          => $game->price,
            'status'         => 'pending',
            'customer_name'  => $data['name'],
            'customer_email' => $data['email'],
            'customer_phone' => $data['phone'] ?? '',
        ]);

        $resp = $mid->chargeQris([
            'order_id'     => $order->order_id,
            'gross_amount' => $order->price,
            'item_details' => [[
                'id'       => (string)$game->id,
                'price'    => $game->price,
                'quantity' => 1,
                'name'     => $game->title,
            ]],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email'      => $order->customer_email,
                'phone'      => $order->customer_phone,
            ],
        ]);

        $actions = collect($resp->actions ?? []);
        $qr = $actions->firstWhere('name', 'generate-qr-code-v2')
            ?? $actions->firstWhere('name', 'generate-qr-code');

        $order->update([
            'midtrans_transaction_id' => $resp->transaction_id ?? null,
            'qr_url'    => $qr->url ?? null,
            'qr_string' => $resp->qr_string ?? ($resp->qris ?? null),
        ]);

        // Kirim email notifikasi pending payment untuk game
        try {
            // Untuk game checkout, kita perlu membuat struktur yang kompatibel dengan email template
            // Karena game menggunakan struktur berbeda dari product, kita perlu menyesuaikan
            Mail::to($order->customer_email)->send(new PaymentPending($order));
            Log::info('Email pending payment sent successfully (game)', [
                'order_id' => $order->order_id,
                'email' => $order->customer_email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send pending payment email (game)', [
                'order_id' => $order->order_id,
                'email' => $order->customer_email,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('orders.show', $order);
    }
}
