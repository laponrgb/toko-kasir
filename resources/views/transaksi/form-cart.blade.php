<div class="card card-orange card-outline">
    <div class="card-body">
        <h3 class="m-0 text-right">Rp <span id="totalJumlah">0</span> ,-</h3>
    </div>
</div>

<form action="{{ route('transaksi.store') }}" method="POST" class="card card-orange card-outline">
    @csrf
    <div class="card-body">
        <p class="text-right">
            Tanggal : {{ $tanggal ?? date('d F Y') }}
        </p>

        <div class="row">
            <div class="col">
                <label>Nama Pelanggan</label>
                <input type="text" id="namaPelanggan"
                    class="form-control @error('pelanggan_id') is-invalid @enderror"
                    disabled>
                @error('pelanggan_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
                <input type="hidden" name="pelanggan_id" id="pelangganId">
            </div>

            <div class="col">
                <label>Nama Kasir</label>
                <input type="text" class="form-control" value="{{ $nama_kasir ?? 'Unknown' }}" disabled>
            </div>
        </div>

        <table class="table table-striped table-hover table-bordered mt-3">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Sub Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="resultCart">
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data.</td>
                </tr>
            </tbody>
        </table>

        <div class="row mt-3">
            <div class="col-2 offset-6">
                <p>Total</p>
                <p>Pajak 10 %</p>
                <p>Total Bayar</p>
            </div>
            <div class="col-4 text-right">
                <p id="subtotal">0</p>
                <p id="taxAmount">0</p>
                <p id="total">0</p>
            </div>
        </div>

        <div class="row mt-0">
            <div class="col-6 offset-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Cash</span>
                    </div>
                    <input type="text" name="cash"
                        class="form-control @error('cash') is-invalid @enderror"
                        placeholder="Jumlah Cash" value="{{ old('cash') }}">
                </div>
                <input type="hidden" name="total_bayar" id="totalBayar" />
                @error('cash')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="col-12 form-inline mt-3">
            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary mr-2">Ke Transaksi</a>
            <a href="{{ route('cart.clear') }}" class="btn btn-danger">Kosongkan</a>
            <button type="submit" class="btn btn-success ml-auto">
                <i class="fas fa-money-bill-wave mr-2"></i> Bayar Transaksi
            </button>
        </div>
    </div>
</form>

@push('scripts')
<script>
$(function() {
    window.fetchCart = function() {
        $.getJSON("/cart", function(response) {
            $('#resultCart').empty();

            const { items, subtotal, tax_amount, total, extra_info } = response;

            $('#subtotal').html(rupiah(subtotal));
            $('#taxAmount').html(rupiah(tax_amount));
            $('#total, #totalJumlah').html(rupiah(total));
            $('#totalBayar').val(total);

            if (items && typeof items === 'object' && Object.keys(items).length > 0) {
                for (const property in items) {
                    addRow(items[property]);
                }
            } else {
                $('#resultCart').html('<tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>');
            }

            if (extra_info && extra_info.pelanggan) {
                const { id, nama } = extra_info.pelanggan;
                $('#namaPelanggan').val(nama);
                $('#pelangganId').val(id);
            }
        });
    }

    fetchCart(); 

    function addRow(item) {
    const { hash, title, quantity, harga, total_price, options } = item;
    const { diskon, harga_produk } = options
    const nilai_diskon = diskon ? `(-${diskon}%)` : '';

    // input number qty
    const qtyInput = `
        <input type="number" 
               class="form-control form-control-sm text-center" 
               value="${quantity}" 
               min="1"
               style="width:80px;"
               onchange="ePut('${hash}', this.value)">
    `;

    // tombol hapus saja
    const btn = `
        <button type="button" class="btn btn-xs btn-danger" onclick="eDel('${hash}')">
            <i class="fas fa-times"></i>
        </button>
    `;

    const row = `<tr>
        <td>${title}</td>
        <td>${qtyInput}</td>
        <td>${rupiah(harga_produk)} ${nilai_diskon}</td>
        <td>${rupiah(total_price)}</td>
        <td>${btn}</td>
    </tr>`;

    $('#resultCart').append(row);
}

    function rupiah(number) {
        return new Intl.NumberFormat("id-ID").format(number);
    }

    window.ePut = function(hash, qty) {
    $.ajax({
        type: "PUT",
        url: "/cart/" + hash,
        data: { qty: qty },
        dataType: "json",
        success: function(response) {
            fetchCart();
        }
    });
}

    window.addItem = function(kode, nama, harga) {
        $.ajax({
            type: "POST",
            url: "/cart",
            data: {
                kode_produk: kode,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                fetchCart();
            },
            error: function(xhr) {
                alert("Gagal menambahkan item. Pastikan produk valid.");
            }
        });
    }

    window.eDel = function(hash) {
        $.ajax({
            type: "DELETE",
            url: "/cart/" + hash,
            dataType: "json",
            success: function(response) {
                fetchCart();
            }
        });
    }
});
</script>
@endpush