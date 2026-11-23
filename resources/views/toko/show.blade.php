<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucwords($obat->nama_obat) }} — Apotek Sehat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F8FAFC] text-slate-900 min-h-screen antialiased">

    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('toko.index') }}" class="font-bold text-[#0F3D3E] text-base">Apotek Sehat</a>
            <div class="flex items-center gap-4">
                <a href="{{ route('toko.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Katalog</a>
                <a href="{{ route('toko.cart') }}" class="flex items-center gap-2 border border-slate-200 px-3.5 py-1.5 rounded-lg text-sm font-medium text-slate-700">
                    <span>Keranjang</span>
                    <span class="bg-[#0F3D3E] text-white text-xs font-semibold px-1.5 py-0.5 rounded">{{ $cartCount }}</span>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-10">
        <!-- Breadcrumb -->
        <nav class="text-xs text-slate-500 flex items-center gap-2">
            <a href="{{ route('toko.index') }}" class="hover:text-slate-900">Beranda</a>
            <span>/</span>
            <a href="{{ route('toko.index', ['kategori' => $obat->kategori]) }}" class="hover:text-slate-900">{{ $obat->kategori }}</a>
            <span>/</span>
            <span class="text-slate-900 font-medium capitalize">{{ $obat->nama_obat }}</span>
        </nav>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-900 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
        @endif

        <!-- Detail Utama -->
        <section class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            <div class="bg-slate-50 border border-slate-100 rounded-xl h-72 sm:h-96 p-6 flex items-center justify-center">
                @if($obat->gambar)
                    <img src="{{ asset('storage/' . $obat->gambar) }}" alt="{{ $obat->nama_obat }}" class="max-h-full max-w-full object-contain">
                @else
                    <span class="text-sm text-slate-400">Foto belum tersedia</span>
                @endif
            </div>

            <div class="flex flex-col justify-between">
                <div>
                    <span class="text-xs font-medium text-teal-800 bg-teal-50 px-2.5 py-1 rounded">{{ $obat->kategori }}</span>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mt-3 capitalize">{{ $obat->nama_obat }}</h1>

                    <!-- Rating Ringkas -->
                    <div class="flex items-center gap-2 mt-2 text-xs text-slate-500">
                        <span class="font-bold text-amber-500 text-sm">★ {{ number_format($avgRating, 1) }}</span>
                        <span>({{ $obat->ulasans->count() }} ulasan)</span>
                        <span>•</span>
                        <span class="{{ $obat->stok > 0 ? 'text-emerald-700 font-medium' : 'text-red-600 font-medium' }}">
                            {{ $obat->stok > 0 ? 'Tersedia ' . $obat->stok . ' unit' : 'Stok Habis' }}
                        </span>
                    </div>

                    <p class="text-2xl font-bold text-[#0F3D3E] mt-5">Rp {{ number_format($obat->harga, 0, ',', '.') }}</p>

                    <div class="mt-5 pt-5 border-t border-slate-100 space-y-3 text-sm text-slate-600">
                        <div>
                            <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Deskripsi & Kegunaan</h2>
                            <p class="leading-relaxed">{{ $obat->deskripsi ?? 'Produk kesehatan standar apotek.' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 pt-2">
                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                <span class="text-xs text-slate-400 block">Tanggal Kadaluarsa</span>
                                <span class="font-semibold text-slate-800">{{ $obat->tanggal_kadaluarsa ?? '-' }}</span>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                                <span class="text-xs text-slate-400 block">Kategori</span>
                                <span class="font-semibold text-slate-800">{{ $obat->kategori }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Tambah Keranjang -->
                <form action="{{ route('toko.add', $obat->id) }}" method="POST" class="mt-6 pt-6 border-t border-slate-100 flex items-center gap-3">
                    @csrf
                    <div class="w-24">
                        <input type="number" name="qty" value="1" min="1" max="{{ max(1, $obat->stok) }}" {{ $obat->stok < 1 ? 'disabled' : '' }}
                            class="w-full border border-slate-300 rounded-lg px-3 py-2.5 text-sm text-center font-semibold focus:outline-none focus:border-[#0F3D3E]">
                    </div>
                    <button type="submit" {{ $obat->stok < 1 ? 'disabled' : '' }}
                        class="flex-1 py-2.5 rounded-lg text-sm font-semibold transition {{ $obat->stok > 0 ? 'bg-[#0F3D3E] hover:bg-[#0b2f30] text-white' : 'bg-slate-200 text-slate-400 cursor-not-allowed' }}">
                        {{ $obat->stok > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                    </button>
                </form>
            </div>
        </section>

        <!-- Ulasan Pembeli -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <!-- Form Tulis Ulasan -->
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="font-bold text-slate-900 text-base mb-4">Tulis Ulasan</h2>
                <form action="{{ route('toko.ulasan.store', $obat->id) }}" method="POST" class="space-y-3.5">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Nama Anda</label>
                        <input type="text" name="nama" required placeholder="Nama lengkap" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#0F3D3E]">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Rating</label>
                        <select name="rating" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-[#0F3D3E]">
                            <option value="5">5 - Sangat Bagus</option>
                            <option value="4">4 - Bagus</option>
                            <option value="3">3 - Cukup</option>
                            <option value="2">2 - Kurang</option>
                            <option value="1">1 - Buruk</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">Komentar</label>
                        <textarea name="komentar" rows="3" required placeholder="Bagikan pengalaman Anda menggunakan produk ini..." class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-[#0F3D3E]"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white py-2 rounded-lg text-sm font-semibold transition">
                        Kirim Ulasan
                    </button>
                </form>
            </div>

            <!-- Daftar Ulasan -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-6">
                <h2 class="font-bold text-slate-900 text-base mb-4">Ulasan Pembeli ({{ $obat->ulasans->count() }})</h2>
                <div class="divide-y divide-slate-100">
                    @forelse($obat->ulasans as $ulasan)
                        <div class="py-4 first:pt-0 last:pb-0">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-sm text-slate-900">{{ $ulasan->nama }}</span>
                                <span class="text-xs text-slate-400">{{ $ulasan->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="text-amber-500 text-xs mt-1">
                                {{ str_repeat('★', $ulasan->rating) }}{{ str_repeat('☆', 5 - $ulasan->rating) }}
                            </div>
                            <p class="text-sm text-slate-600 mt-2 leading-relaxed">{{ $ulasan->komentar }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 py-6 text-center">Belum ada ulasan untuk produk ini. Jadilah yang pertama mengulas.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Produk Terkait -->
        @if($terkait->count() > 0)
            <section>
                <h2 class="font-bold text-slate-900 text-lg mb-4">Produk Sejenis</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($terkait as $item)
                        <a href="{{ route('toko.show', $item->id) }}" class="bg-white rounded-xl border border-slate-200 hover:border-slate-300 p-4 transition block">
                            <div class="h-32 flex items-center justify-center mb-3">
                                @if($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" class="max-h-full max-w-full object-contain">
                                @else
                                    <span class="text-xs text-slate-400">No Img</span>
                                @endif
                            </div>
                            <h3 class="font-semibold text-sm text-slate-900 capitalize line-clamp-1">{{ $item->nama_obat }}</h3>
                            <p class="font-bold text-[#0F3D3E] text-sm mt-1">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </main>
</body>
</html>