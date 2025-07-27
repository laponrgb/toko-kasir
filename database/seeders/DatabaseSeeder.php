<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penjualan;
use App\Models\DetilPenjualan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
{
    \App\Models\User::create([
        'nama'     => 'Administrator',
        'username' => 'admin',
        'role'     => 'admin',
        'password' => bcrypt('password'),
    ]);

    \App\Models\User::create([
        'nama'     => 'Petugas',
        'username' => 'petugas',
        'role'     => 'petugas',
        'password' => bcrypt('password'),
    ]);

    \App\Models\Pelanggan::create([
        'nama' => 'Dodo Sidodo',
        'alamat' => 'Padaherang',
        'nomor_tlp' => '08212345678'
    ]);
    
    \App\Models\Pelanggan::create([
        'nama' => 'Naufal',
        'alamat' => 'Sindanghayu',
        'nomor_tlp' => '08287654321'
    ]);

    \App\Models\Kategori::create([
        'nama_kategori' => 'Makanan',
    ]);

    \App\Models\Kategori::create([
        'nama_kategori' => 'Minuman',
    ]);

    \App\Models\Produk::create([
    'kategori_id' => 1,
    'kode_produk' => '1001',
    'nama_produk' => 'Chiki Taro',
    'harga' => 5000,
    ]);

    \App\Models\Produk::create([
    'kategori_id' => 2,
    'kode_produk' => '1002',
    'nama_produk' => 'Le Mineral',
    'harga' => 3500,
    ]);

    \App\Models\Stok::create([
    'produk_id' => 1,
    'nama_suplier' => 'Toko Haji Usman',
    'jumlah' => 250,
    'tanggal' => date('Y-m-d', strtotime('-1 week')),
    ]);

    \App\Models\Stok::create([
    'produk_id' => 2,
    'nama_suplier' => 'Agen Le Mineral',
    'jumlah' => 100,
    'tanggal' => date('Y-m-d', strtotime('-1 week')),
    ]);

    \App\Models\Produk::where('id', 1)->update([
    'stok' => 250,
    ]);

    \App\Models\Produk::where('id', 2)->update([
    'stok' => 100,
    ]);

// Penjualan 1
Penjualan::create([
    'user_id' => 1,
    'pelanggan_id' => 1,
    'nomor_transaksi' => date('Ymd') . '@ee1',
    'tanggal' => date('Y-m-d H:i:s'),
    'subtotal' => 8500,
    'pajak' => 850,
    'total' => 9350,
    'tunai' => 10000,
    'kembalian' => 650,
]);

// Penjualan 2
Penjualan::create([
    'user_id' => 2,
    'pelanggan_id' => 2,
    'nomor_transaksi' => date('Ymd') . 'eee2',
    'tanggal' => date('Y-m-d H:i:s'),
    'subtotal' => 13500,
    'pajak' => 1350,
    'total' => 14850,
    'tunai' => 20000,
    'kembalian' => 5150,
]);

// Detil Penjualan untuk Penjualan ID 1
DetilPenjualan::create([
    'penjualan_id' => 1,
    'produk_id' => 1,
    'jumlah' => 1,
    'harga_produk' => 5000,
    'subtotal' => 5000,
]);

DetilPenjualan::create([
    'penjualan_id' => 1,
    'produk_id' => 2,
    'jumlah' => 1,
    'harga_produk' => 3500,
    'subtotal' => 3500,
]);

// Detil Penjualan untuk Penjualan ID 2
DetilPenjualan::create([
    'penjualan_id' => 2,
    'produk_id' => 1,
    'jumlah' => 2,
    'harga_produk' => 5000,
    'subtotal' => 10000,
]);

DetilPenjualan::create([
    'penjualan_id' => 2,
    'produk_id' => 2,
    'jumlah' => 1,
    'harga_produk' => 3500,
    'subtotal' => 3500,
]);


}

}
