<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\CartCheckoutController;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentPending;
use App\Models\Order;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test Email Pending Saat Buat Transaksi ===\n\n";

try {
    // Test 1: Cek apakah ada order dengan status pending
    $pendingOrder = Order::where('status', 'pending')
                         ->with(['orderItems.product', 'game'])
                         ->first();
    
    if (!$pendingOrder) {
        echo "❌ Tidak ada order dengan status pending untuk di-test\n";
        echo "Silakan buat transaksi baru terlebih dahulu\n";
        exit(1);
    }
    
    echo "✅ Ditemukan order pending: {$pendingOrder->order_id}\n";
    echo "   Email: {$pendingOrder->customer_email}\n";
    echo "   Total: Rp " . number_format($pendingOrder->total_amount ?? $pendingOrder->price, 0, ',', '.') . "\n\n";
    
    // Test 2: Kirim email pending untuk order ini
    echo "📧 Mengirim email pending...\n";
    
    Mail::to($pendingOrder->customer_email)->send(new PaymentPending($pendingOrder));
    
    echo "✅ Email pending berhasil dikirim!\n";
    echo "   Periksa inbox: {$pendingOrder->customer_email}\n\n";
    
    // Test 3: Tampilkan informasi order
    echo "📋 Detail Order:\n";
    echo "   Order ID: {$pendingOrder->order_id}\n";
    echo "   Status: {$pendingOrder->status}\n";
    echo "   Customer: {$pendingOrder->customer_name}\n";
    echo "   Email: {$pendingOrder->customer_email}\n";
    
    if ($pendingOrder->orderItems && $pendingOrder->orderItems->count() > 0) {
        echo "   Tipe: Product Order\n";
        echo "   Items: {$pendingOrder->orderItems->count()} produk\n";
    } elseif ($pendingOrder->game) {
        echo "   Tipe: Game Order\n";
        echo "   Game: {$pendingOrder->game->title}\n";
    }
    
    echo "\n✅ Test selesai! Email pending sudah dikirim.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}