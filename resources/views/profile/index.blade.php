<x-app-layout :title="'Profile'">
    <x-alert />
    <div class="container mx-auto px-4 py-8">
        <h2 class="text-center text-2xl font-semibold text-gray-800 mb-4">Profil Pengguna</h2>

        <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6 md:p-10 max-w-4xl mx-auto">
            <div class="flex justify-center mb-6">
                <div class="w-40 h-40 rounded-full overflow-hidden shadow-md">
                    @if (auth()->user()->foto && file_exists(public_path('storage/' . auth()->user()->foto)))
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Foto Profil"
                            class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z" />
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Nama</label>
                    <input type="text" value="{{ auth()->user()->nama }}"
                        class="w-full mt-1 p-3 bg-gray-100 border border-gray-200 rounded-lg" disabled>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Email</label>
                    <input type="text" value="{{ auth()->user()->email }}"
                        class="w-full mt-1 p-3 bg-gray-100 border border-gray-200 rounded-lg" disabled>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">Nomor Induk</label>
                    <input type="text" value="{{ auth()->user()->nomor_induk }}"
                        class="w-full mt-1 p-3 bg-gray-100 border border-gray-200 rounded-lg" disabled>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700">No Telepon</label>
                    <input type="text" value="{{ auth()->user()->no_telepon }}"
                        class="w-full mt-1 p-3 bg-gray-100 border border-gray-200 rounded-lg" disabled>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700">Alamat</label>
                    <textarea class="w-full mt-1 p-3 bg-gray-100 border border-gray-200 rounded-lg" rows="3" disabled>{{ auth()->user()->alamat }}</textarea>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <a href="{{ route('profile.edit') }}"
                    class="inline-block px-5 py-2 bg-yellow-500 text-white font-semibold text-sm rounded-lg hover:bg-yellow-600 transition duration-200">
                    Edit Profile
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
