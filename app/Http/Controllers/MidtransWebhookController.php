<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MidtransService;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentSuccessful;
use App\Mail\PaymentPending;

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

        // Map Midtrans status to our application status
        $mappedStatus = $this->mapMidtransStatus($trxStatus);

        // update order
        if ($order = Order::where('order_id', $payload['order_id'])->first()) {
            $oldStatus = $order->status;
            $order->status = $mappedStatus;
            $order->save();

            // Send email notifications based on status change
            $this->sendEmailNotification($order, $oldStatus, $mappedStatus);
        }

        return response()->json(['received' => true], 200);
    }

    /**
     * Send email notification based on payment status
     */
    private function sendEmailNotification(Order $order, string $oldStatus, string $newStatus): void
    {
        try {
            // Only send email if status actually changed
            if ($oldStatus === $newStatus) {
                return;
            }

            // Send email based on new status
            switch ($newStatus) {
                case 'paid':
                    Mail::to($order->customer_email)->send(new PaymentSuccessful($order));
                    Log::info("Payment successful email sent to {$order->customer_email} for order {$order->order_id}");
                    break;
                    
                case 'pending':
                    // Only send pending email if it's a new order or status changed from failed/cancelled
                    if (in_array($oldStatus, ['failed', 'cancelled', 'expire']) || $oldStatus === null) {
                        Mail::to($order->customer_email)->send(new PaymentPending($order));
                        Log::info("Payment pending email sent to {$order->customer_email} for order {$order->order_id}");
                    }
                    break;
            }
        } catch (\Exception $e) {
            Log::error("Failed to send email notification for order {$order->order_id}: " . $e->getMessage());
        }
    }

    /**
     * Map Midtrans transaction status to application status
     */
    private function mapMidtransStatus(string $midtransStatus): string
    {
        return match ($midtransStatus) {
            'settlement', 'capture' => 'paid',
            'pending' => 'pending',
            'expire' => 'expire',
            'cancel', 'deny', 'failure' => 'cancel',
            default => $midtransStatus,
        };
    }
}