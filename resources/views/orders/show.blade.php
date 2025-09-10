<x-layouts.app :title="'Bayar Pesanan'">
  <div class="max-w-lg mx-auto bg-white rounded-2xl shadow p-6 text-center">
    <h2 class="text-xl font-semibold">Scan QRIS untuk bayar</h2>
    <p class="text-sm text-slate-600 mt-1">Order ID: {{ $order->order_id }}</p>

    @if($order->qr_url)
      <img src="{{ $order->qr_url }}" alt="QRIS" class="mx-auto my-4 rounded-lg">
    @elseif($order->qr_string)
      {{-- fallback: generate QR sendiri (opsional pakai lib) --}}
      <p class="text-red-600">QR URL tidak tersedia. Hubungi admin.</p>
    @else
      <p>QR belum tersedia.</p>
    @endif

    <div id="status" class="mt-2 text-sm px-3 py-1 rounded-full inline-block bg-yellow-50 text-yellow-700">
      Status: {{ ucfirst($order->status) }}
    </div>

    <div class="mt-4 text-slate-600">
      <p>Setelah dibayar, halaman akan otomatis update.</p>
    </div>
  </div>

  <script>
    const badge = document.getElementById('status');
    const poll = async () => {
      try {
        const r = await fetch('{{ route('orders.status',$order) }}');
        const { status } = await r.json();
        badge.textContent = 'Status: ' + (status || '').charAt(0).toUpperCase() + (status || '').slice(1);
        if (status === 'settlement') {
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
