<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.form');
    }

    public function harian(Request $request)
    {
        $penjualan = Penjualan::join('users', 'users.id', '=', 'penjualans.user_id')
            ->join('pelanggans', 'pelanggans.id', '=', 'penjualans.pelanggan_id')
            ->whereDate('tanggal', $request->tanggal)
            ->select(
                'penjualans.*',
                'pelanggans.nama as nama_pelanggan',
                'users.nama as nama_kasir'
            )
            ->orderBy('tanggal') // biar urut sesuai jam transaksi
            ->get();

        return view('laporan.harian', [
            'penjualan' => $penjualan
        ]);
    }

    public function bulanan(Request $request)
    {
        $penjualan = Penjualan::select(
            DB::raw("DATE_FORMAT(tanggal, '%d/%m/%Y') as tgl"),
            DB::raw("SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as transaksi_sukses"),
            DB::raw("SUM(CASE WHEN status = 'batal' THEN 1 ELSE 0 END) as transaksi_batal"),
            DB::raw("SUM(CASE WHEN status IN ('selesai','batal') THEN 1 ELSE 0 END) as jumlah_transaksi"),
            DB::raw("SUM(CASE WHEN status = 'selesai' THEN total ELSE 0 END) as total_sukses"),
            DB::raw("SUM(CASE WHEN status = 'batal' THEN total ELSE 0 END) as total_batal")
        )
        ->whereMonth('tanggal', $request->bulan)
        ->whereYear('tanggal', $request->tahun)
        ->groupBy('tgl')
        ->get();


        $nama_bulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei',
            'Juni', 'Juli', 'Agustus', 'September', 'Oktober',
            'November', 'Desember'
        ];

        $bulan = isset($nama_bulan[$request->bulan - 1])
            ? $nama_bulan[$request->bulan - 1]
            : null;

        return view('laporan.bulanan', [
            'penjualan' => $penjualan,
            'bulan' => $bulan
        ]);
    }

    
}
