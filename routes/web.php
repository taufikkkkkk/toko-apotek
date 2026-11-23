<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\AuthController;

// Halaman Pembeli
Route::get('/', [TokoController::class, 'index'])->name('toko.index');
Route::get('/api/cari', [TokoController::class, 'liveSearch'])->middleware('throttle:60,1')->name('toko.liveSearch');
Route::get('/produk/{obat}', [TokoController::class, 'show'])->name('toko.show');
Route::post('/produk/{obat}/ulasan', [TokoController::class, 'storeUlasan'])->middleware('throttle:5,1')->name('toko.ulasan.store');

Route::get('/favorit', [TokoController::class, 'favorit'])->name('toko.favorit');
Route::post('/favorit/toggle/{obat}', [TokoController::class, 'toggleFavorit'])->middleware('throttle:30,1')->name('toko.favorit.toggle');

Route::get('/keranjang', [TokoController::class, 'cart'])->name('toko.cart');
Route::post('/keranjang/tambah/{obat}', [TokoController::class, 'addToCart'])->middleware('throttle:30,1')->name('toko.add');
Route::post('/keranjang/qty/{id}', [TokoController::class, 'updateQty'])->middleware('throttle:30,1')->name('toko.qty');
Route::delete('/keranjang/hapus/{id}', [TokoController::class, 'removeFromCart'])->name('toko.remove');

Route::post('/checkout', [TokoController::class, 'checkout'])->middleware('throttle:5,1')->name('toko.checkout');
Route::get('/invoice/{kode}', [TokoController::class, 'invoice'])->middleware('throttle:20,1')->name('toko.invoice');
Route::get('/lacak', [TokoController::class, 'lacak'])->middleware('throttle:15,1')->name('toko.lacak');

Route::get('/resep', [TokoController::class, 'resep'])->name('toko.resep');
Route::post('/resep', [TokoController::class, 'storeResep'])->middleware('throttle:5,1')->name('toko.resep.store');

// Login & Logout Admin
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Admin (Wajib Login)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::resource('obat', ObatController::class);
    Route::patch('/transaksi/{transaksi}/status', [ObatController::class, 'updateStatus'])->name('transaksi.status');
    Route::get('/transaksi/export', [ObatController::class, 'exportTransaksi'])->name('transaksi.export');
    Route::patch('/resep/{resep}/status', [ObatController::class, 'updateStatusResep'])->name('resep.status');
    Route::get('/resep/{resep}/foto', [ObatController::class, 'lihatResep'])->name('resep.foto');
});