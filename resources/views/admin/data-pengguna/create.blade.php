<x-app-layout :title="'Tambah Data Pengguna'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Data Pengguna</h2>

        <div class="max-w-full md:max-w-4xl bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <form action="{{ route('data-pengguna.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama --}}
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">
                            Nama <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
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
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
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
                        <input type="text" id="nomor_induk" name="nomor_induk" value="{{ old('nomor_induk') }}" required
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
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="petugas_security" {{ old('role') == 'petugas_security' ? 'selected' : '' }}>
                                Petugas Security</option>
                            <option value="kabid_dukbis" {{ old('role') == 'kabid_dukbis' ? 'selected' : '' }}>Kabid
                                Dukbis</option>
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
                        <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}"
                            required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        @error('no_telepon')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm pr-10">
                            <button type="button" onclick="togglePasswordVisibility()"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">
                                <svg id="eyeIcon" class="w-6 h-6 text-gray-500 dark:text-gray-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimal berisi 8 karakter.</p>
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700">
                            Alamat <span class="text-red-600">*</span>
                        </label>
                        <textarea id="alamat" name="alamat" rows="3" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">{{ old('alamat') }}</textarea>
                        @error('alamat')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('data-pengguna.index') }}"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">Kembali</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById("password");
            const icon = document.getElementById("eyeIcon");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("text-gray-500");
                icon.classList.add("text-gray-900");
            } else {
                passwordField.type = "password";
                icon.classList.remove("text-gray-900");
                icon.classList.add("text-gray-500");
            }
        }
    </script>
</x-app-layout>
