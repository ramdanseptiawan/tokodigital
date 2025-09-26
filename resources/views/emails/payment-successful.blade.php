<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
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
        .success-icon {
            font-size: 48px;
            color: #28a745;
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
            color: #28a745;
            text-align: right;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #28a745;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
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
            <div class="success-icon">✅</div>
            <h1 style="color: #28a745; margin: 0;">Selamat! Pembayaran Berhasil</h1>
            <p style="color: #6c757d; margin: 10px 0 0 0;">Terima kasih atas pembelian Anda</p>
        </div>

        <div class="order-details">
            <h3 style="margin-top: 0; color: #495057;">Detail Pesanan</h3>
            <p><strong>Kode Pesanan:</strong> {{ $order->order_id }}</p>
            <p><strong>Tanggal:</strong> {{ $order->created_at->format('d F Y, H:i') }}</p>
            <p><strong>Status:</strong> <span style="color: #28a745; font-weight: bold;">PAID</span></p>
        </div>

        <div class="order-details">
            <h3 style="margin-top: 0; color: #495057;">Produk yang Dibeli</h3>
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
            
            <div class="total">
                Total Pembayaran: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ url('/orders/' . $order->order_id) }}" class="button">
                Lihat Detail Pesanan
            </a>
        </div>

        <div style="background-color: #e7f3ff; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #0056b3;">Informasi Penting:</h4>
            <ul style="margin: 0; padding-left: 20px;">
                <li>Simpan email ini sebagai bukti pembayaran</li>
                <li>Kode pesanan: <strong>{{ $order->order_id }}</strong></li>
                <li>Jika ada pertanyaan, hubungi customer service kami</li>
            </ul>
        </div>

        <div class="footer">
            <p>Email ini dikirim otomatis, mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>