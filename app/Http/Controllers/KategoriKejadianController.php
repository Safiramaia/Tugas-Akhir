<?php

namespace App\Http\Controllers;

use App\Models\KategoriKejadian;
use Illuminate\Http\Request;

class KategoriKejadianController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriKejadian::query();

        if ($request->filled('search')) {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }

        $kategori = $query->orderBy('nama_kategori')->paginate(5);

        return view('admin.data-kategori-kejadian', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategori_kejadian,nama_kategori',
            'kirim_notifikasi' => 'required|boolean',
        ]);

        KategoriKejadian::create($validated);

        return back()->with('success', 'Kategori kejadian berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriKejadian $kategoriKejadian)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategori_kejadian,nama_kategori,' . $kategoriKejadian->id,
            'kirim_notifikasi' => 'required|boolean',
        ]);

        $kategoriKejadian->update($validated);

        return back()->with('success', 'Kategori kejadian berhasil diperbarui.');
    }

    public function destroy(KategoriKejadian $kategoriKejadian)
    {
        $kategoriKejadian->delete();

        return back()->with('success', 'Kategori kejadian berhasil dihapus.');
    }
}
