<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();

        return view('profile.index', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'nama' => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:30'],
            'no_telepon' => ['required', 'regex:/^\d+$/', 'digits_between:6,15'],
            'alamat' => ['required', 'string'],
            'foto' => ['nullable', 'image', 'mimes:png,jpg', 'max:2048'],
        ], [
            'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'no_telepon.regex' => 'Nomor telepon harus berupa angka.',
        ]);

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $file = $request->file('foto');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('foto_user', $filename, 'public');
            $validated['foto'] = $path;
        }

        $user->nama = $validated['nama'];
        $user->no_telepon = $validated['no_telepon'];
        $user->alamat = $validated['alamat'];

        if (isset($validated['foto'])) {
            $user->foto = $validated['foto'];
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
}
