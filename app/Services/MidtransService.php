<?php

namespace App\Services;

use Midtrans\Config as MidConfig;
use Midtrans\CoreApi;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        MidConfig::$serverKey    = config('midtrans.server_key');
        MidConfig::$isProduction = config('midtrans.is_production');
        MidConfig::$isSanitized  = true;

        if (config('midtrans.append_notif_url')) {
            MidConfig::$appendNotifUrl = config('midtrans.append_notif_url');
        }
        if (config('midtrans.override_notif_url')) {
            MidConfig::$overrideNotifUrl = config('midtrans.override_notif_url');
        }
    }

    public function chargeQris(array $payload)
    {
        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id'     => $payload['order_id'],
                'gross_amount' => (int) $payload['gross_amount'],
            ],
            'item_details'      => $payload['item_details'],
            'customer_details'  => $payload['customer_details'],
        ];
        return CoreApi::charge($params);
    }

    public function getStatus(string $orderId)
    {
        return Transaction::status($orderId);
    }

    public static function verifySignature(string $orderId, string $statusCode, $grossAmount, string $signatureKey): bool
    {
        $serverKey = config('midtrans.server_key');
        $expected  = hash('sha512', $orderId.$statusCode.$grossAmount.$serverKey);
        return hash_equals($expected, $signatureKey);
    }
}
