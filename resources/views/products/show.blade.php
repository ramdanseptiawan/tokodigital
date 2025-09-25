<x-layouts.app title="{{ $product->name }} - Digital Store">
    <div class="grid md:grid-cols-2 gap-8">
      <!-- Product Image -->
      <div class="aspect-square bg-slate-200 rounded-2xl overflow-hidden">
        @if($product->cover_image_url)
          <img src="{{ $product->cover_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        @else
          <div class="w-full h-full flex items-center justify-center text-slate-400">
            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
            </svg>
          </div>
        @endif
      </div>
      
      <!-- Product Info -->
      <div>
        <div class="mb-4">
          <span class="inline-block text-sm px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 capitalize mb-2">
            {{ $product->type }}
          </span>
          <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
        </div>
        
        <div class="text-2xl font-bold text-indigo-600 mb-4">
          Rp {{ number_format($product->price, 0, ',', '.') }}
        </div>
        
        @if($product->description)
          <div class="prose prose-slate mb-6">
            <p>{{ $product->description }}</p>
          </div>
        @endif
        
        @if($product->metadata && is_array($product->metadata))
          <div class="bg-slate-50 rounded-lg p-4 mb-6">
            <h3 class="font-semibold mb-2">Detail Produk</h3>
            <div class="space-y-1 text-sm">
              @foreach($product->metadata as $key => $value)
                <div class="flex">
                  <span class="font-medium capitalize w-24">{{ str_replace('_', ' ', $key) }}:</span>
                  <span>{{ $value }}</span>
                </div>
              @endforeach
            </div>
          </div>
        @endif
        
        <!-- Add to Cart -->
        <form method="POST" action="{{ route('cart.add', $product) }}" class="mt-6">
          @csrf
          <div class="flex items-center space-x-4 mb-4">
            <label class="text-sm font-medium text-slate-700">Jumlah:</label>
            <input type="number" name="quantity" value="1" min="1" max="10" 
                   class="w-20 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200">
          </div>
          <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit" 
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg px-6 py-3 transition-colors duration-200 flex items-center justify-center gap-2">
              🛒 Tambah ke Keranjang
            </button>
            <a href="{{ route('products.index') }}" 
               class="px-6 py-3 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 text-center transition-colors duration-200">
              ← Kembali
            </a>
          </div>
        </form>
        
        <!-- Quick Buy -->
        <div class="mt-4 pt-4 border-t">
          <form method="POST" action="{{ route('checkout.product.store', $product) }}">
            @csrf
            <input type="hidden" name="quantity" value="1">
            <button type="submit" 
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg px-6 py-3 transition-colors duration-200 flex items-center justify-center gap-2">
              ⚡ Beli Langsung
            </button>
          </form>
        </div>
      </div>
    </div>
</x-layouts.app>