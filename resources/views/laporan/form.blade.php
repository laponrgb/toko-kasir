@extends('layouts.main', ['title' => 'Laporan'])

@section('title-content')
    <i class="fas fa-print mr-2"></i> Laporan
@endsection

@section('content')
<div class="row">

    {{-- Laporan Harian --}}
    <div class="col-lg-6 col-xl-4">
        <form target="_blank" method="GET" action="{{ route('laporan.harian') }}" class="card card-orange card-outline">
            <div class="card-header">
                <h3 class="card-title">Buat Laporan Harian</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Tanggal</label>
                    {{-- Default isi hari ini --}}
                    <input type="date" name="tanggal" class="form-control"
                           value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-print mr-2"></i> Cetak
                </button>
            </div>
        </form>
    </div>

    {{-- Laporan Bulanan --}}
    <div class="col-lg-6 col-xl-4">
        <form target="_blank" method="GET" action="{{ route('laporan.bulanan') }}" class="card card-orange card-outline">
            <div class="card-header">
                <h3 class="card-title">Buat Laporan Bulanan</h3>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col">
                        <label>Bulan</label>
                        @php
                            $pilihan = [
                                'Pilih Bulan:', 'Januari', 'Februari', 'Maret', 'April', 'Mei',
                                'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                            ];
                            $bulanSekarang = date('n'); // 1-12
                        @endphp
                        <select name="bulan" class="form-control">
                            @foreach ($pilihan as $key => $value)
                                <option value="{{ $key ? $key : '' }}" 
                                    {{ $key == $bulanSekarang ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col">
                        <label>Tahun</label>
                        @php
                            $tahunSekarang = date('Y');
                            $max = $tahunSekarang - 5;
                        @endphp
                        <select name="tahun" class="form-control">
                            <option value="">Pilih Tahun:</option>
                            @for ($tahun = $tahunSekarang; $tahun > $max; $tahun--)
                                <option value="{{ $tahun }}" 
                                    {{ $tahun == $tahunSekarang ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-print mr-2"></i> Cetak
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
