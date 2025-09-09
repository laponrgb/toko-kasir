<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Stok;
use Illuminate\Http\Request;

class StokController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $stoks = Stok::join('produks', 'produks.id', '=', 'stoks.produk_id')
            ->join('kategoris', 'kategoris.id', '=', 'produks.kategori_id')
            ->select(
                'stoks.*',
                'produks.nama_produk',
                'produks.kode_produk',
                'kategoris.nama_kategori'
            )
            ->orderBy('stoks.id', 'desc')
            ->when($search, function ($q, $search) {
                return $q->where('stoks.tanggal', 'like', "%{$search}%");
            })
            ->paginate();

        if ($search) {
            $stoks->appends(['search' => $search]);
        }

        return view('stok.index', compact('stoks'));
    }

    public function create()
    {
        return view('stok.create');
    }

    public function produk(Request $request)
    {
        $produks = Produk::join('kategoris', 'kategoris.id', '=', 'produks.kategori_id')
            ->select(
                'produks.id',
                'produks.kode_produk',
                'produks.nama_produk',
                'kategoris.nama_kategori'
            )
            ->when($request->search, function ($q, $search) {
                $q->where('produks.nama_produk', 'like', "%{$search}%")
                ->orWhere('produks.kode_produk', 'like', "%{$search}%");
            })
            ->take(15)
            ->get();

        return response()->json($produks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => ['required', 'exists:produks,id'],
            'jumlah' => ['required', 'numeric'],
            'nama_suplier' => ['required', 'max:150']
        ]);

        $request->merge([
            'tanggal' => date('Y-m-d')
        ]);

        Stok::create($request->all());

        $produk = Produk::find($request->produk_id);    
        $produk->update([
            'stok' => $produk->stok + $request->jumlah
        ]);

        return redirect()->route('stok.index')->with('store', 'success');
    }

    public function storeMultiple(Request $request)
{
    $request->validate([
        'stok' => ['required', 'array'],
        'stok.*.produk_id' => ['required', 'exists:produks,id'],
        'stok.*.jumlah' => ['required', 'numeric'],
        'stok.*.nama_suplier' => ['required', 'max:150'],
    ]);

    foreach ($request->stok as $stokData) {
        $stokData['tanggal'] = date('Y-m-d');

        // Simpan stok baru
        $stok = Stok::create($stokData);

        // Update stok produk
        $produk = Produk::find($stokData['produk_id']);
        $produk->update([
            'stok' => $produk->stok + $stokData['jumlah']
        ]);
    }

    return redirect()->route('stok.index')->with('store', 'success');
}

    public function destroy(Stok $stok)
    {
        $produk = Produk::find($stok->produk_id);
        $produk->update([
            'stok' => $produk->stok - $stok->jumlah
        ]);

        $stok->delete();

        return back()->with('destroy', 'success');
    }
}
