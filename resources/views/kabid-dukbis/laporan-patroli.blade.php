<x-app-layout :title="'Laporan Patroli'">
    <x-alert />
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Laporan Patroli</h2>

        <div class="overflow-x-auto border border-gray-200 rounded-lg mb-4">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 px-4 py-3 bg-white">
                <form action="{{ route('kabid-dukbis.laporan-patroli') }}" method="GET"
                    class="w-full md:w-2/3 flex flex-col md:flex-row items-start md:items-center gap-3">

                    <!-- Dropdown status -->
                    <select name="status"
                        class="border border-gray-300 rounded-lg p-2 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 w-full md:w-40">
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua status</option>
                        <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Aman</option>
                        <option value="darurat" {{ request('status') == 'darurat' ? 'selected' : '' }}>Darurat</option>
                    </select>

                    <!-- Search -->
                    <div class="relative flex w-full md:w-auto">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama petugas, lokasi, atau tanggal..." autocomplete="off"
                            class="block w-full md:w-64 p-2 pl-10 text-sm text-gray-900 bg-white border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500" />
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg">
                            Cari
                        </button>
                    </div>

                    @if (request('search') || request('status'))
                        <a href="{{ route('kabid-dukbis.laporan-patroli') }}"
                            class="inline-block px-4 py-2 text-sm text-white bg-gray-500 hover:bg-gray-600 rounded-lg transition">
                            Kembali
                        </a>
                    @endif
                </form>

                <form action="{{ route('kabid-dukbis.cetak-laporan-patroli') }}" method="GET"
                    class="flex flex-col md:flex-row md:items-center gap-4">
                    <div class="flex flex-row gap-4 w-full md:w-auto">
                        <div class="flex flex-col gap-1 w-1/2">
                            <label for="tanggal_mulai" class="text-sm font-medium">Tanggal Mulai :</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                                value="{{ old('tanggal_mulai') }}" class="border border-gray-300 rounded-lg text-sm p-2"
                                required>
                            @error('tanggal_mulai')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-1 w-1/2">
                            <label for="tanggal_selesai" class="text-sm font-medium">Tanggal Selesai :</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                                value="{{ old('tanggal_selesai') }}"
                                class="border border-gray-300 rounded-lg text-sm p-2" required>
                            @error('tanggal_selesai')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit"
                        class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold w-full md:w-auto mt-4 md:mt-0">
                        <svg class="w-4 h-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                            fill="currentColor" aria-hidden="true" focusable="false">
                            <path
                                d="M128 0C92.7 0 64 28.7 64 64l0 96 64 0 0-96 226.7 0L384 93.3l0 66.7 64 0 0-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0L128 0zM384 352l0 32 0 64-256 0 0-64 0-16 0-16 256 0zm64 32l32 0c17.7 0 32-14.3 32-32l0-96c0-35.3-28.7-64-64-64L64 192c-35.3 0-64 28.7-64 64l0 96c0 17.7 14.3 32 32 32l32 0 0 64c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                        </svg>
                        Cetak
                    </button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-4">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Petugas</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3">Foto</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($dataPatroli as $patroli)
                        <tr class="hover:bg-gray-100 text-center">
                            <td class="px-4 py-2">
                                {{ $loop->iteration + ($dataPatroli->currentPage() - 1) * $dataPatroli->perPage() }}
                            </td>
                            <td class="px-4 py-2">{{ $patroli->user->nama ?? 'Tidak diketahui' }}</td>
                            <td class="px-4 py-2 text-left">
                                {{ $patroli->lokasiPatroli->nama_lokasi ?? 'Tidak diketahui' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($patroli->tanggal_patroli)->format('d-m-Y') }}</td>
                            <td class="px-4 py-2">{{ $patroli->waktu_patroli }}</td>
                            <td class="px-4 py-2">
                                @if ($patroli->status === 'aman')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Aman</span>
                                @elseif($patroli->status === 'darurat')
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Darurat</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">Tidak
                                        diketahui</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-left">{{ $patroli->keterangan ?? '-' }}</td>
                            <td class="px-4 py-2">
                                @if ($patroli->foto && file_exists(public_path('storage/' . $patroli->foto)))
                                    <img src="{{ asset('storage/' . $patroli->foto) }}" alt="Foto"
                                        class="w-20 sm:w-16 xs:w-12 h-auto object-cover rounded-lg mx-auto cursor-pointer"
                                        onclick="showFotoModal('{{ asset('storage/' . $patroli->foto) }}', '{{ $patroli->lokasiPatroli->nama_lokasi ?? 'Lokasi tidak diketahui' }}')">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada foto</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-4 text-center text-gray-500">
                                Tidak ada data patroli tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center bg-white px-2 py-3">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $dataPatroli->firstItem() ?? 0 }} sampai {{ $dataPatroli->lastItem() ?? 0 }} dari
                {{ $dataPatroli->total() }} data
            </div>
            <div>
                {{ $dataPatroli->links('components.pagination') }}
            </div>
        </div>

        {{-- Modal Preview Foto --}}
        <div id="fotoModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeModal()"></div>
                <div class="relative bg-white rounded-lg shadow-xl transform transition-all sm:max-w-md w-full z-10">
                    <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-700 hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div class="p-6 text-center">
                        <h3 id="fotoModalNamaLokasi" class="text-lg font-semibold mb-4 text-gray-800"></h3>
                        <img id="fotoModalImg" src="" alt="Preview Foto"
                            class="mx-auto max-w-full h-auto rounded-lg">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function showFotoModal(imageUrl, namaLokasi) {
        document.getElementById('fotoModalImg').src = imageUrl;
        document.getElementById('fotoModalNamaLokasi').textContent = namaLokasi;
        document.getElementById('fotoModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('fotoModal').classList.add('hidden');
    }

    document.querySelector('select[name="status"]').addEventListener('change', function() {
        this.form.submit();
    });
</script>
