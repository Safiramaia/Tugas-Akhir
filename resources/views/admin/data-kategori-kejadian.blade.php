<x-app-layout :title="'Data Kategori Kejadian'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Data Kategori Kejadian</h2>

        {{-- Search + Tambah --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-md mb-4">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 px-4 py-3 bg-white">
                <form action="{{ route('kategori-kejadian.index') }}" method="GET"
                    class="flex flex-row items-center gap-2 w-full md:w-1/2">
                    <div class="relative flex flex-grow">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kategori kejadian..."
                            class="block w-full p-2 pl-10 text-sm text-gray-900 bg-white border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500" />
                        <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg">
                            Cari
                        </button>
                    </div>
                    @if (request('search'))
                        <a href="{{ route('kategori-kejadian.index') }}"
                            class="px-4 py-2 text-sm text-white bg-gray-400 hover:bg-gray-500 rounded-lg whitespace-nowrap">
                            Kembali
                        </a>
                    @endif
                </form>

                <button type="button" data-modal-target="tambahModal"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg">
                    Tambah Kategori
                </button>
            </div>
        </div>

        {{-- Modal Tambah Kategori Kejadian --}}
        <div id="tambahModal" tabindex="-1" aria-hidden="true"
            class="hidden fixed top-0 left-0 right-0 z-50 items-center justify-center w-full h-full bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
                <div class="p-4 border-b">
                    <h3 class="text-left text-lg font-semibold">Tambah Kategori Kejadian</h3>
                </div>
                <form action="{{ route('kategori-kejadian.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    <div>
                        <label for="nama_kategori" class="block mb-1 text-left text-sm font-medium text-gray-700">
                            Nama Kategori
                        </label>
                        <input type="text" id="nama_kategori" name="nama_kategori" required
                            placeholder="Nama Kategori Kejadian"
                            class="mt-1 w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300">
                    </div>
                    <div>
                        <label for="kirim_notifikasi" class="block mb-1 text-sm font-medium text-left text-gray-700">
                            Kirim Notifikasi?
                        </label>
                        <select id="kirim_notifikasi" name="kirim_notifikasi" required
                            class="mt-1 w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300">
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" data-modal-hide="tambahModal"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">
                            Tambah
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg mb-4">
            <table class="table-auto w-full text-sm text-gray-700">
                <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Kategori</th>
                        <th class="px-4 py-3">Notifikasi</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($kategori as $data)
                        <tr class="hover:bg-gray-100 text-center">
                            <td class="px-4 py-2">
                                {{ $loop->iteration + ($kategori->currentPage() - 1) * $kategori->perPage() }}</td>
                            <td class="px-4 py-2 text-left">{{ $data->nama_kategori }}</td>
                            <td class="px-4 py-2">
                                <span
                                    class="inline-block px-2 py-1 text-xs rounded-full 
                                    {{ $data->kirim_notifikasi ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                    {{ $data->kirim_notifikasi ? 'Ya' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex justify-center gap-2">
                                    {{-- Edit --}}
                                    <button type="button" data-modal-target="editModal{{ $data->id }}"
                                        class="inline-flex items-center px-2 py-1 text-sm text-white bg-yellow-400 hover:bg-yellow-500 rounded">
                                        <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0
                                            01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                        </svg>
                                        Edit
                                    </button>

                                    {{-- Modal Edit Kategori Kejadian --}}
                                    <div id="editModal{{ $data->id }}" tabindex="-1" aria-hidden="true"
                                        class="hidden fixed top-0 left-0 right-0 z-50 items-center justify-center w-full h-full bg-black bg-opacity-50 overflow-y-auto">
                                        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto mt-10 mb-10">
                                            <div class="p-4 border-b">
                                                <h3 class="text-left text-lg font-semibold text-gray-800">Edit Kategori Kejadian
                                                </h3>
                                            </div>
                                            <form
                                                action="{{ route('kategori-kejadian.update', ['kategoriKejadian' => $data->id]) }}"
                                                method="POST" class="p-6 space-y-5">
                                                @csrf
                                                @method('PUT')
                                                <div>
                                                    <label for="nama_kategori_{{ $data->id }}"
                                                        class="block mb-1 text-left text-sm font-medium text-gray-700">
                                                        Nama Kategori
                                                    </label>
                                                    <input type="text" id="nama_kategori_{{ $data->id }}"
                                                        name="nama_kategori" value="{{ $data->nama_kategori }}"
                                                        required
                                                        class="mt-1 w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300">
                                                </div>
                                                <div>
                                                    <label for="kirim_notifikasi_{{ $data->id }}"
                                                        class="block mb-1 text-left text-sm font-medium text-gray-700">
                                                        Kirim Notifikasi?
                                                    </label>
                                                    <select id="kirim_notifikasi_{{ $data->id }}"
                                                        name="kirim_notifikasi" required
                                                        class="mt-1 w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300">
                                                        <option value="1"
                                                            {{ $data->kirim_notifikasi ? 'selected' : '' }}>Ya</option>
                                                        <option value="0"
                                                            {{ !$data->kirim_notifikasi ? 'selected' : '' }}>Tidak
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="flex justify-end gap-3 pt-4">
                                                    <button type="button"
                                                        data-modal-hide="editModal{{ $data->id }}"
                                                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">
                                                        Batal
                                                    </button>
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">
                                                        Simpan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- Hapus --}}
                                    <form
                                        action="{{ route('kategori-kejadian.destroy', ['kategoriKejadian' => $data->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="btn-hapus inline-flex items-center px-2 py-1 text-sm text-white bg-red-600 hover:bg-red-700 rounded"
                                            data-nama="{{ $data->nama_kategori }}">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
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
                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex justify-between items-center bg-white px-2 py-3">
            <div class="text-sm text-gray-600">
                Menampilkan {{ $kategori->firstItem() ?? 0 }} sampai {{ $kategori->lastItem() ?? 0 }} dari
                {{ $kategori->total() }} data
            </div>
            <div>
                {{ $kategori->links('components.pagination') }}
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Script --}}
<script>
    document.querySelectorAll("[data-modal-target]").forEach(btn => {
        btn.addEventListener("click", () => {
            const modalId = btn.dataset.modalTarget;
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove("hidden");
                modal.classList.add("flex");
            }
        });
    });

    document.querySelectorAll("[data-modal-hide]").forEach(btn => {
        btn.addEventListener("click", () => {
            const modalId = btn.dataset.modalHide;
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove("flex");
                modal.classList.add("hidden");
            }
        });
    });

    document.querySelectorAll('[id^="tambahModal"], [id^="editModal"]').forEach(modal => {
        modal.addEventListener('click', e => {
            if (e.target === modal) {
                modal.classList.remove("flex");
                modal.classList.add("hidden");
            }
        });
    });

    document.querySelectorAll('.btn-hapus').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            const nama = this.getAttribute('data-nama') || 'data ini';

            Swal.fire({
                title: 'Hapus Data?',
                text: `Yakin ingin menghapus kategori kejadian "${nama}?"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
