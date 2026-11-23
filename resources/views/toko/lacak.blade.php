<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Pesanan — Apotek Sehat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F8FAFC] text-slate-900 min-h-screen antialiased">

    <header class="bg-white border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('toko.index') }}" class="font-bold text-[#0F3D3E] text-base">Apotek Sehat</a>
            <a href="{{ route('toko.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Kembali ke Katalog</a>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 py-10">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Lacak Status Pesanan</h1>
            <p class="text-sm text-slate-500 mt-1">Masukkan nomor invoice untuk melihat progres pesanan obat Anda.</p>
        </div>

        <form action="{{ route('toko.lacak') }}" method="GET" class="flex gap-2 mb-8">
            <input type="text" name="kode" value="{{ request('kode') }}" required placeholder="Contoh: APT-20261123-1234"
                class="flex-1 bg-white border border-slate-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#0F3D3E]">
            <button type="submit" class="bg-[#0F3D3E] hover:bg-[#0b2f30] text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                Lacak
            </button>
        </form>

        @if($notFound)
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm text-center">
                Nomor invoice <strong>{{ request('kode') }}</strong> tidak ditemukan. Periksa kembali kode Anda.
            </div>
        @endif

        @if($transaksi)
            @php
                $steps = ['Menunggu', 'Diproses', 'Dikirim', 'Selesai'];
                $currentIndex = array_search($transaksi->status, $steps);
            @endphp

            <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                <div class="flex flex-wrap justify-between items-start gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <span class="text-xs text-slate-400">Nomor Invoice</span>
                        <h2 class="text-lg font-bold text-slate-900">{{ $transaksi->kode_transaksi }}</h2>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $transaksi->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block">Status Saat Ini</span>
                        <span class="inline-block mt-1 text-xs font-semibold px-3 py-1 rounded-full
                            {{ $transaksi->status === 'Selesai' ? 'bg-emerald-100 text-emerald-800' :
                               ($transaksi->status === 'Dibatalkan' ? 'bg-red-100 text-red-800' : 'bg-teal-100 text-teal-800') }}">
                            {{ $transaksi->status }}
                        </span>
                    </div>
                </div>

                <!-- Progress Stepper -->
                @if($transaksi->status !== 'Dibatalkan')
                    <div class="py-8">
                        <div class="grid grid-cols-4 gap-2 text-center">
                            @foreach($steps as $i => $step)
                                @php $done = $currentIndex !== false && $i <= $currentIndex; @endphp
                                <div class="flex flex-col items-center">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold mb-2
                                        {{ $done ? 'bg-[#0F3D3E] text-white' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                                        {{ $i + 1 }}
                                    </div>
                                    <span class="text-xs font-medium {{ $done ? 'text-slate-900' : 'text-slate-400' }}">{{ $step }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="my-6 bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg text-sm text-center">
                        Pesanan ini telah dibatalkan. Silakan hubungi apoteker jika ada pertanyaan.
                    </div>
                @endif

                <div class="border-t border-slate-100 pt-5 text-sm space-y-2">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Pemesan</span>
                        <span class="font-medium text-slate-900">{{ $transaksi->nama_pembeli }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Metode Pembayaran</span>
                        <span class="font-medium text-slate-900">{{ $transaksi->metode_pembayaran }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Total Pembayaran</span>
                        <span class="font-bold text-[#0F3D3E]">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 text-right">
                    <a href="{{ route('toko.invoice', $transaksi->kode_transaksi) }}" class="text-xs font-semibold text-[#0F3D3E] hover:underline">
                        Lihat Detail Struk →
                    </a>
                </div>
            </div>
        @endif
    </main>
</body>
</html>