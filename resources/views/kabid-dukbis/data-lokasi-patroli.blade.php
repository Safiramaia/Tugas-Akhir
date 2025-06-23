<x-app-layout :title="'Data Lokasi Patroli'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Daftar Lokasi Patroli</h2>

        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-md mb-4">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 px-4 py-3 bg-white">
                {{-- Search --}}
                <form action="{{ route('kabid-dukbis.data-lokasi-patroli') }}" method="GET"
                    class="w-full md:w-1/2 flex flex-col md:flex-row items-start md:items-center gap-2">
                    <div class="relative flex w-full">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Cari lokasi..." autocomplete="off"
                            class="block w-full p-2 pl-10 text-sm text-gray-900 bg-white border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg">
                            Cari
                        </button>
                    </div>

                    @if (request('search'))
                        <a href="{{ route('kabid-dukbis.data-lokasi-patroli') }}"
                            class="px-4 py-2 text-sm text-white bg-gray-400 hover:bg-gray-500 rounded-lg">
                            Kembali
                        </a>
                    @endif
                </form>

            </div>
        </div>

        {{-- Tabel Data Lokasi Patroli --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-4">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Lokasi</th>
                        <th class="px-4 py-3">Latitude</th>
                        <th class="px-4 py-3">Longitude</th>
                        <th class="px-4 py-3">QR Code</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($lokasiPatroli as $lokasi)
                        <tr class="hover:bg-gray-100 text-center">
                            <td class="px-4 py-2 text-center">
                                {{ $loop->iteration + ($lokasiPatroli->currentPage() - 1) * $lokasiPatroli->perPage() }}
                            </td>
                            <td class="px-4 py-2 text-left">{{ $lokasi->nama_lokasi }}</td>
                            <td class="px-4 py-2">{{ $lokasi->latitude }}</td>
                            <td class="px-4 py-2">{{ $lokasi->longitude }}</td>
                            <td class="px-4 py-2">
                                @if ($lokasi->qr_code)
                                    <img src="{{ asset('storage/' . $lokasi->qr_code) }}" alt="QR Code"
                                        class="w-20 max-w-full h-auto object-contain mx-auto rounded cursor-pointer"
                                        onclick="showQrModal('{{ asset('storage/' . $lokasi->qr_code) }}', '{{ $lokasi->nama_lokasi }}')">
                                @else
                                    <span class="text-gray-400 italic">Belum Ada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                Belum ada data lokasi patroli.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex justify-between items-center bg-white">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $lokasiPatroli->firstItem() ?? 0 }} sampai {{ $lokasiPatroli->lastItem() ?? 0 }} dari
                {{ $lokasiPatroli->total() }} data
            </div>
            <div>
                {{ $lokasiPatroli->links('components.pagination') }}
            </div>
        </div>
        
        {{-- Modal Preview QR --}}
        <div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
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
                        <h3 id="qrModalNamaLokasi" class="text-base sm:text-lg font-semibold mb-4 text-gray-800"></h3>
                        <img id="qrModalImg" src="" alt="QR Preview"
                            class="mx-auto w-full max-w-[180px] sm:max-w-[220px] h-auto rounded-lg shadow-md border border-gray-200">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    //Menampilkan modal QR
    function showQrModal(imageUrl, namaLokasi) {
        document.getElementById('qrModalImg').src = imageUrl;
        document.getElementById('qrModalNamaLokasi').textContent = namaLokasi;
        document.getElementById('qrModal').classList.remove('hidden');
    }

    //Menutup modal
    function closeModal() {
        document.getElementById('qrModal').classList.add('hidden');
    }
</script>
