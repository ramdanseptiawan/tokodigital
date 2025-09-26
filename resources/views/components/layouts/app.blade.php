<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title ?? 'Digital Store' }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
  <nav class="bg-white shadow sticky top-0 z-10">
    <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
      <a href="{{ route('products.index') }}" class="font-bold text-lg sm:text-xl">🛒 Digital Store</a>
      <div class="flex items-center space-x-3 sm:space-x-4">
        <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-900 text-sm sm:text-base transition-colors duration-200">
          <span class="hidden sm:inline">Products</span>
          <span class="sm:hidden">📦</span>
        </a>
        <a href="{{ route('cart.index') }}" class="text-gray-600 hover:text-gray-900 relative text-sm sm:text-base transition-colors duration-200">
          <span class="hidden sm:inline">Cart 🛒</span>
          <span class="sm:hidden">🛒</span>
          <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden"></span>
        </a>
      </div>
    </div>
  </nav>
  <main class="max-w-5xl mx-auto px-4 py-6">
    {{ $slot }}
  </main>
  
  <script>
    // Update cart count on page load
    function updateCartCount() {
      fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
          const cartCount = document.getElementById('cart-count');
          if (data.count > 0) {
            cartCount.textContent = data.count;
            cartCount.classList.remove('hidden');
          } else {
            cartCount.classList.add('hidden');
          }
        })
        .catch(error => console.log('Cart count error:', error));
    }
    
    // Update cart count when page loads
    document.addEventListener('DOMContentLoaded', updateCartCount);
    
    // Handle add to cart forms with AJAX for better UX
    document.addEventListener('submit', function(e) {
      if (e.target.matches('form[action*="cart/add"]')) {
        e.preventDefault();
        
        const form = e.target;
        const button = form.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '⏳ Menambahkan...';
        button.disabled = true;
        
        fetch(form.action, {
          method: 'POST',
          body: new FormData(form),
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            button.innerHTML = '✅ Ditambahkan!';
            updateCartCount();
            setTimeout(() => {
              button.innerHTML = originalText;
              button.disabled = false;
            }, 1500);
          } else {
            throw new Error(data.message || 'Gagal menambahkan ke keranjang');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          button.innerHTML = '❌ Gagal';
          setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
          }, 1500);
        });
      }
    });
  </script>
</body>
</html>
