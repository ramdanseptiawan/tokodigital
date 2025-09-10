<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MidtransService;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function __invoke(Request $request, MidtransService $mid)
    {
        $payload = $request->all();
        Log::info('midtrans-notify', $payload);

        // validasi minimal field
        if (!isset($payload['order_id'],$payload['status_code'],$payload['gross_amount'],$payload['signature_key'])) {
            return response()->json(['message'=>'bad payload'], 400);
        }

        // verifikasi signature
        if (!MidtransService::verifySignature(
            $payload['order_id'],
            $payload['status_code'],
            $payload['gross_amount'],
            $payload['signature_key']
        )) {
            return response()->json(['message'=>'invalid signature'], 403);
        }

        // (opsional) challenge ke Midtrans
        $statusObj = $mid->getStatus($payload['order_id']);
        $trxStatus = $statusObj->transaction_status ?? $payload['transaction_status'] ?? 'pending';

        // update order
        if ($order = Order::where('order_id', $payload['order_id'])->first()) {
            $order->status = $trxStatus;
            $order->save();
        }

        return response()->json(['received' => true], 200);
    }
}