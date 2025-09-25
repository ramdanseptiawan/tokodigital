@php
    use Illuminate\Support\Str;
@endphp

<x-layouts.app :title="'Bayar Pesanan'">
  <div class="max-w-lg mx-auto bg-white rounded-2xl shadow p-6 text-center">
    <h2 class="text-xl font-semibold">Scan QRIS untuk bayar</h2>
    <p class="text-sm text-slate-600 mt-1">Order ID: {{ $order->order_id }}</p>

    @if($order->qr_url)
      <img src="{{ $order->qr_url }}" alt="QRIS" class="mx-auto my-4 rounded-lg max-w-xs">
    @elseif($order->qr_string)
      {{-- fallback: generate QR sendiri (opsional pakai lib) --}}
      <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 my-4">
        <p class="text-yellow-800 font-medium">⚠️ QR URL tidak tersedia</p>
        <p class="text-yellow-700 text-sm mt-1">QR String: {{ str($order->qr_string)->limit(50) }}</p>
        <p class="text-yellow-700 text-sm">Silakan hubungi admin untuk bantuan.</p>
      </div>
    @else
      <div class="bg-red-50 border border-red-200 rounded-lg p-4 my-4">
        <p class="text-red-800 font-medium">❌ QRIS belum tersedia</p>
        <p class="text-red-700 text-sm mt-1">Kemungkinan masalah:</p>
        <ul class="text-red-700 text-sm mt-1 list-disc list-inside">
          <li>Konfigurasi Midtrans belum lengkap</li>
          <li>Server Key atau Client Key tidak valid</li>
          <li>Koneksi ke Midtrans bermasalah</li>
        </ul>
        <p class="text-red-700 text-sm mt-2">Silakan periksa konfigurasi environment atau hubungi admin.</p>
      </div>
    @endif

    <div id="status" class="mt-2 text-sm px-3 py-1 rounded-full inline-block bg-yellow-50 text-yellow-700">
      Status: {{ ucfirst($order->status) }}
    </div>

    <div class="mt-4 text-slate-600">
      <p>Setelah dibayar, halaman akan otomatis update.</p>
    </div>
  </div>

  <!-- Order Items Section -->
  <div class="max-w-lg mx-auto bg-white rounded-2xl shadow p-6 mt-6">
    <h3 class="text-lg font-semibold mb-4">Detail Pesanan</h3>
    
    <div class="space-y-3">
      @foreach($order->orderItems as $item)
        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
          <div class="flex-1">
            <h4 class="font-medium text-gray-900">{{ $item->product->name }}</h4>
            <p class="text-sm text-gray-600">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
          </div>
          <div class="text-right">
            <p class="font-medium text-gray-900">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
          </div>
        </div>
      @endforeach
    </div>
    
    <div class="border-t border-gray-200 pt-3 mt-3">
      <div class="flex justify-between items-center">
        <span class="text-lg font-semibold text-gray-900">Total:</span>
        <span class="text-lg font-bold text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
      </div>
    </div>
    
    <div class="mt-4 text-sm text-gray-600">
      <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
      <p><strong>Email:</strong> {{ $order->customer_email }}</p>
      @if($order->customer_phone)
        <p><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
      @endif
    </div>
  </div>

  <script>
    const badge = document.getElementById('status');
    const poll = async () => {
      try {
        const r = await fetch('{{ route("orders.status", $order) }}');
        const { status } = await r.json();
        badge.textContent = 'Status: ' + (status || '').charAt(0).toUpperCase() + (status || '').slice(1);
        if (status === 'paid') {
          badge.className = 'mt-2 text-sm px-3 py-1 rounded-full inline-block bg-green-50 text-green-700';
          alert('Pembayaran sukses! Terima kasih 🙌');
          clearInterval(tmr);
        } else if (status === 'expire' || status === 'cancel') {
          badge.className = 'mt-2 text-sm px-3 py-1 rounded-full inline-block bg-red-50 text-red-700';
          clearInterval(tmr);
        }
      } catch(e) { /* ignore */ }
    };
    const tmr = setInterval(poll, 5000);
  </script>
</x-layouts.app>
