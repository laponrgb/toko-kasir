@extends('layouts.laporan', ['title' => 'Laporan Bulanan'])

@section('content')
    <h1 class="text-center">Laporan Bulanan</h1>
    <p>
        Bulan : {{ $bulan }} {{ request()->tahun }}
    </p>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jumlah Transaksi</th>
                <th>Transaksi Sukses</th>
                <th>Transaksi Dibatalkan</th>
                <th>Total Sukses</th>
                <th>Total Dibatalkan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($penjualan as $key => $row)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row->tgl }}</td>
                    <td>{{ $row->jumlah_transaksi }}</td>
                    <td>{{ $row->transaksi_sukses }}</td>
                    <td>{{ $row->transaksi_batal }}</td>
                    <td>{{ number_format($row->total_sukses, 0, ',', '.') }}</td>
                    <td>{{ number_format($row->total_batal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <th colspan="2">Jumlah Total</th>
                <th>{{ $penjualan->sum('jumlah_transaksi') }}</th>
                <th>{{ $penjualan->sum('transaksi_sukses') }}</th>
                <th>{{ $penjualan->sum('transaksi_batal') }}</th>
                <th>{{ number_format($penjualan->sum('total_sukses'), 0, ',', '.') }}</th>
                <th>{{ number_format($penjualan->sum('total_batal'), 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
@endsection
