<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Resep;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::latest()->get();
        $transaksis = Transaksi::latest()->take(15)->get();
        $reseps = Resep::latest()->take(10)->get();

        $totalObat = $obats->count();
        $stokMenipis = $obats->where('stok', '<=', 5)->count();
        $totalTransaksi = Transaksi::count();
        $totalPendapatan = Transaksi::where('status', '!=', 'Dibatalkan')->sum('total_harga');

        $obatHampirExpired = $obats->filter(function ($obat) {
            return $obat->tanggal_kadaluarsa && Carbon::parse($obat->tanggal_kadaluarsa)->lte(now()->addDays(30));
        });

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = (int) Transaksi::whereDate('created_at', $date->toDateString())
                ->where('status', '!=', 'Dibatalkan')
                ->sum('total_harga');
        }

        return view('obat.index', compact(
            'obats',
            'transaksis',
            'reseps',
            'obatHampirExpired',
            'totalObat',
            'stokMenipis',
            'totalTransaksi',
            'totalPendapatan',
            'chartLabels',
            'chartData'
        ));
    }

    public function create()
    {
        return view('obat.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_obat' => 'required|string|max:150',
            'kategori' => 'required|in:Obat Bebas,Obat Keras,Vitamin & Suplemen,Sirup',
            'harga' => 'required|integer|min:0|max:100000000',
            'stok' => 'required|integer|min:0|max:100000',
            'tanggal_kadaluarsa' => 'nullable|date',
            'deskripsi' => 'nullable|string|max:1000',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data['nama_obat'] = strip_tags($data['nama_obat']);
        $data['deskripsi'] = isset($data['deskripsi']) ? strip_tags($data['deskripsi']) : null;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('obat', 'public');
        }

        Obat::create($data);

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil ditambahkan!');
    }

    public function edit(Obat $obat)
    {
        return view('obat.edit', compact('obat'));
    }

    public function update(Request $request, Obat $obat)
    {
        $data = $request->validate([
            'nama_obat' => 'required|string|max:150',
            'kategori' => 'required|in:Obat Bebas,Obat Keras,Vitamin & Suplemen,Sirup',
            'harga' => 'required|integer|min:0|max:100000000',
            'stok' => 'required|integer|min:0|max:100000',
            'tanggal_kadaluarsa' => 'nullable|date',
            'deskripsi' => 'nullable|string|max:1000',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data['nama_obat'] = strip_tags($data['nama_obat']);
        $data['deskripsi'] = isset($data['deskripsi']) ? strip_tags($data['deskripsi']) : null;

        if ($request->hasFile('gambar')) {
            if ($obat->gambar) {
                Storage::disk('public')->delete($obat->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('obat', 'public');
        }

        $obat->update($data);

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil diupdate!');
    }

    public function destroy(Obat $obat)
    {
        if ($obat->gambar) {
            Storage::disk('public')->delete($obat->gambar);
        }
        $obat->delete();

        return redirect()->route('obat.index')->with('success', 'Data obat berhasil dihapus!');
    }

    public function updateStatus(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Dikirim,Selesai,Dibatalkan',
        ]);

        $transaksi->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan ' . $transaksi->kode_transaksi . ' diubah menjadi ' . $request->status . '.');
    }

    public function updateStatusResep(Request $request, Resep $resep)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Selesai,Ditolak',
        ]);

        $resep->update(['status' => $request->status]);

        return back()->with('success', 'Status resep atas nama ' . $resep->nama_pasien . ' diperbarui.');
    }

    // Khusus admin login untuk melihat foto resep dari storage private
    public function lihatResep(Resep $resep)
    {
        if (Storage::disk('local')->exists($resep->foto_resep)) {
            return response()->file(Storage::disk('local')->path($resep->foto_resep));
        }

        if (Storage::disk('public')->exists($resep->foto_resep)) {
            return response()->file(Storage::disk('public')->path($resep->foto_resep));
        }

        abort(404);
    }

    public function exportTransaksi()
    {
        $transaksis = Transaksi::latest()->get();
        $filename = 'laporan-penjualan-' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transaksis) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Invoice', 'Tanggal', 'Nama Pembeli', 'No HP', 'Pengiriman', 'Alamat', 'Metode Bayar', 'Ongkir', 'Total Harga', 'Status']);

            foreach ($transaksis as $trx) {
                fputcsv($file, [
                    $trx->kode_transaksi,
                    $trx->created_at->format('Y-m-d H:i'),
                    $trx->nama_pembeli,
                    $trx->no_hp,
                    $trx->kurir ?? '-',
                    $trx->alamat,
                    $trx->metode_pembayaran,
                    $trx->ongkir ?? 0,
                    $trx->total_harga,
                    $trx->status,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}