<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja — Apotek Sehat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F6F8F9] text-slate-800 min-h-screen antialiased">

    <header class="bg-white border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('toko.index') }}" class="font-bold text-lg text-slate-900">Apotek<span class="text-teal-600">Sehat</span></a>
            <a href="{{ route('toko.index') }}" class="text-sm font-medium text-teal-700 hover:underline">Kembali ke Katalog</a>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-2xl font-bold text-slate-900 mb-6">Keranjang & Pengiriman</h1>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 text-sm">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 text-sm">{{ $errors->first() }}</div>
        @endif

        @if(count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                <!-- Daftar Item -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
                    @foreach($cart as $id => $item)
                        <div class="p-4 sm:p-5 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-lg p-1.5 flex items-center justify-center shrink-0">
                                    @if(!empty($item['gambar']))
                                        <img src="{{ asset('storage/' . $item['gambar']) }}" class="max-h-full max-w-full object-contain">
                                    @else
                                        <span class="text-[10px] text-slate-400">No Img</span>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900 capitalize">{{ $item['nama_obat'] }}</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Rp {{ number_format($item['harga'], 0, ',', '.') }} / item</p>

                                    <div class="flex items-center gap-2 mt-2.5">
                                        <form action="{{ route('toko.qty', $id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="minus">
                                            <button type="submit" class="w-7 h-7 rounded border border-slate-200 hover:bg-slate-100 text-sm font-semibold">-</button>
                                        </form>
                                        <span class="text-sm font-semibold w-6 text-center">{{ $item['qty'] }}</span>
                                        <form action="{{ route('toko.qty', $id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="plus">
                                            <button type="submit" class="w-7 h-7 rounded border border-slate-200 hover:bg-slate-100 text-sm font-semibold">+</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="font-bold text-slate-900">Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</p>
                                <form action="{{ route('toko.remove', $id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Form Checkout + Pilihan Pengiriman -->
                <div class="bg-white rounded-xl border border-slate-200 p-5">
                    <h2 class="font-bold text-slate-900 text-base mb-4 pb-3 border-b border-slate-100">Detail Pemesanan</h2>

                    <form action="{{ route('toko.checkout') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama_pembeli" value="{{ old('nama_pembeli') }}" required placeholder="Nama penerima" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-600">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Nomor WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-600">
                        </div>

                        <!-- Opsi Pengiriman -->
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1.5">Opsi Pengiriman</label>
                            <div class="space-y-2 text-xs">
                                <label class="flex items-center justify-between p-2.5 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-50">
                                    <span class="flex items-center gap-2">
                                        <input type="radio" name="kurir" value="Kurir Instan (1 Jam)" data-ongkir="10000" checked onchange="updateShipping()" class="accent-teal-600">
                                        <span class="font-medium text-slate-800">Kurir Instan (1 Jam)</span>
                                    </span>
                                    <span class="font-semibold text-slate-700">Rp 10.000</span>
                                </label>
                                <label class="flex items-center justify-between p-2.5 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-50">
                                    <span class="flex items-center gap-2">
                                        <input type="radio" name="kurir" value="Pengiriman Reguler" data-ongkir="7000" onchange="updateShipping()" class="accent-teal-600">
                                        <span class="font-medium text-slate-800">Pengiriman Reguler</span>
                                    </span>
                                    <span class="font-semibold text-slate-700">Rp 7.000</span>
                                </label>
                                <label class="flex items-center justify-between p-2.5 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-50">
                                    <span class="flex items-center gap-2">
                                        <input type="radio" name="kurir" value="Ambil di Apotek" data-ongkir="0" onchange="updateShipping()" class="accent-teal-600">
                                        <span class="font-medium text-slate-800">Ambil Sendiri di Apotek</span>
                                    </span>
                                    <span class="font-semibold text-teal-700">Gratis</span>
                                </label>
                            </div>
                        </div>

                        <div id="alamatBox">
                            <label class="block text-xs font-medium text-slate-600 mb-1">Alamat Lengkap</label>
                            <textarea id="alamatInput" name="alamat" rows="2" required placeholder="Nama jalan, nomor rumah, patokan..." class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-600">{{ old('alamat') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:border-teal-600">
                                <option value="COD (Bayar di Tempat)">COD (Bayar di Tempat)</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>

                        <div class="pt-3 border-t border-slate-100 space-y-1.5 text-sm">
                            <div class="flex justify-between text-slate-500">
                                <span>Subtotal Produk</span>
                                <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-500">
                                <span>Ongkos Kirim</span>
                                <span id="ongkirText">Rp 10.000</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                                <span class="font-semibold text-slate-800">Total Bayar</span>
                                <span id="grandTotalText" class="text-lg font-bold text-teal-700">Rp {{ number_format($total + 10000, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2.5 rounded-lg font-semibold text-sm transition">
                            Buat Pesanan
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
                <p class="font-semibold text-slate-800">Keranjang belanja masih kosong</p>
                <p class="text-sm text-slate-500 mt-1">Pilih obat atau vitamin terlebih dahulu di halaman katalog.</p>
                <a href="{{ route('toko.index') }}" class="inline-block mt-4 bg-teal-600 hover:bg-teal-700 text-white px-5 py-2 rounded-lg text-sm font-semibold">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </main>

    <script>
        const subtotal = {{ $total }};

        function updateShipping() {
            const selected = document.querySelector('input[name="kurir"]:checked');
            const ongkir = parseInt(selected.dataset.ongkir || '0', 10);
            const isPickup = selected.value === 'Ambil di Apotek';

            document.getElementById('ongkirText').innerText = ongkir === 0 ? 'Gratis' : 'Rp ' + ongkir.toLocaleString('id-ID');
            document.getElementById('grandTotalText').innerText = 'Rp ' + (subtotal + ongkir).toLocaleString('id-ID');

            const alamatBox = document.getElementById('alamatBox');
            const alamatInput = document.getElementById('alamatInput');
            if (isPickup) {
                alamatBox.classList.add('hidden');
                alamatInput.removeAttribute('required');
            } else {
                alamatBox.classList.remove('hidden');
                alamatInput.setAttribute('required', 'required');
            }
        }
    </script>
</body>
</html>