<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patroli;
use App\Models\LokasiPatroli;
use App\Models\JadwalPatroli;
use App\Models\KategoriKejadian;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PatroliController extends Controller
{
    public function create(Request $request)
    {
        // Mengecek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Ambil lokasi berdasarkan query string ?lokasi=id
        $lokasiId = $request->query('lokasi');
        $lokasiPatroli = LokasiPatroli::findOrFail($lokasiId);

        // Mengambil ID user yang sedang login dan tanggal hari ini
        $userId = Auth::id();
        $today = now()->toDateString();

        // Mengecek apakah petugas dijadwalkan patroli hari ini
        $jadwal = JadwalPatroli::where('user_id', $userId)
            ->where('tanggal', $today)
            ->exists();

        if (!$jadwal) {
            return redirect()->route('petugas-security.dashboard')
                ->with('error', 'Anda tidak dijadwalkan patroli di lokasi ini hari ini.');
        }

        // Ambil semua kategori kejadian untuk ditampilkan di form
        $kategoriKejadian = KategoriKejadian::all();

        // Kirim ke view
        return view('petugas-security.form-patroli', compact('lokasiPatroli', 'kategoriKejadian'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $rules = [
            'lokasi_id' => 'required|exists:lokasi_patroli,id',
            'status' => 'required|in:aman,darurat',
            'keterangan' => 'required|string',
            'foto' => 'required|string',
            // 'latitude' => 'required|numeric',
            // 'longitude' => 'required|numeric',
        ];

        if ($request->status === 'darurat') {
            $rules['kejadian_id'] = 'required|exists:kategori_kejadian,id';
        } else {
            $rules['kejadian_id'] = 'nullable';
        }

        $validated = $request->validate($rules);

        $existingPatroli = Patroli::where('user_id', Auth::id())
            ->where('lokasi_id', $validated['lokasi_id'])
            ->where('tanggal_patroli', now()->toDateString())
            ->exists();

        if ($existingPatroli) {
            return redirect()->route('petugas-security.dashboard')
                ->with('error', 'Patroli di lokasi ini sudah dilakukan hari ini.');
        }

        // $lokasi = LokasiPatroli::findOrFail($validated['lokasi_id']);

        // // Hitung jarak menggunakan rumus Haversine (dalam km)
        // $distance = $this->hitungJarakHaversine(
        //     $validated['latitude'],
        //     $validated['longitude'],
        //     $lokasi->latitude,
        //     $lokasi->longitude
        // );

        // $radiusMaks = 0.05; // 50 meter dalam km

        // if ($distance > $radiusMaks) {
        //     return redirect()
        //         ->route('petugas-security.dashboard')
        //         ->with('error', 'Anda berada di luar radius lokasi patroli yang ditentukan (maks 50 meter).');
        // }

        // Simpan foto
        $fotoPath = null;
        if ($request->filled('foto')) {
            $base64Image = preg_replace('/^data:image\/\w+;base64,/', '', $request->foto);
            $imageData = base64_decode($base64Image);
            $filename = time() . '.jpg';
            $fotoPath = 'patroli/' . $filename;
            Storage::disk('public')->put($fotoPath, $imageData);
        }

        // Ambil unit_id dari lokasi patroli
        $lokasi = LokasiPatroli::findOrFail($request->lokasi_id);
        $unitId = $lokasi->unit_id;

        if (!$unitId) {
            return redirect()->route('petugas-security.dashboard')
                ->with('error', 'Unit belum terhubung dengan lokasi patroli.');
        }

        $patroli = Patroli::create([
            'user_id' => Auth::id(),
            'lokasi_id' => $validated['lokasi_id'],
            'unit_id' => $unitId,
            'kejadian_id' => $validated['kejadian_id'] ?? null,
            'tanggal_patroli' => now()->toDateString(),
            'waktu_patroli' => now()->toTimeString(),
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'],
            'foto' => $fotoPath,
        ]);

        // Kirim notifikasi hanya jika kategori daruratnya memang butuh dikirim
        if ($patroli->status === 'darurat' && $patroli->kategoriKejadian && $patroli->kategoriKejadian->kirim_notifikasi) {
            $this->kirimNotifikasiDarurat($patroli);
        }

        return redirect()->route('petugas-security.dashboard')
            ->with('success', 'Data patroli berhasil disimpan.');
    }

    // private function hitungJarakHaversine($lat1, $lon1, $lat2, $lon2)
    // {
    //     $earthRadius = 6371; // km
    //     $dLat = deg2rad($lat2 - $lat1);
    //     $dLon = deg2rad($lon2 - $lon1);

    //     $a = sin($dLat / 2) * sin($dLat / 2) +
    //         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
    //         sin($dLon / 2) * sin($dLon / 2);
    //     $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    //     return $earthRadius * $c; // km
    // }

    private function kirimNotifikasiDarurat(Patroli $patroli)
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin || !$admin->no_telepon) {
            return;
        }

        //Mengubah nomor telepon
        $number = preg_replace('/[^0-9]/', '', $admin->no_telepon);
        $number = preg_replace('/^08/', '628', $number);

        // Pesan teks
        $message = "*LAPORAN DARURAT!*\n\n"
            . "Petugas : " . ($patroli->user->nama ?? 'Petugas') . "\n"
            . "Lokasi : " . ($patroli->lokasiPatroli->nama_lokasi ?? 'Tidak diketahui') . "\n"
            . "Keterangan : " . ($patroli->keterangan ?? '-') . "\n"
            . "Tanggal : " . \Carbon\Carbon::parse($patroli->tanggal_patroli)->format('d-m-Y') . "\n"
            . "Waktu : " . \Carbon\Carbon::parse($patroli->waktu_patroli)->format('H:i');

        //Payload dasar
        $payload = [
            'target' => $number,
            'message' => $message,
        ];

        // Tambahkan URL foto jika tersedia
        if ($patroli->foto) {
            $payload['url'] = asset('storage/' . $patroli->foto);
        }

        //Kirim ke Fonnte
        $token = trim(config('services.fonnte.token'));
        if (!$token)
            return;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $token,
            ],
        ]);

        curl_exec($curl);
        curl_close($curl);
    }

    //Validasi kejadian darurat oleh admin
    public function validasiKejadianDarurat()
    {
        $patroli = Patroli::with(['user', 'lokasiPatroli', 'unit'])
            ->where('status', 'darurat')
            ->whereNull('validasi_darurat')
            ->latest()
            ->paginate(10);

        $unitKerja = UnitKerja::all();

        return view('admin.validasi-kejadian-darurat', compact('patroli', 'unitKerja'));
    }

    public function updateValidasiDarurat(Request $request, $id)
    {
        $patroli = Patroli::findOrFail($id);

        $request->validate([
            'validasi_darurat' => 'required|in:valid,tidak_valid',
            'unit_id' => 'nullable|exists:unit_kerja,id',
        ]);

        $patroli->update([
            'validasi_darurat' => $request->validasi_darurat,
            'unit_id' => $request->unit_id,
        ]);

        return redirect()->route('admin.validasi-kejadian-darurat')
            ->with('success', 'Validasi kejadian darurat berhasil diperbarui.');
    }
}
