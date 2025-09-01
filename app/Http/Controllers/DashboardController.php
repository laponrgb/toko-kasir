<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = User::selectRaw('count(*) as jumlah')->first();
        $pelanggan = Pelanggan::selectRaw('count(*) as jumlah')->first();
        $kategori = Kategori::selectRaw('count(*) as jumlah')->first();
        $produk = Produk::selectRaw('count(*) as jumlah')->first();

        // list nama bulan
        $nama_bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei',
            6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // ambil bulan dari request, default bulan sekarang
        $bulan = $request->get('bulan', date('m'));
        $tahun = $request->get('tahun', date('Y'));

        // query penjualan berdasarkan bulan & tahun dipilih
        $penjualan = Penjualan::select(
                DB::raw('SUM(total) as jumlah_total'),
                DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') as tgl")
            )
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->groupBy('tgl')
            ->orderBy('tanggal', 'asc')
            ->get();

        $label = 'Transaksi ' . $nama_bulan[(int)$bulan] . ' ' . $tahun;

        $labels = [];
        $data = [];

        foreach ($penjualan as $row) {
            $labels[] = substr($row->tgl, 0, 2); // ambil tanggal (dd)
            $data[] = $row->jumlah_total;
        }

        return view('welcome', [
            'user' => $user,
            'pelanggan' => $pelanggan,
            'kategori' => $kategori,
            'produk' => $produk,
            'bulanList' => $nama_bulan, // untuk dropdown
            'cart' => [
                'label' => $label,
                'labels' => json_encode($labels),
                'data' => json_encode($data)
            ]
        ]);
    }
}
