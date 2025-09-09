<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $produks = Produk::join('kategoris', 'kategoris.id', '=', 'produks.kategori_id')
            ->orderBy('produks.id')
            ->select('produks.*', 'kategoris.nama_kategori')
            ->when($search, function ($q) use ($search) {
                return $q->where('kode_produk', 'like', "%{$search}%")
                         ->orWhere('nama_produk', 'like', "%{$search}%");
            })
            ->paginate();

        if ($search) $produks->appends(['search' => $search]);

        return view('produk.index', [
            'produks' => $produks
        ]);
    }

    public function create()
    {
        $lastProduk = Produk::orderBy('id', 'desc')->first();
        $nextCode = 'P0001';

        if ($lastProduk) {
            $lastCode = $lastProduk->kode_produk;
            $number = intval(substr($lastCode, 1));
            $nextNumber = $number + 1;
            $nextCode = 'P' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        $datakategori = Kategori::orderBy('nama_kategori')->get();
        $kategoris = [['', 'Pilih Kategori:']];

        foreach ($datakategori as $kategori) {
            $kategoris[] = [$kategori->id, $kategori->nama_kategori];
        }

        return view('produk.create', [
            'kategoris' => $kategoris,
            'nextCode'  => $nextCode
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_produk' => ['required', 'max:250', 'unique:produks'],
            'nama_produk' => ['required', 'max:150'],
            'harga' => ['required', 'numeric'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'diskon'=>['required','between:0,100'],
        ]);
        
        $harga = $request->harga - ($request->harga * $request->diskon / 100);

        $request->merge([
            'harga_produk' => $request->harga,
            'harga'        => $harga,
        ]);

        Produk::create($request->all());

        return redirect()->route('produk.index')->with('store', 'success');
    }

    public function storeMultiple(Request $request)
    {
        $request->validate([
            'produk' => 'required|array|min:1',
            'produk.*.kode_produk' => ['required', 'max:250', 'unique:produks,kode_produk'],
            'produk.*.nama_produk' => ['required', 'max:150'],
            'produk.*.harga' => ['required', 'numeric'],
            'produk.*.kategori_id' => ['required', 'exists:kategoris,id'],
            'produk.*.diskon' => ['required','between:0,100'],
        ]);

        foreach ($request->produk as $data) {
            $hargaSetelahDiskon = $data['harga'] - ($data['harga'] * $data['diskon'] / 100);

            Produk::create([
                'kode_produk'  => $data['kode_produk'],
                'nama_produk'  => $data['nama_produk'],
                'harga_produk' => $data['harga'],
                'harga'        => $hargaSetelahDiskon,
                'diskon'       => $data['diskon'],
                'kategori_id'  => $data['kategori_id'],
            ]);
        }

        return redirect()->route('produk.index')->with('store', 'success');
    }

    public function show(Produk $produk)
    {
        abort(404);
    }

    public function edit(Produk $produk)
    {
        $datakategori = Kategori::orderBy('nama_kategori')->get();
        $kategoris = [['', 'Pilih Kategori:']];

        foreach ($datakategori as $kategori) {
            $kategoris[] = [$kategori->id, $kategori->nama_kategori];
        }

        return view('produk.edit', [
            'produk' => $produk,
            'kategoris' => $kategoris
        ]);
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'kode_produk' => ['required', 'max:250', 'unique:produks,kode_produk,' . $produk->id],
            'nama_produk' => ['required', 'max:150'],
            'harga' => ['required', 'numeric'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'diskon'=>['required','between:0,100'],
        ]);

        $harga = $request->harga - ($request->harga * $request->diskon / 100);

        $request->merge([
            'harga_produk' => $request->harga,
            'harga'        => $harga,
        ]);

        $produk->update($request->all());

        return redirect()->route('produk.index')->with('update', 'success');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return back()->with('destroy', 'success');
    }
}
