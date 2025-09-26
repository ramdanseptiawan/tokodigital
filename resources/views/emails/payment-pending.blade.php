<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .pending-icon {
            font-size: 48px;
            color: #ffc107;
            margin-bottom: 20px;
        }
        .order-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .order-item {
            border-bottom: 1px solid #dee2e6;
            padding: 15px 0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            color: #ffc107;
            text-align: right;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #ffc107;
        }
        .button {
            display: inline-block;
            background-color: #ffc107;
            color: #212529;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
        }
        .urgent {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="pending-icon">⏰</div>
            <h1 style="color: #ffc107; margin: 0;">Menunggu Pembayaran</h1>
            <p style="color: #6c757d; margin: 10px 0 0 0;">Segera selesaikan pembayaran Anda</p>
        </div>

        <div class="urgent">
            <h3 style="margin-top: 0; color: #856404;">⚠️ Pembayaran Belum Selesai</h3>
            <p style="margin-bottom: 0;">Pesanan Anda dengan kode <strong>{{ $order->order_id }}</strong> masih menunggu pembayaran. Segera selesaikan pembayaran untuk memproses pesanan Anda.</p>
        </div>

        <div class="order-details">
            <h3 style="margin-top: 0; color: #495057;">Detail Pesanan</h3>
            <p><strong>Kode Pesanan:</strong> {{ $order->order_id }}</p>
            <p><strong>Tanggal:</strong> {{ $order->created_at->format('d F Y, H:i') }}</p>
            <p><strong>Status:</strong> <span style="color: #ffc107; font-weight: bold;">PENDING</span></p>
        </div>

        <div class="order-details">
            <h3 style="margin-top: 0; color: #495057;">Produk yang Dipesan</h3>
            @if($orderItems && $orderItems->count() > 0)
                @foreach($orderItems as $item)
                <div class="order-item">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong>{{ $item->product->name }}</strong><br>
                            <small style="color: #6c757d;">Qty: {{ $item->quantity }}</small>
                        </div>
                        <div style="text-align: right;">
                            <strong>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
                @endforeach
            @elseif($order->game)
                <div class="order-item">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong>{{ $order->game->title }}</strong><br>
                            <small style="color: #6c757d;">Qty: 1</small>
                        </div>
                        <div style="text-align: right;">
                            <strong>Rp {{ number_format($order->price ?? $order->total_amount, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="total">
                Total yang Harus Dibayar: Rp {{ number_format($order->total_amount ?? $order->price, 0, ',', '.') }}
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/orders/' . $order->order_id) }}" class="button">
                Bayar Sekarang
            </a>
        </div>

        <div style="background-color: #fff3cd; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #ffeaa7;">
            <h4 style="margin-top: 0; color: #856404;">Cara Menyelesaikan Pembayaran:</h4>
            <ol style="margin: 0; padding-left: 20px;">
                <li>Klik tombol "Bayar Sekarang" di atas</li>
                <li>Pilih metode pembayaran yang Anda inginkan</li>
                <li>Ikuti instruksi pembayaran yang diberikan</li>
                <li>Setelah pembayaran berhasil, Anda akan menerima konfirmasi email</li>
            </ol>
        </div>

        <div style="background-color: #f8d7da; padding: 20px; border-radius: 8px; margin: 20px 0; border: 1px solid #f5c6cb;">
            <h4 style="margin-top: 0; color: #721c24;">⚠️ Penting!</h4>
            <p style="margin-bottom: 0; color: #721c24;">Jika pembayaran tidak diselesaikan dalam 24 jam, pesanan akan otomatis dibatalkan.</p>
        </div>

        <div class="footer">
            <p>Butuh bantuan? Hubungi customer service kami</p>
            <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>