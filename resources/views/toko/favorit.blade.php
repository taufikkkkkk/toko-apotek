<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obat Favorit — Apotek Sehat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F6F8F9] text-slate-800 min-h-screen antialiased">

    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('toko.index') }}" class="font-bold text-lg text-slate-900">Apotek<span class="text-teal-600">Sehat</span></a>
            <div class="flex items-center gap-4">
                <a href="{{ route('toko.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Katalog</a>
                <a href="{{ route('toko.cart') }}" class="text-sm font-semibold text-teal-700 hover:underline">Keranjang ({{ $cartCount }})</a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Obat Tersimpan</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar produk yang Anda simpan untuk pembelian cepat.</p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($obats as $obat)
                <article class="bg-white rounded-xl border border-slate-200 overflow-hidden flex flex-col">
                    <a href="{{ route('toko.show', $obat->id) }}" class="bg-white h-40 p-4 flex items-center justify-center border-b border-slate-100">
                        @if($obat->gambar)
                            <img src="{{ asset('storage/' . $obat->gambar) }}" class="max-h-full max-w-full object-contain">
                        @else
                            <span class="text-xs text-slate-400">Tanpa foto</span>
                        @endif
                    </a>
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <p class="text-[11px] text-slate-400">{{ $obat->kategori }}</p>
                            <a href="{{ route('toko.show', $obat->id) }}" class="font-semibold text-sm text-slate-900 hover:text-teal-700 capitalize block mt-0.5 line-clamp-2">
                                {{ $obat->nama_obat }}
                            </a>
                        </div>
                        <div class="mt-3 pt-3 border-t border-slate-100 space-y-2">
                            <p class="font-bold text-slate-900">Rp {{ number_format($obat->harga, 0, ',', '.') }}</p>
                            <div class="flex gap-2">
                                <form action="{{ route('toko.add', $obat->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" {{ $obat->stok < 1 ? 'disabled' : '' }}
                                        class="w-full py-1.5 rounded-lg text-xs font-semibold {{ $obat->stok > 0 ? 'bg-teal-600 hover:bg-teal-700 text-white' : 'bg-slate-100 text-slate-400 cursor-not-allowed' }}">
                                        {{ $obat->stok > 0 ? '+ Keranjang' : 'Habis' }}
                                    </button>
                                </form>
                                <form action="{{ route('toko.favorit.toggle', $obat->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg border border-slate-200 hover:bg-red-50 hover:text-red-600 text-xs font-medium text-slate-500">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full bg-white rounded-xl border border-slate-200 p-12 text-center">
                    <p class="font-semibold text-slate-800 text-sm">Belum ada obat yang disimpan</p>
                    <p class="text-xs text-slate-500 mt-1">Klik ikon hati pada produk di katalog untuk menyimpannya di sini.</p>
                    <a href="{{ route('toko.index') }}" class="inline-block mt-4 bg-teal-600 hover:bg-teal-700 text-white px-5 py-2 rounded-lg text-xs font-semibold">
                        Jelajahi Katalog
                    </a>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>