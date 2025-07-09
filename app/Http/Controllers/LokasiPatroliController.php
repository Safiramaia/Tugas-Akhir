<?php

namespace App\Http\Controllers;

use App\Models\LokasiPatroli;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class LokasiPatroliController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $lokasiPatroli = LokasiPatroli::with('unitKerja') 
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
        //URL dalam QR Code yang mengarahkan ke form patroli
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
        $unitKerjaList = UnitKerja::orderBy('nama_unit')->get();
        return view('admin.lokasi-patroli.create', compact('unitKerjaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:40',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'unit_id' => 'required|exists:unit_kerja,id',
        ]);

        $lokasi = LokasiPatroli::create($validated);

        $this->generateQrCode($lokasi);

        return redirect()->route('lokasi-patroli.index')
            ->with('success', 'Lokasi Patroli berhasil ditambahkan');
    }

    public function edit(LokasiPatroli $lokasiPatroli)
    {
        $unitKerjaList = UnitKerja::orderBy('nama_unit')->get();
        return view('admin.lokasi-patroli.edit', compact('lokasiPatroli', 'unitKerjaList'));
    }

    public function update(Request $request, LokasiPatroli $lokasiPatroli)
    {
        $validated = $request->validate([
            'nama_lokasi' => 'required|string|max:40',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'unit_id' => 'required|exists:unit_kerja,id',
        ]);

        $dataLama = $lokasiPatroli->only(['nama_lokasi', 'latitude', 'longitude', 'unit_id']);

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
        // Cek apakah file QR ada
        if (!$lokasiPatroli->qr_code || !Storage::disk('public')->exists($lokasiPatroli->qr_code)) {
            return redirect()->back()->with('error', 'QR Code tidak ditemukan.');
        }

        // Ambil path QR code
        $qrPath = storage_path('app/public/' . $lokasiPatroli->qr_code);

        // Baca gambar QR
        $manager = new ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
        $qrImage = $manager->read($qrPath);

        // Resize ke ukuran yang sama dengan generateQrCode (500x500)
        $qrImage->resize(500, 500);

        // Ukuran QR
        $width = $qrImage->width();
        $height = $qrImage->height();
        $extraHeight = 80; // lebih tinggi agar teks tidak mepet
        $canvasHeight = $height + $extraHeight;

        // Buat canvas putih
        $canvas = $manager->create($width, $canvasHeight)->fill('#ffffff');

        // Path font
        $fontPath = public_path('fonts/OpenSans.ttf');

        // Tambahkan teks nama lokasi di atas QR
        if (file_exists($fontPath)) {
            $canvas->text($lokasiPatroli->nama_lokasi, $width / 2, 30, function ($font) use ($fontPath) {
                $font->filename($fontPath);
                $font->size(24);
                $font->color('#000000');
                $font->align('center');
                $font->valign('top');
            });
        }

        // Tempel QR di bawah teks
        $canvas->place($qrImage, 'top-left', 0, $extraHeight);

        // Return sebagai file download PNG
        return response($canvas->toPng()->toString())
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="qr_lokasi_' . $lokasiPatroli->id . '.png"');
    }
}
