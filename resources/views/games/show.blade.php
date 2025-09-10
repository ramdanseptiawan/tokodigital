<x-layouts.app :title="$game->title">
  <div class="grid md:grid-cols-2 gap-8">
    <div class="aspect-video bg-slate-200 rounded-2xl"></div>
    <div>
      <h1 class="text-3xl font-bold">{{ $game->title }}</h1>
      <div class="text-xl mt-2">Rp {{ number_format($game->price,0,',','.') }}</div>
      <form method="post" action="{{ route('checkout.store',$game) }}" class="mt-6 space-y-3">
        @csrf
        <input name="name" placeholder="Nama" class="w-full border rounded-xl px-4 py-2" required>
        <input type="email" name="email" placeholder="Email" class="w-full border rounded-xl px-4 py-2" required>
        <input name="phone" placeholder="No. HP (opsional)" class="w-full border rounded-xl px-4 py-2">
        <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl px-4 py-2">
          Bayar via QRIS
        </button>
      </form>
    </div>
  </div>
</x-layouts.app>
