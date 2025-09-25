<x-layouts.app title="Keranjang - Digital Store">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-semibold">Keranjang Belanja</h1>
      @if($cartItems->count() > 0)
        <span class="bg-indigo-100 text-indigo-800 text-sm font-medium px-3 py-1 rounded-full">
          {{ $cartItems->sum('quantity') }} item{{ $cartItems->sum('quantity') > 1 ? 's' : '' }}
        </span>
      @endif
    </div>
    
    @if(session('success'))
      <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
        {{ session('success') }}
      </div>
    @endif

    @if($cartItems->count() > 0)
      <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="divide-y">
          @foreach($cartItems as $item)
            <div class="p-4 sm:p-6">
              <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-200 rounded-lg flex-shrink-0 mx-auto sm:mx-0"></div>
                <div class="flex-1 text-center sm:text-left">
                  <h3 class="font-semibold text-lg">{{ $item->product->name }}</h3>
                  <p class="text-sm text-slate-600 capitalize">{{ $item->product->type }}</p>
                  <p class="text-lg font-medium text-indigo-600">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                </div>
                <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-3">
                  <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center space-x-2">
                    @csrf
                    @method('PATCH')
                    <label class="text-sm font-medium text-slate-700 sm:hidden">Qty:</label>
                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10" 
                           class="w-16 px-2 py-1 border rounded text-center transition-colors duration-200 focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="text-sm bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700 transition-colors duration-200">
                      ✓
                    </button>
                  </form>
                  <form method="POST" action="{{ route('cart.remove', $item) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 p-1 transition-colors duration-200" 
                            onclick="return confirm('Hapus item ini?')">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>
                  </form>
                </div>
                <div class="text-center sm:text-right">
                  <p class="font-semibold text-lg">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                  <p class="text-xs text-slate-500">{{ $item->quantity }} × Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        
        <div class="bg-slate-50 px-4 sm:px-6 py-4">
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-2 sm:space-y-0">
            <span class="text-lg font-semibold text-center sm:text-left">Total Belanja:</span>
            <span class="text-2xl font-bold text-indigo-600 text-center sm:text-right">Rp {{ number_format($total, 0, ',', '.') }}</span>
          </div>
          <div class="flex flex-col sm:flex-row gap-3">
            <form method="POST" action="{{ route('cart.clear') }}" class="inline">
              @csrf
              @method('DELETE')
              <button type="submit" class="w-full sm:w-auto px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-colors duration-200"
                      onclick="return confirm('Kosongkan keranjang?')">
                🗑️ Kosongkan Keranjang
              </button>
            </form>
            <a href="{{ route('checkout.cart') }}" 
               class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center font-semibold rounded-lg px-6 py-3 transition-colors duration-200 flex items-center justify-center gap-2">
              💳 Checkout Sekarang
            </a>
          </div>
        </div>
      </div>
    @else
      <div class="text-center py-12">
        <div class="text-6xl mb-4">🛒</div>
        <h2 class="text-xl font-semibold mb-2">Keranjang Kosong</h2>
        <p class="text-slate-600 mb-6">Belum ada produk di keranjang Anda</p>
        <a href="{{ route('products.index') }}" 
           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg px-6 py-2">
          Mulai Belanja
        </a>
      </div>
    @endif
</x-layouts.app>