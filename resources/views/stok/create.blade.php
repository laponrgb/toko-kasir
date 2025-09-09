@extends('layouts.main', ['title' => 'Stok'])

@section('title-content')
    <i class="fas fa-pallet mr-2"></i>
    Stok
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ route('stok.storeMultiple') }}" class="card card-orange card-outline">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Tambah Stok Barang</h3>
                <button type="button" class="btn btn-success btn-sm" id="addRow">
                    <i class="fas fa-plus"></i> Tambah Stok
                </button>
            </div>

            <div class="card-body">
                @csrf
                <div id="stokContainer" class="d-flex flex-wrap">
                    <div class="stok-item border p-3 m-2 rounded position-relative bg-light" style="width: 280px;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute btn-remove" style="top:10px; right:10px;">
                            <i class="fas fa-trash"></i>
                        </button>

                        <div class="form-group">
                            <label>Nama Produk</label>
                            <div class="input-group">
                                <input type="text" class="form-control nama-produk" disabled>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary btn-cari" data-toggle="modal" data-target="#modalCari">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="stok[0][produk_id]" class="produk-id">
                        </div>

                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" name="stok[0][jumlah]" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Nama Suplier</label>
                            <input type="text" name="stok[0][nama_suplier]" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer form-inline">
                <button type="submit" class="btn btn-primary">Simpan Stok</button>
                <button type="button" class="btn btn-warning ml-2" id="clearForm">Clear Form</button>
                <a href="{{ route('stok.index') }}" class="btn btn-secondary ml-auto">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

{{-- Modal cari produk --}}
@push('modals')
<div class="modal fade" id="modalCari" data-backdrop="static" data-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cari Produk</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="formSearch" method="get" class="input-group mb-3">
                    <input type="text" class="form-control" id="search" placeholder="Cari nama produk...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </form>
                <table class="table table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="resultProduk"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endpush

{{-- Script --}}
@push('scripts')
<script>
    let index = 1;
    const container = document.getElementById('stokContainer');
    const addRowBtn = document.getElementById('addRow');
    const clearBtn = document.getElementById('clearForm');
    let activeInput = null;

addRowBtn.addEventListener('click', function () {
    let firstItem = container.querySelector('.stok-item');
    let newItem = firstItem.cloneNode(true);

    newItem.querySelectorAll('input').forEach(el => {
        let name = el.getAttribute('name');
        if (name) {
            el.setAttribute('name', name.replace(/\d+/, index));
        }
        el.value = '';
    });

    container.appendChild(newItem);
    index++;
});


    container.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove')) {
            let items = container.querySelectorAll('.stok-item');
            if (items.length > 1) {
                e.target.closest('.stok-item').remove();
            } else {
                alert("Minimal 1 stok harus ada!");
            }
        }
    });

    clearBtn.addEventListener('click', function () {
        container.innerHTML = '';
        index = 0;
        addRowBtn.click();
    });

    $(function () {
        $('#formSearch').submit(function (e) {
            e.preventDefault();
            let search = $('#search').val();
            if (search.length >= 2) fetchProduk(search);
        });

        $(document).on('click', '.btn-cari', function () {
            activeInput = $(this).closest('.stok-item');
        });

        function fetchProduk(search) {
            let url = "{{ route('stok.produk') }}?search=" + encodeURIComponent(search);
            $.getJSON(url, function (result) {
                $('#resultProduk').html('');
                result.forEach((produk) => {
                    let row = `<tr>
                        <td>${produk.kode_produk}</td>
                        <td>${produk.nama_produk}</td>
                        <td>${produk.nama_kategori}</td>
                        <td class="text-right">
                            <button type="button" class="btn btn-xs btn-success"
                                onclick="addProduk(${produk.id}, '${produk.nama_produk}')">
                                Pilih
                            </button>
                        </td>
                    </tr>`;
                    $('#resultProduk').append(row);
                });
            });
        }
    });

    function addProduk(id, nama) {
        if (activeInput) {
            activeInput.find('.nama-produk').val(nama);
            activeInput.find('.produk-id').val(id);
        }
        $('#modalCari').modal('hide');
    }
</script>
@endpush
