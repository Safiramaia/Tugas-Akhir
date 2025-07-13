<x-app-layout :title="'Validasi Kejadian Darurat'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Validasi Kejadian Darurat</h2>

        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Petugas</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Keterangan</th>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Validasi Kejadian</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($patroli as $data)
                        <tr class="text-center hover:bg-gray-50">
                            <td class="px-4 py-2">
                                {{ $loop->iteration + ($patroli->currentPage() - 1) * $patroli->perPage() }}
                            </td>
                            <td class="px-4 py-2">{{ $data->user->nama ?? '-' }}</td>
                            <td class="px-4 py-2 text-left">{{ $data->lokasiPatroli->nama_lokasi ?? '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($data->tanggal_patroli)->format('d-m-Y') }}
                            </td>
                            <td class="px-4 py-2 text-left">{{ $data->keterangan }}</td>
                            <td class="px-4 py-2">
                                @if ($data->foto && file_exists(public_path('storage/' . $data->foto)))
                                    <img src="{{ asset('storage/' . $data->foto) }}" alt="Foto"
                                        class="w-20 mx-auto rounded shadow cursor-pointer"
                                        onclick="showFotoModal('{{ asset('storage/' . $data->foto) }}', '{{ $data->lokasiPatroli->nama_lokasi ?? '-' }}')">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if ($data->validasi_darurat == null)
                                    <button
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm text-center font-semibold whitespace-nowrap"
                                        onclick="bukaModalValidasi({{ $data->id }})">
                                        Belum Validasi
                                    </button>
                                @elseif ($data->validasi_darurat == 'valid')
                                    <span
                                        class="px-4 py-2 bg-green-100 text-green-700 rounded-lg text-sm font-semibold">Valid</span>
                                @else
                                    <span
                                        class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-semibold">Tidak
                                        Valid</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-4 text-center text-gray-500">
                                Tidak ada laporan darurat yang menunggu validasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex justify-between items-center bg-white px-2 py-3 mt-4">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $patroli->firstItem() ?? 0 }} sampai {{ $patroli->lastItem() ?? 0 }} dari
                {{ $patroli->total() }} data
            </div>
            <div>
                {{ $patroli->links('components.pagination') }}
            </div>
        </div>

        {{-- Modal Validasi --}}
        <div id="modalValidasi"
            class="hidden fixed top-0 left-0 right-0 z-50 w-full h-full bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4">
                <h2 class="text-lg font-bold mb-4 text-gray-800">Validasi Kejadian Darurat</h2>
                <form id="formValidasi" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="validasi_darurat" class="block text-sm font-medium text-gray-700 mb-1">
                            Status Validasi
                        </label>
                        <select name="validasi_darurat" id="selectValidasi"
                            class="mt-1 w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300"
                            required>
                            <option value="">-- Pilih Status --</option>
                            <option value="valid">Valid</option>
                            <option value="tidak_valid">Tidak Valid</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Unit Terkait
                        </label>
                        <select name="unit_id" id="selectUnit"
                            class="mt-1 w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300"
                            required>
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach ($unitKerja as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="tutupModalValidasi()"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">
                            Kirim
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Foto --}}
        <div id="fotoModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="relative w-full max-w-sm bg-white rounded-lg shadow-lg">
                    <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-700 hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <div class="p-4 text-center">
                        <h3 id="fotoModalNamaLokasi" class="text-lg font-semibold mb-3 text-gray-800"></h3>
                        <img id="fotoModalImg" src="" alt="Preview Foto"
                            class="mx-auto w-full max-w-[240px] rounded shadow-md">
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

    function bukaModalValidasi(patroliId) {
        const modal = document.getElementById('modalValidasi');
        const form = document.getElementById('formValidasi');
        const validasiSelect = document.getElementById('selectValidasi');
        const unitSelect = document.getElementById('selectUnit');

        form.action = `/admin/validasi-kejadian-darurat/${patroliId}`;
        validasiSelect.value = '';
        unitSelect.value = '';
        modal.classList.remove('hidden');
    }

    function tutupModalValidasi() {
        document.getElementById('modalValidasi').classList.add('hidden');
    }
</script>
