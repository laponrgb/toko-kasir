<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PelangganController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home')->middleware('auth');

Route::view('login', 'auth.login')
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthController::class, 'login'])
    ->middleware('guest');

Route::post('logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::singleton('profile', ProfileController::class);
    });

Route::middleware('auth')->group(function () {
    Route::resource('user', UserController::class)->middleware('can:admin');
    });

Route::middleware('auth')->group(function () {
    Route::resource('pelanggan', PelangganController::class);
    });

Route::middleware('auth')->group(function () {
    Route::resource('kategori', KategoriController::class);
    });

Route::middleware('auth')->group(function () {
    Route::resource('produk', ProdukController::class);
    });

    Route::middleware('auth')->group(function () {
    Route::get('stok/produk', [StokController::class, 'produk'])->name('stok.produk');
    Route::resource('stok', StokController::class)->only(['index', 'create', 'store', 'destroy']);
});
