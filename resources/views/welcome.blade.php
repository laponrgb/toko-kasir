@extends('layouts.main', ['title' => 'Home'])

@section('title-content')
    <i class="fas fa-home mr-2"></i> Home
@endsection

@section('content')
<div class="row">
    @can('admin')
        <x-box title="User" icon="fas fa-user-tie" background="bg-danger"
            :route="route('user.index')" :jumlah="$user->jumlah" />
        <x-box title="Kategori" icon="fas fa-list" background="bg-warning"
            :route="route('kategori.index')" :jumlah="$kategori->jumlah" />
    @endcan

    <x-box title="Pelanggan" icon="fas fa-users" background="bg-primary"
        :route="route('pelanggan.index')" :jumlah="$pelanggan->jumlah" />
    <x-box title="Produk" icon="fas fa-box-open" background="bg-success"
        :route="route('produk.index')" :jumlah="$produk->jumlah" />
</div>

<div class="card">
    <div class="card-header d-flex align-items-center">
        <span>Grafik Penjualan</span>
        <form action="{{ route('home') }}" method="GET" class="form-inline ml-auto">
            <label for="bulan" class="mr-2">Bulan:</label>
            <select name="bulan" id="bulan" class="form-control" onchange="this.form.submit()">
                @foreach ($bulanList as $key => $namaBulan)
                    <option value="{{ $key }}" {{ request('bulan', date('m')) == $key ? 'selected' : '' }}>
                        {{ $namaBulan }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="card-body" style="height:400px;"> {{-- tinggi tetap supaya tidak gepeng --}}
        <canvas id="myChart"></canvas>
    </div>
</div>

@endsection

@push('scripts')
<script>
    var ctx = document.getElementById('myChart').getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! $cart['labels'] !!},
            datasets: [{
                label: "{{ $cart['label'] }}",
                data: {!! $cart['data'] !!},
                borderWidth: 3,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // biar proporsional sesuai height div
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>
@endpush
