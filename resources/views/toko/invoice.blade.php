<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $transaksi->kode_transaksi }} — Apotek Sehat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F6F8F9] text-slate-900 py-10 px-4 antialiased">

    @php
        $subtotal = max(0, $transaksi->total_harga - ($transaksi->ongkir ?? 0));
        $waText = "Halo Apotek Sehat, saya sudah melakukan pemesanan:\n"
            . "No. Invoice: {$transaksi->kode_transaksi}\n"
            . "Nama: {$transaksi->nama_pembeli}\n"
            . "Pengiriman: {$transaksi->kurir}\n"
            . "Alamat: {$transaksi->alamat}\n"
            . "Pembayaran: {$transaksi->metode_pembayaran}\n"
            . "Total: Rp " . number_format($transaksi->total_harga, 0, ',', '.');
    @endphp

    <div class="max-w-xl mx-auto bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <div class="flex justify-between items-start border-b border-slate-200 pb-5">
            <div>
                <h1 class="font-bold text-lg text-slate-900">Apotek<span class="text-teal-600">Sehat</span></h1>
                <p class="text-xs text-slate-500 mt-0.5">Bukti Pemesanan Resmi</p>
            </div>
            <div class="text-right">
                <span class="text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 px-2.5 py-1 rounded">
                    {{ $transaksi->status }}
                </span>
                <p class="text-xs text-slate-500 mt-2">{{ $transaksi->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 py-5 border-b border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 block">Nomor Invoice</span>
                <span class="font-bold text-slate-900 text-sm">{{ $transaksi->kode_transaksi }}</span>
            </div>
            <div>
                <span class="text-slate-400 block">Pengiriman</span>
                <span class="font-semibold text-slate-900 text-sm">{{ $transaksi->kurir ?? 'Kurir Apotek' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block">Pemesan</span>
                <span class="font-semibold text-slate-800">{{ $transaksi->nama_pembeli }} ({{ $transaksi->no_hp }})</span>
            </div>
            <div>
                <span class="text-slate-400 block">Pembayaran</span>
                <span class="font-semibold text-slate-800">{{ $transaksi->metode_pembayaran }}</span>
            </div>
            <div class="col-span-2">
                <span class="text-slate-400 block">Alamat</span>
                <span class="text-slate-700">{{ $transaksi->alamat }}</span>
            </div>
        </div>

        <div class="py-4">
            <table class="w-full text-sm">
                <thead class="text-xs text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="text-left py-2 font-medium">Produk</th>
                        <th class="text-center py-2 font-medium">Qty</th>
                        <th class="text-right py-2 font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transaksi->items as $item)
                        <tr>
                            <td class="py-3 font-medium text-slate-800 capitalize">{{ $item['nama_obat'] }}</td>
                            <td class="py-3 text-center text-slate-600">{{ $item['qty'] }}</td>
                            <td class="py-3 text-right font-medium text-slate-900">
                                Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 pt-4 space-y-1.5 text-sm">
            <div class="flex justify-between text-slate-500">
                <span>Subtotal Produk</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-slate-500">
                <span>Ongkos Kirim</span>
                <span>{{ ($transaksi->ongkir ?? 0) > 0 ? 'Rp ' . number_format($transaksi->ongkir, 0, ',', '.') : 'Gratis' }}</span>
            </div>
            <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                <span class="font-semibold text-slate-800">Total Pembayaran</span>
                <span class="text-xl font-bold text-teal-700">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-3 print:hidden">
            <button onclick="window.print()" class="flex-1 border border-slate-300 hover:bg-slate-50 text-slate-800 py-2.5 rounded-lg text-sm font-semibold transition">
                Cetak Struk
            </button>
            <a href="https://wa.me/6281234567890?text={{ urlencode($waText) }}" target="_blank"
               class="flex-1 text-center bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg text-sm font-semibold transition">
                Konfirmasi via WhatsApp
            </a>
        </div>
        <div class="text-center mt-4 print:hidden">
            <a href="{{ route('toko.index') }}" class="text-xs text-slate-500 hover:text-slate-800 underline">Kembali ke Katalog</a>
        </div>
    </div>
</body>
</html>