<?php

namespace App\Http\Controllers;

use App\Models\LokasiPatroli;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class LokasiPatroliController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $lokasiPatroli = LokasiPatroli::query()
            ->when($search, function ($query, $search) {
                $query->where('nama_lokasi', 'like', '%' . $search . '%');
            })
            ->orderBy('nama_lokasi')
            ->paginate(10)
            ->withQueryString();

        return view('admin.lokasi-patroli.index', compact('lokasiPatroli', 'search'));
    }

    //Generate QR Code untuk lokasi patroli
    private function generateQrCode(LokasiPatroli $lokasi): void
    {
        $url = route('patroli.create', ['lokasi' => $lokasi->id]);
        $qrCodeImage = QrCode::format('png')
            ->size(300)
            ->margin(2)
            ->generate($url);

        $filePath = "qr_codes/lokasi_{$lokasi->id}.png";
        Storage::disk('public')->put($filePath, $qrCodeImage);

        $lokasi->update([
            'qr_code' => $filePath,
        ]);
    }

    public function create()
    {
        return view('admin.lokasi-patroli.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:40',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $lokasi = LokasiPatroli::create($validated);

        $this->generateQrCode($lokasi);

        return redirect()->route('lokasi-patroli.index')
            ->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function edit(LokasiPatroli $lokasiPatroli)
    {
        return view('admin.lokasi-patroli.edit', compact('lokasiPatroli'));
    }

    public function update(Request $request, LokasiPatroli $lokasiPatroli)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:40',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $dataLama = $lokasiPatroli->only(['nama_lokasi', 'latitude', 'longitude']);

        $lokasiPatroli->update($validated);

        // Regenerate QR jika ada perubahan data lokasi
        if ($dataLama != $validated) {
            if ($lokasiPatroli->qr_code && Storage::disk('public')->exists($lokasiPatroli->qr_code)) {
                Storage::disk('public')->delete($lokasiPatroli->qr_code);
            }

            $this->generateQrCode($lokasiPatroli);
        }

        return redirect()->route('lokasi-patroli.index')
            ->with('success', 'Lokasi Patroli berhasil diperbarui.');
    }

    public function destroy(LokasiPatroli $lokasiPatroli)
    {
        if ($lokasiPatroli->qr_code && Storage::disk('public')->exists($lokasiPatroli->qr_code)) {
            Storage::disk('public')->delete($lokasiPatroli->qr_code);
        }

        $lokasiPatroli->delete();

        return redirect()->route('lokasi-patroli.index')
            ->with('success', 'Lokasi Patroli berhasil dihapus.');
    }

    public function downloadQrCode(LokasiPatroli $lokasiPatroli)
    {
        if (!$lokasiPatroli->qr_code || !Storage::disk('public')->exists($lokasiPatroli->qr_code)) {
            return redirect()->back()->with('error', 'QR Code tidak ditemukan.');
        }

        return response()->download(storage_path('app/public/' . $lokasiPatroli->qr_code));
    }
}
