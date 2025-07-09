<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use Illuminate\Http\Request;

class UnitKerjaController extends Controller
{
    public function index(Request $request)
    {
        $query = UnitKerja::query();

        if ($request->has('search')) {
            $query->where('nama_unit', 'like', '%' . $request->search . '%');
        }

        $units = $query->orderBy('nama_unit')->paginate(5);

        return view('admin.data-unit-kerja', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_unit' => 'required|string|max:30|unique:unit_kerja,nama_unit',
        ]);

        UnitKerja::create($validated);

        return redirect()->route('unit-kerja.index')->with('success', 'Unit kerja berhasil ditambahkan.');
    }

    public function update(Request $request, UnitKerja $unitKerja)
    {
        $validated = $request->validate([
            'nama_unit' => 'required|string|max:30|unique:unit_kerja,nama_unit,' . $unitKerja->id,
        ]);

        $unitKerja->update($validated);

        return redirect()->route('unit-kerja.index')->with('success', 'Unit kerja berhasil diperbarui.');
    }

    public function destroy(UnitKerja $unitKerja)
    {
        $unitKerja->delete();

        return redirect()->route('unit-kerja.index')->with('success', 'Unit kerja berhasil dihapus.');
    }
}
