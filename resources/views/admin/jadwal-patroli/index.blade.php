<x-app-layout :title="'Data Jadwal Patroli'">
    <div class="container mx-auto p-4">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                Jadwal Patroli Bulan {{ $currentMonth->translatedFormat('F Y') }}
            </h2>
            <div
                class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 border border-gray-200 rounded-lg shadow-md p-4">

                {{-- Filter Bulan --}}
                <form method="GET" action="{{ route('jadwal-patroli.index') }}" class="w-full sm:w-auto">
                    <input type="month" name="month" value="{{ $currentMonth->format('Y-m') }}"
                        onchange="this.form.submit()"
                        class="border border-gray-300 rounded px-4 py-2 text-sm text-gray-700 shadow-sm w-full min-w-[150px] sm:min-w-[180px]">
                </form>

                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    {{-- Generate Ulang --}}
                    <form action="{{ route('jadwal-patroli.generate-ulang') }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <input type="hidden" name="month" value="{{ $currentMonth->format('Y-m') }}">
                        <button type="submit"
                            class="w-full min-w-[150px] sm:min-w-[180px] whitespace-nowrap px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg text-sm font-semibold text-center">
                            Generate Ulang
                        </button>
                    </form>

                    {{-- Tambah Jadwal --}}
                    <button data-modal-target="tambahModal" data-modal-toggle="tambahModal"
                        class="w-full min-w-[150px] sm:min-w-[180px] whitespace-nowrap px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold text-center">
                        Tambah Jadwal
                    </button>
                    {{-- Modal Tambah Jadwal --}}
                    <div id="tambahModal" tabindex="-1" aria-hidden="true"
                        class="hidden fixed top-0 left-0 right-0 z-50 items-center justify-center w-full h-full bg-black bg-opacity-50">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
                            <div class="p-4 border-b">
                                <h3 class="text-lg font-semibold">Tambah Jadwal Patroli</h3>
                            </div>
                            <form action="{{ route('jadwal-patroli.store') }}" method="POST" class="p-6 space-y-5">
                                @csrf
                                <div>
                                    <label for="user_id" class="block mb-1 text-sm font-medium text-gray-700">
                                        Petugas
                                    </label>
                                    <select id="user_id" name="user_id" required
                                        class="mt-1 w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300">
                                        <option value="">-- Pilih Petugas --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="tanggal" class="block mb-1 text-sm font-medium text-gray-700">
                                        Tanggal
                                    </label>
                                    <input type="date" id="tanggal" name="tanggal" required
                                        class="mt-1 w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300">
                                </div>
                                <div class="flex justify-end gap-3 pt-4">
                                    <button type="button" data-modal-hide="tambahModal"
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

                    {{-- Cetak --}}
                    <form action="{{ route('jadwal-patroli.cetak-jadwal') }}" method="GET" class="w-full sm:w-auto">
                        <input type="hidden" name="month" value="{{ $currentMonth->format('Y-m') }}">
                        <button type="submit"
                            class="inline-flex items-center justify-center w-full min-w-[150px] sm:min-w-[180px] whitespace-nowrap px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold text-center">
                            <svg class="w-4 h-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512" fill="currentColor" aria-hidden="true">
                                <path
                                    d="M128 0C92.7 0 64 28.7 64 64l0 96 64 0 0-96 226.7 0L384 93.3l0 66.7 64 0 0-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0L128 0zM384 352l0 32 0 64-256 0 0-64 0-16 0-16 256 0zm64 32l32 0c17.7 0 32-14.3 32-32l0-96c0-35.3-28.7-64-64-64L64 192c-35.3 0-64 28.7-64 64l0 96c0 17.7 14.3 32 32 32l32 0 0 64c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
                            </svg>
                            Cetak
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kalender --}}
        <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-lg">
            <div class="min-w-[700px] grid grid-cols-7 gap-px text-center text-sm select-none">
                {{-- Header Hari --}}
                @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                    <div class="font-semibold py-2 bg-blue-200 text-gray-800 text-xs uppercase border border-gray-200">
                        {{ $day }}
                    </div>
                @endforeach

                @php
                    $startOfMonth = $currentMonth->copy()->startOfMonth();
                    $endOfMonth = $currentMonth->copy()->endOfMonth();
                    $startDayOfWeek = $startOfMonth->dayOfWeek;
                    $daysInMonth = $currentMonth->daysInMonth;
                @endphp

                {{-- Sel kosong sebelum tanggal 1 --}}
                @for ($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="border border-gray-200 h-24 bg-gray-100"></div>
                @endfor

                @php
                    $warnaPetugas = [];
                    $daftarWarna = ['bg-red-400', 'bg-yellow-400', 'bg-blue-400', 'bg-orange-400', 'bg-purple-400'];

                    foreach ($users as $index => $user) {
                        $warnaPetugas[$user->id] = $daftarWarna[$index % count($daftarWarna)];
                    }
                @endphp

                {{-- Tanggal dan Jadwal --}}
                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $date = $currentMonth->copy()->day($day)->format('Y-m-d');
                        $jadwalHariIni = $jadwalPatroli[$date] ?? [];
                    @endphp

                    @php
                        $bgColor = !empty($jadwalHariIni)
                            ? $warnaPetugas[$jadwalHariIni[0]->user_id] ?? 'bg-white'
                            : 'bg-white';
                    @endphp

                    <div
                        class="border border-gray-200 p-2 h-24 sm:h-28 text-left relative group {{ $bgColor }} hover:bg-blue-50 rounded transition-all overflow-hidden">
                        <div class="text-sm font-semibold text-gray-800">{{ $day }}</div>

                        @if (!empty($jadwalHariIni))
                            @php $jadwal = $jadwalHariIni[0]; @endphp
                            <div class="text-sm mt-1 text-gray-900 truncate"
                                title="{{ $jadwal->user->nama ?? 'Tidak ditemukan' }}">
                                {{ $jadwal->user->nama ?? 'Tidak ditemukan' }}
                            </div>
                        @else
                            <span class="text-gray-400 text-xs italic mt-1 block">Belum dijadwalkan</span>
                        @endif

                        @if (!empty($jadwalHariIni))
                            <div class="absolute top-1 right-1 hidden group-hover:flex flex-col gap-1 z-20">
                                <div class="flex space-x-1">
                                    {{-- Edit Jadwal --}}
                                    <button data-modal-target="editModal{{ $jadwal->id }}"
                                        class="w-7 h-7 flex items-center justify-center text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded-lg"
                                        type="button" aria-label="Edit jadwal {{ $jadwal->user->nama }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd"
                                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    {{-- Modal Edit Jadwal --}}
                                    <div id="editModal{{ $jadwal->id }}" tabindex="-1"
                                        class="hidden fixed top-0 left-0 right-0 z-50 w-full h-full items-center justify-center bg-black bg-opacity-50">
                                        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
                                            <div class="p-4 border-b">
                                                <h3 class="text-lg font-semibold">Edit Jadwal Patroli</h3>
                                            </div>
                                            <form action="{{ route('jadwal-patroli.update', $jadwal->id) }}"
                                                method="POST" class="p-6 space-y-5">
                                                @csrf
                                                @method('PUT')
                                                <div>

                                                    <label for="user_id_edit_{{ $jadwal->id }}"
                                                        class="block mb-1 text-sm font-medium text-gray-700">Petugas</label>
                                                    <select id="user_id_edit_{{ $jadwal->id }}" name="user_id"
                                                        required
                                                        class="mt-1 w-full border border-gray-300 rounded-lg shadow-sm text-sm px-3 py-2 focus:ring focus:ring-blue-300">
                                                        <option value="">--- Pilih Petugas ---</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}"
                                                                {{ $jadwal->user_id == $user->id ? 'selected' : '' }}>
                                                                {{ $user->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="flex justify-end gap-3 pt-4">
                                                    <button type="button"
                                                        data-modal-hide="editModal{{ $jadwal->id }}"
                                                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">
                                                        Batal
                                                    </button>
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">
                                                        Perbarui
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    {{-- Hapus Jadwal --}}
                                    <form action="{{ route('jadwal-patroli.destroy', $jadwal->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="w-7 h-7 flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-lg btn-hapus"
                                            data-nama="{{ $jadwal->user->nama }}" title="Hapus Jadwal"
                                            aria-label="Hapus jadwal {{ $jadwal->user->nama }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                @endfor
                {{-- Sel kosong setelah akhir bulan --}}
                @php
                    $totalCells = $startDayOfWeek + $daysInMonth;
                    $remainingCells = 7 - ($totalCells % 7);
                    if ($remainingCells < 7) {
                        for ($i = 0; $i < $remainingCells; $i++) {
                            echo '<div class="border border-gray-200 h-24 bg-gray-100"></div>';
                        }
                    }
                @endphp
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        //Untuk Membuka Modal
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

        //Untuk Menutup Modal
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

        //Untuk Menutup Modal Jika Klik di Luar Kontennya
        document.querySelectorAll('[id^="tambahModal"], [id^="editModal"]').forEach(modal => {
            modal.addEventListener('click', e => {
                if (e.target === modal) {
                    modal.classList.remove("flex");
                    modal.classList.add("hidden");
                }
            });
        });

        //Konfirmasi Hapus
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const nama = this.dataset.nama || 'jadwal ini';

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Apakah Anda yakin ingin menghapus jadwal patroli "${nama}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
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
