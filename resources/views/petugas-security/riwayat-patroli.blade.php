<x-app-layout :title="'Riwayat Patroli'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Riwayat Patroli Anda</h2>

        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-md mb-4">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 px-4 py-3 bg-white">
                <form action="{{ route('petugas-security.riwayat-patroli') }}" method="GET"
                    class="flex flex-col md:flex-row items-start md:items-center gap-2 w-full md:w-auto">

                    {{-- Status Filter --}}
                    <select name="status"
                        class="border border-gray-300 rounded-lg p-2 text-sm text-gray-900 focus:ring-blue-500 focus:border-blue-500 w-full md:w-40">
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua status</option>
                        <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Aman</option>
                        <option value="darurat" {{ request('status') == 'darurat' ? 'selected' : '' }}>Darurat</option>
                    </select>

                    {{-- Search --}}
                    <div class="flex w-full md:w-96">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari riwayat patroli..." autocomplete="off"
                                class="block w-full p-2 pl-10 text-sm text-gray-900 bg-white border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500" />
                        </div>
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg border-l-0 border border-blue-600">
                            Cari
                        </button>
                    </div>

                    @if (request('search') || request('status'))
                        <a href="{{ route('petugas-security.riwayat-patroli') }}"
                            class="px-4 py-2 text-sm text-white bg-gray-400 hover:bg-gray-500 rounded-lg whitespace-nowrap">
                            Kembali
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Tabel Riwayat Patroli --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-4">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3">Foto</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($riwayatPatroli as $item)
                        <tr class="hover:bg-gray-100 text-center">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-left">{{ $item->lokasiPatroli->nama_lokasi ?? '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $item->tanggal_patroli }}</td>
                            <td class="px-4 py-2">{{ $item->waktu_patroli }}</td>
                            <td class="px-4 py-2">
                                @if ($item->status === 'aman')
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Aman</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Darurat</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-left">{{ $item->keterangan ?? '-' }}</td>
                            <td class="px-4 py-2">
                                @if ($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto"
                                        class="w-16 h-16 object-cover rounded-lg mx-auto cursor-pointer"
                                        onclick="showFotoModal('{{ asset('storage/' . $item->foto) }}', '{{ $item->lokasiPatroli->nama_lokasi ?? '-' }}')">
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-center text-gray-500">
                                Belum ada data riwayat patroli.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex justify-between items-center bg-white px-2 py-3">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $riwayatPatroli->firstItem() ?? 0 }} sampai {{ $riwayatPatroli->lastItem() ?? 0 }} dari
                {{ $riwayatPatroli->total() }} data
            </div>
            <div>
                {{ $riwayatPatroli->links('components.pagination') }}
            </div>
        </div>

        {{-- Modal Preview Foto --}}
        <div id="fotoModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
                <div class="relative w-full max-w-sm bg-white rounded-lg shadow-xl z-10">
                    <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-700 hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div class="p-4 sm:p-6 text-center">
                        <h3 id="fotoModalNamaLokasi" class="text-base sm:text-lg font-semibold mb-4 text-gray-800"></h3>
                        <img id="fotoModalImg" src="" alt="Preview Foto"
                            class="mx-auto w-full max-w-[220px] sm:max-w-[280px] h-auto rounded shadow-md">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        //Menampilkan modal foto
        function showFotoModal(imageUrl, namaLokasi) {
            document.getElementById('fotoModalImg').src = imageUrl;
            document.getElementById('fotoModalNamaLokasi').textContent = namaLokasi;
            document.getElementById('fotoModal').classList.remove('hidden');
        }

        //Menutup modal foto
        function closeModal() {
            document.getElementById('fotoModal').classList.add('hidden');
        }

        //Mengganti status filter
        document.querySelector('select[name="status"]').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
</x-app-layout>
