<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function editPassword()
    {
        return view('auth.ubah-password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Password berhasil diubah.');
        } elseif ($user->role === 'petugas_security') {
            return redirect()->route('petugas-security.dashboard')->with('success', 'Password berhasil diubah.');
        } elseif ($user->role === 'kabid_dukbis') {
            return redirect()->route('kabid-dukbis.dashboard')->with('success', 'Password berhasil diubah.');
        }

        // fallback
        return redirect()->route('login')->with('error', 'Role tidak dikenali.');
    }
}
