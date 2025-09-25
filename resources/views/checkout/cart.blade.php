<x-layouts.app title="Checkout - Digital Store">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Checkout</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>
                
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                            <p class="text-sm text-gray-600">{{ ucfirst($item->product->type) }}</p>
                            <p class="text-sm text-gray-600">Qty: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-gray-900">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                            <p class="text-sm text-gray-600">@ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="border-t pt-4 mt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">Total:</span>
                        <span class="text-xl font-bold text-blue-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informasi Pembeli</h2>
                
                <form action="{{ route('checkout.cart.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon (Opsional)</label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 font-medium">
                            Bayar via QRIS - Rp {{ number_format($total, 0, ',', '.') }}
                        </button>
                    </div>
                </form>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('cart.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        ← Kembali ke Keranjang
                    </a>
                </div>
            </div>
            

        </div>
    </div>
</div>



</x-layouts.app>