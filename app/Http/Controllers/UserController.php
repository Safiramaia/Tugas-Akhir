<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('nomor_induk', 'like', '%' . $search . '%')
                        ->orWhere('no_telepon', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.data-pengguna.index', compact('users', 'search'));
    }

    public function create()
    {
        return view('admin.data-pengguna.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:30'],
            'email' => ['required', 'email', 'regex:/@gmail\.com$/', 'max:30', 'unique:users,email'],
            'nomor_induk' => ['required', 'string', 'regex:/^\d+$/', 'unique:users,nomor_induk'],
            'no_telepon' => ['required', 'string', 'regex:/^\d+$/', 'digits_between:6,15', 'unique:users,no_telepon'],
            'alamat' => ['required', 'string'],
            'role' => ['required', 'in:admin,petugas_security,kabid_dukbis'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ], [
            'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.regex' => 'Email harus menggunakan domain @gmail.com.',
            'email.unique' => 'Email sudah terdaftar.',
            'nomor_induk.regex' => 'Nomor induk harus berupa angka.',
            'nomor_induk.unique' => 'Nomor induk sudah ada.',
            'no_telepon.regex' => 'Nomor telepon harus berupa angka.',
            'no_telepon.unique' => 'Nomor telepon sudah digunakan.',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('data-pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.data-pengguna.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'nama' => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:30'],
            'email' => ['required', 'email', 'regex:/@gmail\.com$/', 'max:30', 'unique:users,email,' . $user->id],
            'nomor_induk' => ['required', 'string', 'regex:/^\d+$/', 'unique:users,nomor_induk,' . $user->id],
            'no_telepon' => ['required', 'string', 'regex:/^\d+$/', 'digits_between:6,15', 'unique:users,no_telepon,' . $user->id],
            'alamat' => ['required', 'string'],
            'role' => ['required', 'in:admin,petugas_security,kabid_dukbis'],
        ], [
            'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.regex' => 'Email harus menggunakan domain @gmail.com.',
            'email.unique' => 'Email sudah terdaftar.',
            'nomor_induk.regex' => 'Nomor induk harus berupa angka.',
            'nomor_induk.unique' => 'Nomor induk sudah ada.',
            'no_telepon.regex' => 'Nomor telepon harus berupa angka.',
            'no_telepon.unique' => 'Nomor telepon sudah digunakan.',
        ]);

        $user->update($validated);

        return redirect()->route('data-pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('data-pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
