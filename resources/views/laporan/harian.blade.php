@extends('layouts.laporan', ['title' => 'Laporan Harian'])

@section('content')
    <h1 class="text-center">Laporan Harian</h1>
    <p>
        Tanggal : {{ date('d/m/Y', strtotime(request()->tanggal)) }}
    </p>

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>No</th>
                <th>No. Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Kasir</th>
                <th>Status</th>
                <th>Waktu</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($penjualan as $key => $row)
                @php
                    $isBatal = strtolower($row->status) === 'batal';
                @endphp
                <tr @if($isBatal) style="color: red;" @endif>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row->nomor_transaksi }}</td>
                    <td>{{ $row->nama_pelanggan }}</td>
                    <td>{{ $row->nama_kasir }}</td>
                    <td>{{ ucwords($row->status) }}</td>
                    <td>{{ date('H:i:s', strtotime($row->tanggal)) }}</td>
                    <td>
                        @if($isBatal)
                            ({{ number_format($row->total, 0, ',', '.') }})
                        @else
                            {{ number_format($row->total, 0, ',', '.') }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>

        @php
            $totalSukses = $penjualan->filter(function ($row) {
                return strtolower($row->status) !== 'batal';
            })->sum('total');
        @endphp

        <tfoot>
            <tr class="bg-light font-weight-bold">
                <td colspan="6" class="text-left">Jumlah Total</td>
                <td>Rp {{ number_format($totalSukses, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
@endsection
