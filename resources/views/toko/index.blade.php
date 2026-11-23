<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Apotek Sehat — Beli Obat & Vitamin Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#F6F8F9] text-slate-800 flex flex-col min-h-screen antialiased">

    <!-- Clean E-Commerce Header -->
    <header class="sticky top-0 z-40 bg-white border-b border-slate-200/90">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
            <!-- Brand -->
            <a href="{{ route('toko.index') }}" class="flex items-center gap-2.5 shrink-0">
                <div class="w-8 h-8 rounded-lg bg-teal-600 text-white flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="font-bold text-lg tracking-tight text-slate-900">Apotek<span class="text-teal-600">Sehat</span></span>
            </a>

            <!-- Real Search Bar with Live Dropdown -->
            <form action="{{ route('toko.index') }}" method="GET" class="flex-1 max-w-xl hidden md:block relative">
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <div class="relative">
                    <input id="searchInput" type="text" name="q" value="{{ request('q') }}" autocomplete="off"
                        placeholder="Cari paracetamol, vitamin C, obat batuk..."
                        class="w-full bg-slate-100 hover:bg-slate-200/60 focus:bg-white border border-slate-200 focus:border-teal-600 rounded-lg pl-10 pr-4 py-2 text-sm text-slate-800 placeholder-slate-400 focus:outline-none transition">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z"/>
                    </svg>
                </div>
                <div id="liveSearchBox" class="hidden absolute left-0 right-0 top-11 bg-white rounded-lg border border-slate-200 shadow-lg overflow-hidden z-50 divide-y divide-slate-100"></div>
            </form>

            <!-- Navigation, Favorit & Cart -->
            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                <a href="{{ route('toko.resep') }}" class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 transition">
                    Upload Resep
                </a>
                <a href="{{ route('toko.lacak') }}" class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition">
                    Lacak Pesanan
                </a>

                <div class="h-5 w-px bg-slate-200 mx-1 hidden sm:block"></div>

                <!-- Favorit Button -->
                <a href="{{ route('toko.favorit') }}" class="relative inline-flex items-center justify-center w-9 h-9 rounded-lg hover:bg-slate-100 text-slate-700 transition" title="Obat Tersimpan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                    </svg>
                    <span id="favBadge" class="absolute -top-0.5 -right-0.5 bg-rose-500 text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full min-w-[17px] text-center {{ count($favoritIds) > 0 ? '' : 'hidden' }}">
                        {{ count($favoritIds) }}
                    </span>
                </a>

                <!-- Cart Button -->
                <button type="button" onclick="toggleCartDrawer()" class="relative inline-flex items-center justify-center w-9 h-9 rounded-lg hover:bg-slate-100 text-slate-700 transition" title="Keranjang">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                    </svg>
                    <span id="cartBadge" class="absolute -top-0.5 -right-0.5 bg-teal-600 text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full min-w-[17px] text-center {{ $cartCount > 0 ? '' : 'hidden' }}">
                        {{ $cartCount }}
                    </span>
                </button>

                <!-- Admin Link -->
                <a href="{{ auth()->check() ? route('obat.index') : route('login') }}"
                   class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg border border-slate-200 hover:border-slate-300 text-xs font-medium text-slate-700 transition">
                    {{ auth()->check() ? 'Admin' : 'Masuk' }}
                </a>
            </div>
        </div>

        <!-- Mobile Search -->
        <div class="md:hidden px-4 pb-3">
            <form action="{{ route('toko.index') }}" method="GET">
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari obat atau vitamin..."
                    class="w-full bg-slate-100 border border-slate-200 rounded-lg px-3.5 py-2 text-sm focus:bg-white focus:outline-none focus:border-teal-600">
            </form>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 flex-1 w-full">

        <!-- Light & Natural Hero Section -->
        @if(!request('q') && !request('kategori'))
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-4 mb-8">
            <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 overflow-hidden grid grid-cols-1 sm:grid-cols-12 items-center">
                <div class="sm:col-span-7 p-6 sm:p-8">
                    <span class="inline-block text-[11px] font-semibold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-md mb-3">
                        Buka Setiap Hari • 08.00 – 22.00 WIB
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight leading-snug">
                        Beli obat asli & vitamin, langsung diantar dari apotek.
                    </h1>
                    <p class="text-sm text-slate-500 mt-2.5 leading-relaxed">
                        Stok selalu diperbarui setiap hari. Tersedia layanan tebus resep dokter dan konsultasi apoteker gratis.
                    </p>
                    <div class="flex flex-wrap gap-2.5 mt-5">
                        <a href="#katalog" class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                            Lihat Produk
                        </a>
                        <a href="{{ route('toko.resep') }}" class="bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-sm font-semibold px-5 py-2.5 rounded-lg transition">
                            Tebus Resep
                        </a>
                    </div>
                </div>
                <div class="sm:col-span-5 h-56 sm:h-full bg-slate-100">
                    <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&w=700&q=80"
                         alt="Dokter Apotek Sehat"
                         class="w-full h-full object-cover object-top">
                </div>
            </div>

            <div class="lg:col-span-4 flex flex-col gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <h2 class="font-bold text-sm text-slate-900">Lacak Pesanan</h2>
                        <p class="text-xs text-slate-500 mt-1">Cek status pengiriman menggunakan kode invoice Anda.</p>
                    </div>
                    <form action="{{ route('toko.lacak') }}" method="GET" class="mt-3 flex gap-2">
                        <input type="text" name="kode" required placeholder="Contoh: APT-2026..."
                            class="flex-1 bg-slate-50 border border-slate-200 focus:bg-white focus:border-teal-600 rounded-lg px-3 py-2 text-xs focus:outline-none">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-3.5 py-2 rounded-lg text-xs font-semibold transition">
                            Cek
                        </button>
                    </form>
                </div>

                <a href="https://wa.me/6281234567890?text=Halo%20Apoteker,%20saya%20ingin%20tanya%20obat" target="_blank"
                   class="bg-teal-600 hover:bg-teal-700 text-white rounded-2xl p-5 flex items-center justify-between gap-3 transition">
                    <div>
                        <p class="font-bold text-sm">Butuh rekomendasi obat?</p>
                        <p class="text-xs text-teal-100 mt-0.5">Chat langsung dengan apoteker kami</p>
                    </div>
                    <span class="bg-white/20 text-white text-xs font-semibold px-3 py-1.5 rounded-lg shrink-0">
                        Chat WA
                    </span>
                </a>
            </div>
        </section>

        <!-- Quick Keluhan Strip -->
        <section class="mb-8">
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                <span class="text-xs font-semibold text-slate-500 shrink-0 mr-1">Cari keluhan:</span>
                @foreach(['Demam', 'Batuk', 'Pilek', 'Vitamin', 'Maag', 'Alergi', 'Pusing'] as $keluhan)
                    <a href="{{ route('toko.index', ['q' => $keluhan]) }}#katalog"
                       class="px-3.5 py-1.5 bg-white border border-slate-200 hover:border-teal-600 hover:text-teal-700 rounded-full text-xs font-medium text-slate-600 whitespace-nowrap transition">
                        {{ $keluhan }}
                    </a>
                @endforeach
            </div>
        </section>
        @endif

        <!-- Category Tabs & Sort -->
        <div id="katalog" class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1 sm:pb-0">
                @php
                    $kategoris = ['Obat Bebas', 'Obat Keras', 'Vitamin & Suplemen', 'Sirup'];
                    $activeKat = request('kategori');
                @endphp
                <a href="{{ route('toko.index', ['q' => request('q'), 'sort' => request('sort')]) }}"
                   class="px-3.5 py-1.5 rounded-lg text-xs sm:text-sm font-semibold whitespace-nowrap transition {{ !$activeKat ? 'bg-teal-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    Semua
                </a>
                @foreach($kategoris as $kat)
                    <a href="{{ route('toko.index', ['kategori' => $kat, 'q' => request('q'), 'sort' => request('sort')]) }}"
                       class="px-3.5 py-1.5 rounded-lg text-xs sm:text-sm font-semibold whitespace-nowrap transition {{ $activeKat == $kat ? 'bg-teal-600 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                        {{ $kat }}
                    </a>
                @endforeach
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <form action="{{ route('toko.index') }}" method="GET">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    <select name="sort" onchange="this.form.submit()" class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-medium text-slate-700 focus:outline-none focus:border-teal-600">
                        <option value="">Urutkan: Terbaru</option>
                        <option value="termurah" {{ request('sort') === 'termurah' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="termahal" {{ request('sort') === 'termahal' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </form>
                <span class="text-xs text-slate-500">{{ $obats->count() }} produk</span>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">
            @forelse($obats as $obat)
                @php $isFav = in_array($obat->id, $favoritIds); @endphp
                <article class="bg-white rounded-xl border border-slate-200/90 hover:border-teal-500/60 hover:shadow-md transition flex flex-col overflow-hidden relative">
                    <!-- Tombol Favorit -->
                    <button type="button" onclick="toggleFavorit(this, '{{ route('toko.favorit.toggle', $obat->id) }}')"
                        class="absolute top-2.5 right-2.5 z-10 w-8 h-8 rounded-full bg-white/90 border border-slate-200/80 flex items-center justify-center transition hover:scale-105 {{ $isFav ? 'text-rose-500' : 'text-slate-400 hover:text-rose-500' }}"
                        title="Simpan ke Favorit">
                        <svg class="w-4 h-4" fill="{{ $isFav ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                        </svg>
                    </button>

                    <a href="{{ route('toko.show', $obat->id) }}" class="relative bg-white h-40 p-4 flex items-center justify-center border-b border-slate-100">
                        @if($obat->gambar)
                            <img src="{{ asset('storage/' . $obat->gambar) }}" alt="{{ $obat->nama_obat }}"
                                 class="max-h-full max-w-full object-contain">
                        @else
                            <span class="text-xs text-slate-400">Tanpa foto</span>
                        @endif

                        @if($obat->stok < 1)
                            <span class="absolute inset-0 bg-white/80 flex items-center justify-center text-xs font-bold text-slate-600">
                                Stok Habis
                            </span>
                        @endif
                    </a>

                    <div class="p-3.5 flex-1 flex flex-col justify-between">
                        <div>
                            <p class="text-[11px] text-slate-400 font-medium">{{ $obat->kategori }}</p>
                            <a href="{{ route('toko.show', $obat->id) }}" class="block font-semibold text-slate-900 hover:text-teal-700 text-sm mt-0.5 capitalize line-clamp-2 leading-snug">
                                {{ $obat->nama_obat }}
                            </a>
                            @if($obat->ulasans_count > 0)
                                <div class="flex items-center gap-1 mt-1 text-[11px] text-slate-500">
                                    <span class="text-amber-500">★</span>
                                    <span class="font-semibold text-slate-700">{{ number_format($obat->ulasans_avg_rating, 1) }}</span>
                                    <span>({{ $obat->ulasans_count }})</span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-3 pt-2.5 border-t border-slate-100">
                            <p class="font-bold text-slate-900 text-sm sm:text-base">
                                Rp {{ number_format($obat->harga, 0, ',', '.') }}
                            </p>
                            <p class="text-[11px] {{ $obat->stok > 0 ? 'text-slate-400' : 'text-red-500' }} mb-2.5">
                                Stok {{ $obat->stok }}
                            </p>

                            <form action="{{ route('toko.add', $obat->id) }}" method="POST" class="ajax-cart-form">
                                @csrf
                                <button type="submit" {{ $obat->stok < 1 ? 'disabled' : '' }}
                                    class="w-full py-1.5 rounded-lg text-xs font-semibold transition {{ $obat->stok > 0 ? 'bg-teal-600 hover:bg-teal-700 text-white' : 'bg-slate-100 text-slate-400 cursor-not-allowed' }}">
                                    {{ $obat->stok > 0 ? '+ Keranjang' : 'Habis' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full bg-white rounded-xl border border-slate-200 p-10 text-center">
                    <p class="font-semibold text-slate-800 text-sm">Produk tidak ditemukan</p>
                    <p class="text-xs text-slate-500 mt-1">Coba kata kunci lain atau lihat semua produk.</p>
                    <a href="{{ route('toko.index') }}" class="inline-block mt-3 text-xs font-semibold text-teal-700 underline">Reset pencarian</a>
                </div>
            @endforelse
        </div>

        <!-- Apoteker & FAQ -->
        @if(!request('q') && !request('kategori'))
        <section class="mt-12 grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-6">
                <h2 class="font-bold text-base text-slate-900 mb-4">Apoteker Penanggung Jawab</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?auto=format&fit=crop&w=150&q=80" class="w-11 h-11 rounded-full object-cover object-top">
                            <div>
                                <p class="font-semibold text-sm text-slate-900">dr. Hendra Pratama</p>
                                <p class="text-xs text-slate-500">Dokter Umum</p>
                            </div>
                        </div>
                        <a href="https://wa.me/6281234567890" target="_blank" class="text-xs font-semibold text-teal-700 hover:underline">Chat WA</a>
                    </div>
                    <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1537368910025-700350fe46c7?auto=format&fit=crop&w=150&q=80" class="w-11 h-11 rounded-full object-cover object-top">
                            <div>
                                <p class="font-semibold text-sm text-slate-900">apt. Rizky Ramadhan, S.Farm</p>
                                <p class="text-xs text-slate-500">SIPA: 446/102/SIPA/2026</p>
                            </div>
                        </div>
                        <a href="https://wa.me/6281234567890" target="_blank" class="text-xs font-semibold text-teal-700 hover:underline">Chat WA</a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-6">
                <h2 class="font-bold text-base text-slate-900 mb-3">Pertanyaan Umum</h2>
                <div class="divide-y divide-slate-100 text-sm">
                    <details class="py-3 first:pt-0" open>
                        <summary class="font-semibold text-slate-800 cursor-pointer">Apakah obat yang dijual dijamin asli?</summary>
                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Ya, semua produk diambil dari distributor farmasi resmi (PBF) dan dicek tanggal kadaluarsanya sebelum dikirim.</p>
                    </details>
                    <details class="py-3">
                        <summary class="font-semibold text-slate-800 cursor-pointer">Apakah pesanan bisa diambil langsung di apotek?</summary>
                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Bisa, pilih opsi <strong>Ambil Sendiri di Apotek (Gratis)</strong> saat checkout dan tunjukkan nomor invoice ke kasir.</p>
                    </details>
                    <details class="py-3 last:pb-0">
                        <summary class="font-semibold text-slate-800 cursor-pointer">Bagaimana cara menebus resep dokter?</summary>
                        <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Klik menu Upload Resep, kirim foto resep yang jelas, lalu apoteker kami akan mengonfirmasi ketersediaan dan total biaya lewat WhatsApp.</p>
                    </details>
                </div>
            </div>
        </section>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 grid grid-cols-1 md:grid-cols-3 gap-6 text-xs text-slate-500">
            <div>
                <p class="font-bold text-slate-900 text-sm">Apotek Sehat</p>
                <p class="mt-1.5 leading-relaxed">Jl. Kesehatan No. 12 • Buka Setiap Hari 08.00 – 22.00 WIB</p>
                <p class="mt-1 text-slate-400">No. SIA: 503/014/SIA/2026</p>
            </div>
            <div>
                <p class="font-semibold text-slate-900 mb-2">Menu Cepat</p>
                <div class="flex flex-col gap-1.5">
                    <a href="{{ route('toko.index') }}" class="hover:text-teal-700">Katalog Obat</a>
                    <a href="{{ route('toko.favorit') }}" class="hover:text-teal-700">Obat Tersimpan</a>
                    <a href="{{ route('toko.resep') }}" class="hover:text-teal-700">Upload Resep Dokter</a>
                    <a href="{{ route('toko.lacak') }}" class="hover:text-teal-700">Lacak Pesanan</a>
                </div>
            </div>
            <div>
                <p class="font-semibold text-slate-900 mb-2">Kontak Apoteker</p>
                <p>WhatsApp: 0812-3456-7890</p>
                <p class="mt-1">Email: layanan@apoteksehat.id</p>
            </div>
        </div>
        <div class="border-t border-slate-100 py-4 text-center text-[11px] text-slate-400">
            &copy; {{ date('Y') }} Apotek Sehat.
        </div>
    </footer>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-5 right-5 z-50 hidden bg-slate-900 text-white px-4 py-3 rounded-lg shadow-lg text-xs font-medium flex items-center gap-3">
        <span id="toastMessage"></span>
        <button type="button" onclick="toggleCartDrawer()" class="text-teal-400 font-semibold hover:underline shrink-0">Buka Keranjang</button>
    </div>

    <!-- Real-Time Slide-Over Cart Drawer -->
    <div id="cartDrawerBackdrop" class="fixed inset-0 z-50 hidden bg-slate-900/40" onclick="toggleCartDrawer()"></div>
    <aside id="cartDrawer" class="fixed top-0 right-0 z-50 h-full w-full max-w-md bg-white shadow-xl border-l border-slate-200 transform translate-x-full transition-transform duration-200 flex flex-col">
        <div class="px-5 h-14 border-b border-slate-200 flex items-center justify-between">
            <h2 class="font-bold text-slate-900 text-sm">Keranjang (<span id="drawerCount">{{ $cartCount }}</span>)</h2>
            <button type="button" onclick="toggleCartDrawer()" class="text-slate-400 hover:text-slate-700 text-lg px-2">✕</button>
        </div>

        <div id="drawerItemsContainer" class="flex-1 overflow-y-auto p-5 divide-y divide-slate-100">
            @forelse($cart as $id => $item)
                <div class="py-3.5 first:pt-0 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-lg p-1 flex items-center justify-center shrink-0">
                            @if(!empty($item['gambar']))
                                <img src="{{ asset('storage/' . $item['gambar']) }}" class="max-h-full max-w-full object-contain">
                            @else
                                <span class="text-[10px] text-slate-400">-</span>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-slate-900 capitalize">{{ $item['nama_obat'] }}</p>
                            <p class="text-xs text-slate-500">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                            <div class="flex items-center gap-2 mt-1.5">
                                <button type="button" onclick="updateDrawerQty('{{ route('toko.qty', $id) }}', 'minus')" class="w-6 h-6 rounded border border-slate-200 hover:bg-slate-100 text-xs font-bold">-</button>
                                <span class="text-xs font-semibold w-4 text-center">{{ $item['qty'] }}</span>
                                <button type="button" onclick="updateDrawerQty('{{ route('toko.qty', $id) }}', 'plus')" class="w-6 h-6 rounded border border-slate-200 hover:bg-slate-100 text-xs font-bold">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-sm text-slate-900">Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</p>
                        <button type="button" onclick="removeDrawerItem('{{ route('toko.remove', $id) }}')" class="text-[11px] text-red-500 hover:underline mt-1">Hapus</button>
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-center py-12">
                    <p class="font-semibold text-slate-700 text-sm">Keranjang masih kosong</p>
                    <p class="text-xs text-slate-400 mt-1">Pilih obat atau vitamin dari katalog.</p>
                </div>
            @endforelse
        </div>

        <div id="drawerFooter" class="p-5 border-t border-slate-200 bg-slate-50 space-y-3 {{ count($cart) > 0 ? '' : 'hidden' }}">
            <div class="flex justify-between items-center">
                <span class="text-xs text-slate-600">Total Sementara</span>
                <span id="drawerTotal" class="text-base font-bold text-slate-900">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
            </div>
            <a href="{{ route('toko.cart') }}" class="block w-full text-center bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg font-semibold text-sm transition">
                Lanjut ke Checkout
            </a>
        </div>
    </aside>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Sanitasi teks untuk mencegah XSS saat render via innerHTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text ?? '';
            return div.innerHTML;
        }

        function toggleCartDrawer() {
            document.getElementById('cartDrawer').classList.toggle('translate-x-full');
            document.getElementById('cartDrawerBackdrop').classList.toggle('hidden');
        }

        function renderCartState(data) {
            const badge = document.getElementById('cartBadge');
            badge.innerText = data.cartCount;
            badge.classList.toggle('hidden', data.cartCount < 1);
            document.getElementById('drawerCount').innerText = data.cartCount;
            document.getElementById('drawerTotal').innerText = 'Rp ' + data.cartTotal;

            const container = document.getElementById('drawerItemsContainer');
            const footer = document.getElementById('drawerFooter');

            if (!data.items || data.items.length === 0) {
                container.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center text-center py-12">
                        <p class="font-semibold text-slate-700 text-sm">Keranjang masih kosong</p>
                        <p class="text-xs text-slate-400 mt-1">Pilih obat atau vitamin dari katalog.</p>
                    </div>`;
                footer.classList.add('hidden');
                return;
            }

            footer.classList.remove('hidden');
            container.innerHTML = data.items.map(item => `
                <div class="py-3.5 first:pt-0 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-slate-50 border border-slate-100 rounded-lg p-1 flex items-center justify-center shrink-0">
                            ${item.gambar_url
                                ? `<img src="${escapeHtml(item.gambar_url)}" class="max-h-full max-w-full object-contain">`
                                : `<span class="text-[10px] text-slate-400">-</span>`}
                        </div>
                        <div>
                            <p class="font-semibold text-sm text-slate-900">${escapeHtml(item.nama_obat)}</p>
                            <p class="text-xs text-slate-500">Rp ${escapeHtml(item.harga_format)}</p>
                            <div class="flex items-center gap-2 mt-1.5">
                                <button type="button" onclick="updateDrawerQty('${escapeHtml(item.qty_url)}', 'minus')" class="w-6 h-6 rounded border border-slate-200 hover:bg-slate-100 text-xs font-bold">-</button>
                                <span class="text-xs font-semibold w-4 text-center">${Number(item.qty)}</span>
                                <button type="button" onclick="updateDrawerQty('${escapeHtml(item.qty_url)}', 'plus')" class="w-6 h-6 rounded border border-slate-200 hover:bg-slate-100 text-xs font-bold">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-sm text-slate-900">Rp ${escapeHtml(item.subtotal_format)}</p>
                        <button type="button" onclick="removeDrawerItem('${escapeHtml(item.remove_url)}')" class="text-[11px] text-red-500 hover:underline mt-1">Hapus</button>
                    </div>
                </div>
            `).join('');
        }

        async function updateDrawerQty(url, action) {
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ action })
                });
                const data = await res.json();
                if (res.ok && data.status === 'success') {
                    renderCartState(data);
                } else {
                    showToast(data.message || 'Gagal mengubah jumlah.', true);
                }
            } catch (e) {}
        }

        async function removeDrawerItem(url) {
            try {
                const res = await fetch(url, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (res.ok && data.status === 'success') {
                    renderCartState(data);
                }
            } catch (e) {}
        }

        async function toggleFavorit(btn, url) {
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (res.ok && data.status === 'success') {
                    const svg = btn.querySelector('svg');
                    if (data.saved) {
                        btn.classList.remove('text-slate-400');
                        btn.classList.add('text-rose-500');
                        svg.setAttribute('fill', 'currentColor');
                    } else {
                        btn.classList.add('text-slate-400');
                        btn.classList.remove('text-rose-500');
                        svg.setAttribute('fill', 'none');
                    }
                    const favBadge = document.getElementById('favBadge');
                    favBadge.innerText = data.count;
                    favBadge.classList.toggle('hidden', data.count < 1);
                    showToast(data.message);
                }
            } catch (e) {}
        }

        let toastTimer;
        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            document.getElementById('toastMessage').innerText = message;
            toast.classList.remove('hidden', 'bg-slate-900', 'bg-red-600');
            toast.classList.add(isError ? 'bg-red-600' : 'bg-slate-900');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => toast.classList.add('hidden'), 3000);
        }

        document.querySelectorAll('.ajax-cart-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const btn = this.querySelector('button');
                const oldText = btn.innerText;
                btn.disabled = true;
                btn.innerText = '...';
                try {
                    const res = await fetch(this.action, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    if (res.ok && data.status === 'success') {
                        renderCartState(data);
                        showToast(data.message);
                    } else {
                        showToast(data.message || 'Gagal menambahkan.', true);
                    }
                } catch (err) {
                    showToast('Koneksi bermasalah.', true);
                } finally {
                    btn.disabled = false;
                    btn.innerText = oldText;
                }
            });
        });

        const searchInput = document.getElementById('searchInput');
        const liveSearchBox = document.getElementById('liveSearchBox');
        let searchTimeout;

        searchInput?.addEventListener('input', function() {
            const q = this.value.trim();
            clearTimeout(searchTimeout);
            if (q.length < 2) { liveSearchBox.classList.add('hidden'); return; }
            searchTimeout = setTimeout(async () => {
                try {
                    const res = await fetch(`{{ route('toko.liveSearch') }}?q=${encodeURIComponent(q)}`);
                    const items = await res.json();
                    liveSearchBox.innerHTML = items.length === 0
                        ? `<div class="p-3.5 text-xs text-slate-400 text-center">Produk tidak ditemukan</div>`
                        : items.map(item => `
                            <a href="${escapeHtml(item.url)}" class="flex items-center justify-between p-3 hover:bg-slate-50 transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-slate-50 border border-slate-100 rounded p-1 flex items-center justify-center shrink-0">
                                        ${item.gambar ? `<img src="${escapeHtml(item.gambar)}" class="max-h-full max-w-full object-contain">` : `<span class="text-[10px] text-slate-400">-</span>`}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">${escapeHtml(item.nama_obat)}</p>
                                        <p class="text-[11px] text-slate-500">${escapeHtml(item.kategori)}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-teal-700">Rp ${escapeHtml(item.harga)}</span>
                            </a>
                        `).join('');
                    liveSearchBox.classList.remove('hidden');
                } catch (e) {}
            }, 250);
        });

        document.addEventListener('click', e => {
            if (!searchInput?.contains(e.target) && !liveSearchBox?.contains(e.target)) liveSearchBox?.classList.add('hidden');
        });
    </script>
</body>
</html>