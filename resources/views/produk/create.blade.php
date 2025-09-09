@extends('layouts.main', ['title' => 'Produk'])

@section('title-content')
    <i class="fas fa-box-open mr-2"></i>
    Produk
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ route('produk.storeMultiple') }}" class="card card-orange card-outline">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Buat Produk Baru</h3>
                <button type="button" class="btn btn-success btn-sm" id="addRow">
                    <i class="fas fa-plus"></i> Tambah Produk
                </button>
            </div>

            <div class="card-body">
                @csrf
                <div id="produkContainer" class="d-flex flex-wrap">
                    <div class="produk-item border p-3 m-2 rounded position-relative bg-light" style="width: 280px;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute btn-remove" style="top:10px; right:10px;">
                            <i class="fas fa-trash"></i>
                        </button>
                        
                        <div class="form-group">
                            <label>Kode Produk</label>
                            <input type="text" class="form-control" value="{{ $nextCode }}" disabled>
                            <input type="hidden" name="produk[0][kode_produk]" value="{{ $nextCode }}">
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
            </div>

            <div class="card-footer form-inline">
                <button type="submit" class="btn btn-primary">Simpan Produk</button>
                <button type="button" class="btn btn-warning ml-2" id="clearForm">Clear Form</button>
                <a href="{{ route('produk.index') }}" class="btn btn-secondary ml-auto">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let index = 1;
    const container = document.getElementById('produkContainer');
    const addRowBtn = document.getElementById('addRow');
    const clearBtn = document.getElementById('clearForm');
    let nextCodeNumber = parseInt("{{ intval(substr($nextCode, 1)) }}");
    let baseCodeNumber = nextCodeNumber;

    addRowBtn.addEventListener('click', function () {
        let firstItem = container.querySelector('.produk-item');
        let newItem = firstItem.cloneNode(true);

        newItem.querySelectorAll('input, select').forEach(el => {
            let name = el.getAttribute('name');
            if (name) {
                el.setAttribute('name', name.replace(/\d+/, index));
                el.value = '';
            }
        });

        nextCodeNumber++;
        let newCode = 'P' + String(nextCodeNumber).padStart(4, '0');
        newItem.querySelector('input[disabled]').value = newCode;
        newItem.querySelector('input[type="hidden"][name*="kode_produk"]').value = newCode;

        container.appendChild(newItem);
        index++;
    });

    container.addEventListener('click', function (e) {
        if (e.target.closest('.btn-remove')) {
            let items = container.querySelectorAll('.produk-item');
            if (items.length > 1) {
                e.target.closest('.produk-item').remove();
                nextCodeNumber--;
                index--;

                let allItems = container.querySelectorAll('.produk-item');
                allItems.forEach((item, idx) => {
                    let kodeBaru = 'P' + String(baseCodeNumber + idx).padStart(4, '0');
                    let kodeInput = item.querySelector('input[disabled]');
                    let kodeHidden = item.querySelector('input[type="hidden"][name*="kode_produk"]');
                    if (kodeInput) kodeInput.value = kodeBaru;
                    if (kodeHidden) {
                        kodeHidden.value = kodeBaru;
                        kodeHidden.setAttribute('name', `produk[${idx}][kode_produk]`);
                    }

                    item.querySelectorAll('input, select').forEach(el => {
                        let oldName = el.getAttribute('name');
                        if (oldName) {
                            el.setAttribute('name', oldName.replace(/\d+/, idx));
                        }
                    });
                });
            } else {
                alert("Minimal 1 produk harus ada!");
            }
        }
    });

    clearBtn.addEventListener('click', function () {
        container.innerHTML = '';
        index = 1;
        nextCodeNumber = baseCodeNumber;

        let newItem = document.createElement('div');
        newItem.className = "produk-item border p-3 m-2 rounded position-relative bg-light";
        newItem.style.width = "280px";
        newItem.innerHTML = `
            <button type="button" class="btn btn-danger btn-sm position-absolute btn-remove" style="top:10px; right:10px;">
                <i class="fas fa-trash"></i>
            </button>
            <div class="form-group">
                <label>Kode Produk</label>
                <input type="text" class="form-control" value="P${String(nextCodeNumber).padStart(4, '0')}" disabled>
                <input type="hidden" name="produk[0][kode_produk]" value="P${String(nextCodeNumber).padStart(4, '0')}">
            </div>
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="produk[0][nama_produk]" class="form-control">
            </div>
            <div class="form-group">
                <label>Harga Produk</label>
                <input type="text" name="produk[0][harga]" class="form-control">
            </div>
            <div class="form-group">
                <label>Diskon</label>
                <input type="text" name="produk[0][diskon]" class="form-control">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="produk[0][kategori_id]" class="form-control">
                    <option value="">Pilih Kategori:</option>
                    @foreach($kategoris as $kategori)
                        @if($loop->first) @continue @endif
                        <option value="{{ $kategori[0] }}">{{ $kategori[1] }}</option>
                    @endforeach
                </select>
            </div>
        `;
        container.appendChild(newItem);
    });
</script>
@endpush

@endsection
