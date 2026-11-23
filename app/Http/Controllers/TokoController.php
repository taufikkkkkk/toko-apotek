<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Resep;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TokoController extends Controller
{
    private function cartPayload(array $cart, ?string $message = null): array
    {
        $items = [];
        $total = 0;
        $count = 0;

        foreach ($cart as $id => $item) {
            $subtotal = $item['harga'] * $item['qty'];
            $total += $subtotal;
            $count += $item['qty'];

            $items[] = [
                'id' => $id,
                'nama_obat' => ucwords($item['nama_obat']),
                'harga_format' => number_format($item['harga'], 0, ',', '.'),
                'subtotal_format' => number_format($subtotal, 0, ',', '.'),
                'qty' => $item['qty'],
                'gambar_url' => !empty($item['gambar']) ? asset('storage/' . $item['gambar']) : null,
                'qty_url' => route('toko.qty', $id),
                'remove_url' => route('toko.remove', $id),
            ];
        }

        return [
            'status' => 'success',
            'message' => $message,
            'cartCount' => $count,
            'cartTotal' => number_format($total, 0, ',', '.'),
            'items' => $items,
        ];
    }

    public function index(Request $request)
    {
        $query = Obat::withAvg('ulasans', 'rating')->withCount('ulasans');

        if ($request->filled('q')) {
            $keyword = Str::limit(trim($request->q), 80, '');
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_obat', 'like', '%' . $keyword . '%')
                  ->orWhere('deskripsi', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->sort === 'termurah') {
            $query->orderBy('harga', 'asc');
        } elseif ($request->sort === 'termahal') {
            $query->orderBy('harga', 'desc');
        } else {
            $query->latest();
        }

        $obats = $query->get();
        $cart = session()->get('cart', []);
        $cartCount = array_sum(array_column($cart, 'qty'));
        $cartTotal = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);
        $favoritIds = session()->get('favorit', []);

        return view('toko.index', compact('obats', 'cart', 'cartCount', 'cartTotal', 'favoritIds'));
    }

    public function liveSearch(Request $request)
    {
        $q = Str::limit(trim($request->get('q', '')), 60, '');
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $results = Obat::where('nama_obat', 'like', '%' . $q . '%')
            ->orWhere('deskripsi', 'like', '%' . $q . '%')
            ->take(5)
            ->get()
            ->map(function ($obat) {
                return [
                    'id' => $obat->id,
                    'nama_obat' => ucwords($obat->nama_obat),
                    'kategori' => $obat->kategori,
                    'harga' => number_format($obat->harga, 0, ',', '.'),
                    'stok' => $obat->stok,
                    'gambar' => $obat->gambar ? asset('storage/' . $obat->gambar) : null,
                    'url' => route('toko.show', $obat->id),
                ];
            });

        return response()->json($results);
    }

    public function show(Obat $obat)
    {
        $obat->load(['ulasans' => fn($q) => $q->latest()]);
        $avgRating = $obat->ulasans->avg('rating') ?? 0;
        $terkait = Obat::where('kategori', $obat->kategori)
            ->where('id', '!=', $obat->id)
            ->take(4)
            ->get();
        $cartCount = array_sum(array_column(session('cart', []), 'qty'));

        return view('toko.show', compact('obat', 'avgRating', 'terkait', 'cartCount'));
    }

    public function storeUlasan(Request $request, Obat $obat)
    {
        $data = $request->validate([
            'nama' => 'required|string|min:2|max:80',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:3|max:500',
        ]);

        $obat->ulasans()->create([
            'nama' => strip_tags($data['nama']),
            'rating' => $data['rating'],
            'komentar' => strip_tags($data['komentar']),
        ]);

        return back()->with('success', 'Terima kasih, ulasan Anda telah dipublikasikan.');
    }

    public function favorit()
    {
        $favoritIds = session()->get('favorit', []);
        $obats = Obat::withAvg('ulasans', 'rating')
            ->withCount('ulasans')
            ->whereIn('id', $favoritIds)
            ->get();
        $cartCount = array_sum(array_column(session('cart', []), 'qty'));

        return view('toko.favorit', compact('obats', 'cartCount'));
    }

    public function toggleFavorit(Request $request, Obat $obat)
    {
        $favorit = session()->get('favorit', []);

        if (in_array($obat->id, $favorit)) {
            $favorit = array_values(array_diff($favorit, [$obat->id]));
            $saved = false;
            $msg = ucwords($obat->nama_obat) . ' dihapus dari favorit.';
        } else {
            $favorit[] = $obat->id;
            $saved = true;
            $msg = ucwords($obat->nama_obat) . ' disimpan ke favorit.';
        }

        session()->put('favorit', $favorit);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'saved' => $saved,
                'count' => count($favorit),
                'message' => $msg,
            ]);
        }

        return back()->with('success', $msg);
    }

    public function addToCart(Request $request, Obat $obat)
    {
        if ($obat->stok < 1) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Stok obat habis.'], 422);
            }
            return back()->with('error', 'Stok obat habis.');
        }

        $qtyTambah = max(1, min(100, (int) $request->input('qty', 1)));
        $cart = session()->get('cart', []);

        if (isset($cart[$obat->id])) {
            if ($cart[$obat->id]['qty'] + $qtyTambah > $obat->stok) {
                if ($request->expectsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Jumlah melebihi stok tersedia.'], 422);
                }
                return back()->with('error', 'Jumlah di keranjang melebihi stok tersedia.');
            }
            $cart[$obat->id]['qty'] += $qtyTambah;
        } else {
            if ($qtyTambah > $obat->stok) {
                if ($request->expectsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Jumlah melebihi stok tersedia.'], 422);
                }
                return back()->with('error', 'Jumlah melebihi stok tersedia.');
            }
            $cart[$obat->id] = [
                'nama_obat' => $obat->nama_obat,
                'harga' => $obat->harga,
                'gambar' => $obat->gambar,
                'qty' => $qtyTambah,
            ];
        }

        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json($this->cartPayload($cart, ucwords($obat->nama_obat) . ' ditambahkan ke keranjang.'));
        }

        return back()->with('success', $obat->nama_obat . ' ditambahkan ke keranjang.');
    }

    public function updateQty(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        if (!isset($cart[$id])) {
            return $request->expectsJson()
                ? response()->json($this->cartPayload($cart))
                : back();
        }

        $obat = Obat::find($id);
        if ($request->action === 'plus') {
            if ($obat && $cart[$id]['qty'] < $obat->stok) {
                $cart[$id]['qty']++;
            } else {
                if ($request->expectsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Stok tidak mencukupi.'], 422);
                }
                return back()->with('error', 'Stok tidak mencukupi.');
            }
        } elseif ($request->action === 'minus') {
            $cart[$id]['qty']--;
            if ($cart[$id]['qty'] <= 0) {
                unset($cart[$id]);
            }
        }

        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json($this->cartPayload($cart));
        }

        return back();
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);
        return view('toko.cart', compact('cart', 'total'));
    }

    public function removeFromCart(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json($this->cartPayload($cart, 'Item dihapus dari keranjang.'));
        }

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('toko.index');
        }

        $request->validate([
            'nama_pembeli' => 'required|string|min:2|max:100',
            'no_hp' => ['required', 'string', 'regex:/^[0-9+\-\s]{9,20}$/'],
            'kurir' => 'required|in:Ambil di Apotek,Kurir Instan (1 Jam),Pengiriman Reguler',
            'alamat' => 'nullable|string|max:500',
            'metode_pembayaran' => 'required|in:COD (Bayar di Tempat),Transfer Bank,QRIS',
        ]);

        if ($request->kurir !== 'Ambil di Apotek' && !$request->filled('alamat')) {
            return back()->withErrors(['alamat' => 'Alamat lengkap wajib diisi untuk layanan antar.'])->withInput();
        }

        $ongkirMap = [
            'Ambil di Apotek' => 0,
            'Kurir Instan (1 Jam)' => 10000,
            'Pengiriman Reguler' => 7000,
        ];

        $ongkir = $ongkirMap[$request->kurir] ?? 0;

        try {
            // DB Transaction + Row Locking: anti race-condition & harga selalu diambil dari DB
            $transaksi = DB::transaction(function () use ($cart, $request, $ongkir) {
                $subtotal = 0;
                $verifiedItems = [];

                foreach ($cart as $id => $item) {
                    $qty = max(1, (int) $item['qty']);
                    $obat = Obat::where('id', $id)->lockForUpdate()->first();

                    if (!$obat || $obat->stok < $qty) {
                        $nama = $obat ? $obat->nama_obat : 'Produk';
                        throw new \RuntimeException("Stok {$nama} tidak mencukupi. Silakan periksa keranjang Anda.");
                    }

                    $obat->decrement('stok', $qty);
                    $subtotal += $obat->harga * $qty;

                    $verifiedItems[] = [
                        'nama_obat' => $obat->nama_obat,
                        'harga' => $obat->harga,
                        'qty' => $qty,
                    ];
                }

                // Kode acak kriptografis 8 karakter (anti tebak/IDOR)
                do {
                    $kode = 'APT-' . date('Ymd') . '-' . Str::upper(Str::random(8));
                } while (Transaksi::where('kode_transaksi', $kode)->exists());

                return Transaksi::create([
                    'kode_transaksi' => $kode,
                    'nama_pembeli' => strip_tags($request->nama_pembeli),
                    'no_hp' => strip_tags($request->no_hp),
                    'alamat' => $request->kurir === 'Ambil di Apotek'
                        ? 'Ambil langsung di Apotek Sehat'
                        : strip_tags($request->alamat),
                    'metode_pembayaran' => $request->metode_pembayaran,
                    'kurir' => $request->kurir,
                    'ongkir' => $ongkir,
                    'total_harga' => $subtotal + $ongkir,
                    'status' => 'Menunggu',
                    'items' => $verifiedItems,
                ]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        session()->forget('cart');

        return redirect()->route('toko.invoice', $transaksi->kode_transaksi);
    }

    public function invoice($kode)
    {
        $transaksi = Transaksi::where('kode_transaksi', $kode)->firstOrFail();
        return view('toko.invoice', compact('transaksi'));
    }

    public function lacak(Request $request)
    {
        $transaksi = null;
        $notFound = false;

        if ($request->filled('kode')) {
            $kode = Str::upper(trim($request->kode));
            $transaksi = Transaksi::where('kode_transaksi', $kode)->first();
            if (!$transaksi) {
                $notFound = true;
            }
        }

        return view('toko.lacak', compact('transaksi', 'notFound'));
    }

    public function resep()
    {
        return view('toko.resep');
    }

    public function storeResep(Request $request)
    {
        $data = $request->validate([
            'nama_pasien' => 'required|string|min:2|max:100',
            'no_hp' => ['required', 'string', 'regex:/^[0-9+\-\s]{9,20}$/'],
            'catatan' => 'nullable|string|max:500',
            'foto_resep' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        // Simpan ke storage PRIVATE (local disk), bukan public!
        $path = $request->file('foto_resep')->store('private_resep', 'local');

        Resep::create([
            'nama_pasien' => strip_tags($data['nama_pasien']),
            'no_hp' => strip_tags($data['no_hp']),
            'catatan' => isset($data['catatan']) ? strip_tags($data['catatan']) : null,
            'foto_resep' => $path,
            'status' => 'Menunggu',
        ]);

        return back()->with('success', 'Resep dokter berhasil dikirim secara aman. Apoteker kami akan segera menghubungi Anda via WhatsApp.');
    }
}