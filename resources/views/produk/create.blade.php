@extends('layouts.main', ['title' => 'Produk'])

@section('title-content')
    <i class="fas fa-box-open mr-2"></i>
    Produk
@endsection

@section('content')
<div class="row">
    <div class="col-xl-4 col-lg-6">
        <form method="POST" action="{{ route('produk.storeMultiple') }}" class="card card-orange card-outline">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Buat Produk Baru</h3>
                <button type="button" class="btn btn-success btn-sm" id="addRow">
                    <i class="fas fa-plus"></i> Tambah Produk
                </button>
            </div>

            <div class="card-body" id="produkContainer">
                @csrf

                {{-- Template Produk --}}
                <div class="produk-item border p-3 mb-3 rounded position-relative">
                    <button type="button" class="btn btn-danger btn-sm position-absolute btn-remove" style="top:10px; right:10px;">
                        <i class="fas fa-trash"></i>
                    </button>

                    <div class="form-group">
                        <label>Kode Produk</label>
                        <x-input name="produk[0][kode_produk]" type="text" />
                    </div>

                    <div class="form-group">
                        <label>Nama Produk</label>
                        <x-input name="produk[0][nama_produk]" type="text" />
                    </div>

                    <div class="form-group">
                        <label>Harga Produk</label>
                        <x-input name="produk[0][harga]" type="text" />
                    </div>

                    <div class="form-group">
                        <label>Diskon</label>
                        <x-input name="produk[0][diskon]" type="text" />
                    </div>

                    <div class="form-group">
                        <label>Kategori</label>
                        <x-select name="produk[0][kategori_id]" :options="$kategoris" />
                    </div>
                </div>
            </div>

            <div class="card-footer form-inline">
                <button type="submit" class="btn btn-primary">Simpan Produk</button>
                <a href="{{ route('produk.index') }}" class="btn btn-secondary ml-auto">Batal</a>
            </div>
        </form>
    </div>
</div>

{{-- Script Tambah / Hapus Produk --}}
@push('scripts')
<script>
    let index = 1;
    const container = document.getElementById('produkContainer');
    const addRowBtn = document.getElementById('addRow');

    addRowBtn.addEventListener('click', function () {
        let firstItem = container.querySelector('.produk-item');
        let newItem = firstItem.cloneNode(true);

        // Update name agar sesuai index baru
        newItem.querySelectorAll('input, select').forEach(el => {
            let name = el.getAttribute('name');
            if (name) {
                el.setAttribute('name', name.replace(/\d+/, index));
                el.value = '';
            }
        });

        container.appendChild(newItem);
        index++;
    });

    container.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove')) {
            if (container.querySelectorAll('.produk-item').length > 1) {
                e.target.closest('.produk-item').remove();
            } else {
                alert("Minimal 1 produk harus ada!");
            }
        }
    });
</script>
@endpush
@endsection
