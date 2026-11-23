<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — Apotek Sehat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F6F8F9] text-slate-900 min-h-screen antialiased">

    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="font-bold text-slate-900 text-base">Apotek<span class="text-teal-600">Sehat</span></span>
                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-medium">Admin Panel</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('toko.index') }}" class="text-sm text-slate-600 hover:text-slate-900 font-medium">Lihat Toko</a>
                <a href="{{ route('obat.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">+ Tambah Obat</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="border border-slate-200 hover:bg-red-50 hover:text-red-600 hover:border-red-200 text-slate-600 px-3.5 py-2 rounded-lg text-sm font-medium transition">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 space-y-8">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Peringatan Obat Hampir / Sudah Kadaluarsa -->
        @if($obatHampirExpired->count() > 0)
            <div class="bg-amber-50 border border-amber-300 rounded-xl p-4 text-sm text-amber-950">
                <p class="font-bold">Peringatan Kadaluarsa ({{ $obatHampirExpired->count() }} Produk)</p>
                <p class="text-xs text-amber-800 mt-0.5">Obat berikut sudah kadaluarsa atau akan kadaluarsa dalam 30 hari ke depan:</p>
                <div class="flex flex-wrap gap-2 mt-2.5">
                    @foreach($obatHampirExpired as $expObat)
                        <a href="{{ route('obat.edit', $expObat->id) }}" class="bg-white border border-amber-300 hover:border-amber-500 px-2.5 py-1 rounded-md text-xs font-medium capitalize">
                            {{ $expObat->nama_obat }} ({{ $expObat->tanggal_kadaluarsa }})
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 4 Kartu Statistik -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <span class="text-xs font-medium text-slate-500">Total Produk</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $totalObat }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <span class="text-xs font-medium text-slate-500">Stok Menipis (&le; 5)</span>
                <p class="text-2xl font-bold {{ $stokMenipis > 0 ? 'text-amber-600' : 'text-slate-900' }} mt-1">{{ $stokMenipis }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <span class="text-xs font-medium text-slate-500">Total Pesanan</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $totalTransaksi }}</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200">
                <span class="text-xs font-medium text-slate-500">Total Pendapatan</span>
                <p class="text-2xl font-bold text-teal-700 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Grafik Penjualan 7 Hari Terakhir -->
        <section class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="mb-4">
                <h2 class="font-bold text-slate-900">Grafik Pendapatan (7 Hari Terakhir)</h2>
                <p class="text-xs text-slate-500">Total transaksi harian di luar pesanan dibatalkan</p>
            </div>
            <div class="h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </section>

        <!-- Tabel Resep Dokter Masuk (Foto Private Khusus Admin) -->
        <section class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
                <h2 class="font-bold text-slate-900">Resep Dokter Masuk</h2>
                <span class="text-xs text-slate-500">{{ $reseps->count() }} resep terbaru</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs text-slate-500 uppercase border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Foto Resep</th>
                            <th class="px-5 py-3 font-semibold">Pasien</th>
                            <th class="px-5 py-3 font-semibold">Catatan</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 font-semibold text-right">Hubungi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reseps as $resep)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('resep.foto', $resep->id) }}" target="_blank" class="inline-block border border-slate-200 rounded-lg overflow-hidden hover:opacity-80 transition">
                                        <img src="{{ route('resep.foto', $resep->id) }}" alt="Foto Resep" class="w-14 h-14 object-cover">
                                    </a>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-slate-900">{{ $resep->nama_pasien }}</p>
                                    <p class="text-xs text-slate-500">{{ $resep->no_hp }} • {{ $resep->created_at->format('d M, H:i') }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-600 max-w-xs">{{ $resep->catatan ?? '-' }}</td>
                                <td class="px-5 py-3.5">
                                    <form action="{{ route('resep.status', $resep->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                            class="text-xs font-semibold rounded-lg px-2.5 py-1.5 border border-slate-200 bg-white focus:outline-none focus:border-teal-600">
                                            @foreach(['Menunggu', 'Diproses', 'Selesai', 'Ditolak'] as $st)
                                                <option value="{{ $st }}" {{ $resep->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $resep->no_hp)) }}?text={{ urlencode('Halo ' . $resep->nama_pasien . ', resep dokter Anda di Apotek Sehat sudah kami terima.') }}"
                                       target="_blank" class="text-xs font-semibold text-teal-700 hover:underline">
                                        Chat WA
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada resep dokter yang masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Tabel Riwayat Transaksi Terbaru -->
        <section class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex flex-wrap justify-between items-center gap-3">
                <h2 class="font-bold text-slate-900">Pesanan Terbaru</h2>
                <a href="{{ route('transaksi.export') }}" class="border border-slate-300 hover:bg-slate-50 text-slate-700 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition">
                    Export Laporan (CSV)
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs text-slate-500 uppercase border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Invoice</th>
                            <th class="px-5 py-3 font-semibold">Pembeli</th>
                            <th class="px-5 py-3 font-semibold">Total</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 font-semibold text-right">Struk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transaksis as $trx)
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-slate-900">{{ $trx->kode_transaksi }}</p>
                                    <p class="text-xs text-slate-400">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="font-medium text-slate-800">{{ $trx->nama_pembeli }}</p>
                                    <p class="text-xs text-slate-500">{{ $trx->no_hp }} • {{ $trx->kurir ?? 'Kurir' }} • {{ $trx->metode_pembayaran }}</p>
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-teal-700">
                                    Rp {{ number_format($trx->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <form action="{{ route('transaksi.status', $trx->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                            class="text-xs font-semibold rounded-lg px-2.5 py-1.5 border border-slate-200 bg-white focus:outline-none focus:border-teal-600">
                                            @foreach(['Menunggu', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'] as $st)
                                                <option value="{{ $st }}" {{ $trx->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('toko.invoice', $trx->kode_transaksi) }}" class="text-xs font-semibold text-teal-700 hover:underline">Lihat</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada pesanan masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Tabel Data Obat -->
        <section class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center">
                <h2 class="font-bold text-slate-900">Inventaris Obat</h2>
                <span class="text-xs text-slate-500">{{ $obats->count() }} item terdaftar</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs text-slate-500 uppercase border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Produk</th>
                            <th class="px-5 py-3 font-semibold">Kategori</th>
                            <th class="px-5 py-3 font-semibold">Harga</th>
                            <th class="px-5 py-3 font-semibold">Stok</th>
                            <th class="px-5 py-3 font-semibold">Kadaluarsa</th>
                            <th class="px-5 py-3 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($obats as $obat)
                            @php
                                $isNearExp = $obat->tanggal_kadaluarsa && \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->lte(now()->addDays(30));
                            @endphp
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-5 py-3.5 flex items-center gap-3">
                                    <div class="w-11 h-11 bg-slate-50 border border-slate-100 rounded-lg p-1 flex items-center justify-center shrink-0">
                                        @if($obat->gambar)
                                            <img src="{{ asset('storage/' . $obat->gambar) }}" class="max-h-full max-w-full object-contain">
                                        @else
                                            <span class="text-[10px] text-slate-400">-</span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 capitalize">{{ $obat->nama_obat }}</p>
                                        <p class="text-xs text-slate-500">{{ Str::limit($obat->deskripsi, 40) }}</p>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5"><span class="text-xs bg-teal-50 text-teal-800 px-2 py-0.5 rounded font-medium">{{ $obat->kategori }}</span></td>
                                <td class="px-5 py-3.5 font-medium">Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="font-semibold {{ $obat->stok <= 5 ? 'text-red-600' : 'text-slate-800' }}">{{ $obat->stok }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-xs {{ $isNearExp ? 'text-red-600 font-bold' : 'text-slate-600' }}">
                                    {{ $obat->tanggal_kadaluarsa ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-right space-x-3">
                                    <a href="{{ route('obat.edit', $obat->id) }}" class="text-teal-700 hover:underline font-medium text-xs">Edit</a>
                                    <form action="{{ route('obat.destroy', $obat->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus obat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline font-medium text-xs">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada data obat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json($chartData),
                    backgroundColor: '#0D9488',
                    borderRadius: 6,
                    maxBarThickness: 42
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString('id-ID')
                        }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    </script>
</body>
</html>