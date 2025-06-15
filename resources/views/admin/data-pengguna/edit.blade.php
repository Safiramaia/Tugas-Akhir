<x-app-layout :title="'Edit Data Pengguna'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Data Pengguna</h2>

        <div class="max-w-full md:max-w-4xl bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <form action="{{ route('data-pengguna.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama --}}
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">
                            Nama <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('nama')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email <span class="text-red-600">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('email')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor Induk --}}
                    <div>
                        <label for="nomor_induk" class="block text-sm font-medium text-gray-700">
                            Nomor Induk <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="nomor_induk" name="nomor_induk" value="{{ old('nomor_induk', $user->nomor_induk) }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('nomor_induk')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">
                            Hak Akses <span class="text-red-600">*</span>
                        </label>
                        <select id="role" name="role" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">--- Pilih Hak Akses ---</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="petugas_security" {{ old('role', $user->role) == 'petugas_security' ? 'selected' : '' }}>Petugas Security</option>
                            <option value="kabid_dukbis" {{ old('role', $user->role) == 'kabid_dukbis' ? 'selected' : '' }}>Kabid Dukbis</option>
                        </select>
                        @error('role')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- No Telepon --}}
                    <div>
                        <label for="no_telepon" class="block text-sm font-medium text-gray-700">
                            No Telepon <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('no_telepon')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700">
                            Alamat <span class="text-red-600">*</span>
                        </label>
                        <textarea id="alamat" name="alamat" rows="3" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">{{ old('alamat', $user->alamat) }}</textarea>
                        @error('alamat')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('data-pengguna.index') }}"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">Kembali</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
