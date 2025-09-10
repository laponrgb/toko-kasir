<form action="#" method="get" id="formCariPelanggan">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Nama Pelanggan" id="searchPelanggan">
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Cari</button>
        </div>
    </div>
</form>

<table class="table table-sm mt-3">
    <thead>
        <tr>
            <th colspan="2" class="border-0">Hasil Pencarian :</th>
        </tr>
    </thead>
    <tbody id="resultPelanggan"></tbody>
</table>

@push('scripts')
<script>
$(function () {
    $('#formCariPelanggan').submit(function (e) {
        e.preventDefault();
        const search = $('#searchPelanggan').val();
        if (search.length >= 3) {
            fetchCariPelanggan(search);
        }
    });

    function fetchCariPelanggan(search) {
        $.getJSON("/transaksi/pelanggan", { search: search }, function (response) {
            $('#resultPelanggan').html('');
            if (response.length > 0) {
                response.forEach(item => {
                    addResultPelanggan(item);
                });
            } else {
                $('#resultPelanggan').html(`
                    <tr>
                        <td colspan="2" class="text-center text-muted">Pelanggan tidak ditemukan</td>
                    </tr>
                `);
            }
        });
    }

    function addResultPelanggan(item) {
        const { id, nama } = item;
        const btn = `<button type="button" class="btn btn-xs btn-success" onclick="addPelanggan(${id})">ADD</button>`;
        const row = `<tr>
            <td>${nama}</td>
            <td class="text-right">${btn}</td>
        </tr>`;
        $('#resultPelanggan').append(row);
    }

    window.addPelanggan = function(id) {
        $.post("/transaksi/pelanggan", { id: id }, function (response) {
            fetchCart(); 
        });
    }
});
</script>
@endpush
