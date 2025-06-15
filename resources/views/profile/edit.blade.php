<x-app-layout :title="'Edit Profile'">
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-center text-2xl font-bold mb-6 text-gray-800">Edit Profil</h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
            class="overflow-x-auto rounded-lg border border-gray-200 shadow-md bg-white p-6">
            @csrf
            @method('PUT')

            <div class="flex flex-col items-center mb-6">
                <div class="w-40 h-40 rounded-full overflow-hidden shadow-md bg-gray-100">
                    @if (auth()->user()->foto && file_exists(public_path('storage/' . auth()->user()->foto)))
                        <img id="preview-image" src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Foto Profil"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="mt-4 w-full md:w-1/2 text-center">
                    <label
                        class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg cursor-pointer hover:bg-blue-700 transition duration-150">
                        Pilih Foto
                        <input type="file" name="foto" accept="image/*" class="hidden"
                            onchange="previewFoto(event)">
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Format gambar: JPG, PNG. Maksimal 2MB.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Nama <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama', auth()->user()->nama) }}"
                        class="w-full mt-1 border border-gray-300 rounded-md p-2">
                    @error('nama')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" value="{{ auth()->user()->email }}" readonly
                        class="w-full mt-1 border border-gray-300 rounded-md p-2 text-gray-700 bg-gray-100 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nomor Induk</label>
                    <input type="text" value="{{ auth()->user()->nomor_induk }}" readonly
                        class="w-full mt-1 border border-gray-300 rounded-md p-2 text-gray-700 bg-gray-100 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        No Telepon <span class="text-red-600">*</span>
                    </label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', auth()->user()->no_telepon) }}"
                        class="w-full mt-1 border border-gray-300 rounded-md p-2">
                    @error('no_telepon')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Alamat <span class="text-red-600">*</span>
                    </label>
                    <textarea name="alamat" rows="3" class="w-full mt-1 border border-gray-300 rounded-md p-2">{{ old('alamat', auth()->user()->alamat) }}</textarea>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <a href="{{ route('profile.index') }}"
                    class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg text-sm font-semibold mr-2">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
<script>
    function previewFoto(event) {
        const input = event.target;
        const preview = document.getElementById('preview-image');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    const container = input.closest('.flex').querySelector('.w-40.h-40');
                    if (container) {
                        container.innerHTML =
                            `<img id="preview-image" src="${e.target.result}" class="w-full h-full object-cover rounded-full" alt="Foto Profil">`;
                    }
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
