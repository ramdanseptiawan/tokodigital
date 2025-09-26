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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-8">
      @foreach($products as $product)
        <div class="group bg-white rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden border border-slate-100 hover:border-indigo-200 hover:-translate-y-2">
          <!-- Product Image with Overlay -->
          <div class="relative aspect-square bg-gradient-to-br from-slate-100 to-slate-200 overflow-hidden">
            @if($product->cover_image_url)
              <img src="{{ $product->cover_image_url }}" alt="{{ $product->name }}" 
                   class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            @else
              <div class="w-full h-full flex items-center justify-center text-slate-400 bg-gradient-to-br from-slate-50 to-slate-100">
                <div class="text-center">
                  <svg class="w-20 h-20 mx-auto mb-2 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                  </svg>
                  <p class="text-xs font-medium">No Image</p>
                </div>
              </div>
            @endif
            
            <!-- Category Badge -->
            <div class="absolute top-4 left-4">
              <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-white/90 backdrop-blur-sm text-slate-700 shadow-sm border border-white/20">
                @switch($product->type)
                  @case('game')
                    🎮 Game
                    @break
                  @case('ebook')
                    📚 E-book
                    @break
                  @case('workflow')
                    ⚡ Workflow
                    @break
                  @case('module')
                    🧩 Module
                    @break
                  @default
                    📦 {{ ucfirst($product->type) }}
                @endswitch
              </span>
            </div>

            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>

          <!-- Product Content -->
          <div class="p-6">
            <!-- Product Title -->
            <h3 class="font-bold text-xl mb-3 line-clamp-2 text-slate-900 group-hover:text-indigo-600 transition-colors duration-300 leading-tight">
              {{ $product->name }}
            </h3>
            
            <!-- Product Description -->
            <p class="text-sm text-slate-600 mb-4 line-clamp-3 leading-relaxed">
              {{ $product->description }}
            </p>
            
            <!-- Price Section -->
            <div class="mb-6">
              <div class="flex items-baseline gap-2">
                <span class="text-2xl font-bold text-indigo-600">
                  Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
                <span class="text-sm text-slate-500 line-through opacity-0">
                  <!-- Space for discount price if needed -->
                </span>
              </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex gap-3">
              <a href="{{ route('products.show', $product) }}" 
                 class="flex-1 text-center bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold rounded-xl px-4 py-3 text-sm transition-all duration-200 border border-slate-200 hover:border-slate-300 hover:shadow-sm">
                <span class="flex items-center justify-center gap-2">
                  Detail
                </span>
              </a>
              <form method="POST" action="{{ route('cart.add', $product) }}" class="flex-1">
                @csrf
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl px-4 py-3 text-sm transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                  <span class="flex items-center justify-center gap-2">
                     Cart
                   </span>
                </button>
              </form>
            </div>
          </div>

          <!-- Bottom Accent Line -->
          <div class="h-1 bg-gradient-to-r from-indigo-500 to-purple-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
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