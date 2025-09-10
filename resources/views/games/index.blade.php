<x-layouts.app :title="'Daftar Game'">
  <h1 class="text-2xl font-semibold mb-4">Pilih Game</h1>
  <div class="grid md:grid-cols-3 gap-4">
    @foreach($games as $g)
    <a href="{{ route('games.show',$g) }}" class="bg-white rounded-2xl shadow hover:shadow-md p-4 flex flex-col">
      <div class="aspect-video bg-slate-200 rounded-xl mb-3"></div>
      <div class="font-semibold">{{ $g->title }}</div>
      <div class="text-sm text-slate-600 mt-1">Rp {{ number_format($g->price,0,',','.') }}</div>
      <div class="mt-3">
        <span class="inline-block text-sm px-3 py-1 rounded-full bg-indigo-50 text-indigo-600">Beli</span>
      </div>
    </a>
    @endforeach
  </div>
</x-layouts.app>