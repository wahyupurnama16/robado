<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(DashboardController::class)->name('dashboard.')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', 'index')->name('index');
    });
    Route::get('/dashboard-guest', 'index')->name('guest');
    Route::get('/aturan-berlangganan', 'aturanBerlangganan')->name('aturanBerlangganan');
    Route::get('/tentang-kami', 'tentangKami')->name('tentangKami');
});
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
Route::get('/rencana-produksi', [LaporanController::class, 'rencanaProduksi'])->name('laporan.rencanaProduksi');
Route::get('/api/laporan/produksi/{status?}', [LaporanController::class, 'apiData'])->name('api.laporan.produksi');
Route::put('/api/laporan/produksi/{id}', [LaporanController::class, 'update'])->name('api.laporan.produksi.update');

Route::controller(PemesananController::class)->name('pemesanan.')->group(function () {
    Route::post('/pemesanan', 'store')->name('store');
    Route::get('/laporan/pemesanan', 'laporanOwner')->name('laporanOwner');
    Route::get('/api/laporan/pemesanan/daily', 'getDailyReport')->name('api.laporan.pemesanan.daily');
    Route::middleware('auth')->group(function () {
        Route::post('/send-laporan-owner', 'sendLaporanOwner')->name('sendLaporanOwner');
        Route::get('/pemesanan', 'riwayat')->name('riwayat');
        Route::get('/riwayat/transaksi', 'riwayatPesanan')->name('riwayatPesanan');
        Route::get('/get/riwayat/pesanan/{id}', 'getRiwayatPesanan')->name('getRiwayatPesanan');
        Route::get('/get/riwayat/{id}', 'getRiwayat')->name('getRiwayat');
        Route::get('/get/riwayat/dashboard/{id}', 'getRiwayatDashboard')->name('getRiwayatDashboard');
        Route::get('/update/{status}/{value}/{up}', 'updateStatus')->name('updateStatus');
        Route::get('/details/{id}', 'detail')->name('detail');
        Route::post('/update/details/{id}', 'updatePesananDetails')->name('updatePesananDetails');
        Route::delete('/hapus/{id}', 'destroy')->name('destroy');
    });

});

Route::controller(UserController::class)->name('users.')->group(function () {
    Route::get('/users', 'index')->name('index');
    Route::get('/users/data', 'getData')->name('data');
    Route::post('/users', 'store')->name('store');
    Route::put('/users/{id}', 'update')->name('update');
    Route::delete('/users/{id}', 'destroy')->name('destroy');
});

Route::controller(ProduksiController::class)->name('produksi.')->group(function () {
    Route::get('/produksi', 'index')->name('index');
    Route::get('/produksi/data', 'getData')->name('data');
    Route::post('/produksi', 'store')->name('store');
    Route::put('/produksi/{id}', 'update')->name('update');
    Route::delete('/produksi/{id}', 'destroy')->name('destroy');
});

Route::controller(CartController::class)->name('cart.')->group(function () {
    Route::get('/cart', 'index')->name('index');
    Route::middleware('auth')->group(function () {
        Route::get('/getCart/{id}', 'getCart')->name('getCart');
        Route::post('/update-cart', 'updateCart');
        Route::post('/create-cart', 'createCart');
        Route::post('/sync-cart', 'syncCart');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
