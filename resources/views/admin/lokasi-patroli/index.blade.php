<x-app-layout :title="'Data Lokasi Patroli'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Daftar Lokasi Patroli</h2>

        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-md mb-4">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 px-4 py-3 bg-white">
                {{-- Search --}}
                <form id="search-form" action="{{ route('lokasi-patroli.index') }}" method="GET"
                    class="flex flex-row items-center gap-2 w-full md:w-1/2">

                    <label for="search" class="sr-only">Cari Lokasi</label>

                    <div class="relative flex flex-grow">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Cari lokasi..." autocomplete="off"
                            class="block w-full p-2 pl-10 text-sm text-gray-900 bg-white border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500" />
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg">
                            Cari
                        </button>
                    </div>

                    @if (request('search'))
                        <a href="{{ route('lokasi-patroli.index') }}"
                            class="px-4 py-2 text-sm text-white bg-gray-400 hover:bg-gray-500 rounded-lg whitespace-nowrap">
                            Kembali
                        </a>
                    @endif
                </form>

                {{-- Tambah Lokasi --}}
                <a href="{{ route('lokasi-patroli.create') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm text-center font-semibold whitespace-nowrap">
                    Tambah Lokasi
                </a>
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
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($lokasiPatroli as $data)
                        <tr class="hover:bg-gray-100">
                            <td class="px-4 py-2 text-center">
                                {{ $loop->iteration + ($lokasiPatroli->currentPage() - 1) * $lokasiPatroli->perPage() }}
                            </td>
                            <td class="px-4 py-2 text-left">{{ $data->nama_lokasi }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->latitude }}</td>
                            <td class="px-4 py-2 text-center">{{ $data->longitude }}</td>
                            <td class="px-4 py-2 text-center">
                                @if ($data->qr_code)
                                    <img src="{{ asset('storage/' . $data->qr_code) }}" alt="QR Code"
                                        class="w-20 sm:w-16 xs:w-14 max-w-full h-auto object-contain mx-auto rounded cursor-pointer"
                                        onclick="showQrModal('{{ asset('storage/' . $data->qr_code) }}', '{{ $data->nama_lokasi }}')">
                                @else
                                    <span class="text-gray-400 italic">Tidak tersedia</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex overflow-x-auto no-scrollbar gap-1">
                                    {{-- Unduh QR Code --}}
                                    @if ($data->qr_code)
                                        <a href="{{ route('lokasi-patroli.downloadQrCode', $data->id) }}"
                                            class="inline-flex items-center px-2 py-1 text-sm text-white bg-green-600 hover:bg-green-700 rounded whitespace-nowrap">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1"
                                                fill="currentColor" viewBox="0 0 512 512">
                                                <path
                                                    d="M480 352c17.7 0 32 14.3 32 32v64c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V384c0-17.7
                                                14.3-32 32-32s32 14.3 32 32v64H448V384c0-17.7 14.3-32 32-32zM272 32v278.1l73.6-73.6c12.5-12.5 32.8-12.5
                                                45.3 0s12.5 32.8 0 45.3l-128 128c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8
                                                0-45.3s32.8-12.5 45.3 0L240 310.1V32c0-17.7 14.3-32 32-32s32 14.3 32 32z" />
                                            </svg>
                                            Unduh
                                        </a>
                                    @endif

                                    {{-- Edit --}}
                                    <a href="{{ route('lokasi-patroli.edit', $data->id) }}"
                                        class="inline-flex items-center px-2 py-1 text-sm text-white bg-yellow-400 hover:bg-yellow-500 rounded whitespace-nowrap">
                                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd"
                                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Edit
                                    </a>

                                    {{-- Hapus --}}
                                    <form action="{{ route('lokasi-patroli.destroy', $data->id) }}" method="POST"
                                        class="inline form-hapus">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="btn-hapus inline-flex items-center px-2 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded whitespace-nowrap"
                                            data-nama="{{ $data->nama_lokasi }}">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0
                                                002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0
                                                012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500">
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
        <div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
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
                        <h3 id="qrModalNamaLokasi" class="text-lg font-semibold mb-4 text-gray-800"></h3>
                        <img id="qrModalImg" src="" alt="QR Preview" class="mx-auto max-w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    //Menampilkan QR Code
    function showQrModal(imageUrl, namaLokasi) {
        document.getElementById('qrModalImg').src = imageUrl;
        document.getElementById('qrModalNamaLokasi').textContent = namaLokasi;
        document.getElementById('qrModal').classList.remove('hidden');
    }

    //Menutup Modal
    function closeModal() {
        document.getElementById('qrModal').classList.add('hidden');
    }

    //Konfirmasi Hapus
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                const namaLokasi = this.getAttribute('data-nama');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Apakah Anda yakin ingin menghapus lokasi "${namaLokasi}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
