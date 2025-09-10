<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $title ?? 'QRIS Game Store' }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800">
  <nav class="bg-white shadow sticky top-0 z-10">
    <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
      <a href="{{ route('games.index') }}" class="font-bold text-xl">🎮 Game Store</a>
    </div>
  </nav>
  <main class="max-w-5xl mx-auto px-4 py-6">
    {{ $slot }}
  </main>
</body>
</html>
