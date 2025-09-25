<x-layouts.app title="Products - Digital Store">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-2">Semua Produk</h1>
    <p class="text-slate-600">Temukan berbagai produk digital terbaik untuk kebutuhan Anda</p>
    
    <!-- Filter dan Search -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
      <form method="GET" class="flex flex-col lg:flex-row gap-4 lg:items-end">
        <div class="flex-1 lg:max-w-md">
          <label class="block text-sm font-medium text-slate-700 mb-2">Cari Produk</label>
          <input type="text" name="search" value="{{ request('search') }}" 
                 placeholder="Nama produk..." 
                 class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
        </div>
        <div class="lg:min-w-48">
          <label class="block text-sm font-medium text-slate-700 mb-2">Kategori</label>
          <select name="type" class="w-full border border-slate-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
            <option value="">Semua Kategori</option>
            @foreach($types as $type)
              <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                {{ ucfirst($type) }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="flex gap-3">
          <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
            🔍 Filter
          </button>
          <a href="{{ route('products.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-6 py-2.5 rounded-lg font-medium transition-colors duration-200 flex items-center gap-2">
            🔄 Reset
          </a>
        </div>
      </form>
    </div>
  </div>

  @if($products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      @foreach($products as $product)
        <div class="bg-white rounded-2xl shadow hover:shadow-lg transition-all duration-300 overflow-hidden">
          <div class="aspect-square bg-slate-200 rounded-t-2xl overflow-hidden">
            @if($product->cover_image_url)
              <img src="{{ $product->cover_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
              <div class="w-full h-full flex items-center justify-center text-slate-400">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                </svg>
              </div>
            @endif
          </div>
          <div class="p-5">
            <div class="flex items-start justify-between mb-3">
              <span class="inline-block text-xs px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-600 capitalize font-medium">
                {{ $product->type }}
              </span>
            </div>
            <h3 class="font-semibold text-lg mb-2 line-clamp-2 text-slate-800">{{ $product->name }}</h3>
            <p class="text-sm text-slate-600 mb-4 line-clamp-2 leading-relaxed">{{ $product->description }}</p>
            <div class="flex items-center justify-between mb-4">
              <span class="text-xl font-bold text-indigo-600">
                Rp {{ number_format($product->price, 0, ',', '.') }}
              </span>
            </div>
            <div class="flex gap-2">
              <a href="{{ route('products.show', $product) }}" 
                 class="flex-1 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg px-4 py-2.5 text-sm transition-colors duration-200">
                Detail
              </a>
              <form method="POST" action="{{ route('cart.add', $product) }}" class="flex-1">
                @csrf
                <button type="submit" 
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg px-4 py-2.5 text-sm transition-colors duration-200 flex items-center justify-center gap-1">
                  Keranjang
                </button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
      <div class="mt-12 flex justify-center">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
          {{ $products->appends(request()->query())->links() }}
        </div>
      </div>
    @endif
  @else
    <div class="text-center py-16">
      <div class="text-8xl mb-6">📦</div>
      <h2 class="text-2xl font-bold text-slate-900 mb-3">Tidak Ada Produk</h2>
      <p class="text-slate-600 mb-6 max-w-md mx-auto">Tidak ada produk yang sesuai dengan pencarian Anda. Coba ubah filter atau kata kunci pencarian.</p>
      <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-3 rounded-lg transition-colors duration-200">
        🔄 Lihat Semua Produk
      </a>
    </div>
  @endif
</div>
</x-layouts.app>