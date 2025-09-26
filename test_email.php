<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Mail;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Test basic email
    Mail::raw('Test email dari Laravel - ' . date('Y-m-d H:i:s'), function($message) {
        $message->to('ramdan.einstein@gmail.com')
                ->subject('Test Email Laravel');
    });
    
    echo "✅ Test email berhasil dikirim!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}