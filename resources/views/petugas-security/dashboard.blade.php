<x-app-layout :title="'Dashboard Petugas Security'">

    <div class="max-w-7xl mx-auto p-2 space-y-2">
        {{-- Notifikasi jika masih ada lokasi yang belum dipatroli --}}
        @if ($jumlahBelum > 0)
            <div class="mt-6 mb-2 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-800 rounded-lg shadow-md">
                <strong>Perhatian!</strong> Masih ada <strong>{{ $jumlahBelum }}</strong> lokasi yang belum dipatroli
                hari
                ini.
                Segera lakukan patroli untuk menyelesaikan tugas hari ini.
            </div>
        @endif

        {{-- Notifikasi jika tidak ada jadwal patroli --}}
        @if ($totalLokasi == 0)
            <div class="mt-6 mb-2 p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-800 rounded-lg shadow-md">
                <strong>Informasi :</strong> Hari ini kamu tidak memiliki jadwal patroli.
            </div>
        @endif

        <div>
            <h1 class="text-2xl font-semibold text-gray-800 mt-6 mb-2">Selamat datang, {{ Auth::user()->nama }}</h1>
            <p class="text-sm text-gray-600">Berikut adalah ringkasan kegiatan patrolimu hari ini.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Status Patroli --}}
            <div class="p-5 bg-white border border-gray-200 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Status Patroli Hari Ini</h2>

                {{-- Ringkasan jumlah lokasi --}}
                <div class="space-y-1 text-sm text-gray-800">
                    <p class="flex space-x-1">
                        <span class="w-40 text-left">Total titik lokasi</span>
                        <span>:</span>
                        <strong>{{ $totalLokasi }}</strong>
                    </p>
                    <p class="flex space-x-1">
                        <span class="w-40 text-left">Lokasi sudah dipatroli</span>
                        <span>:</span>
                        <strong class="text-green-600">{{ $jumlahSelesai }}</strong>
                    </p>
                    <p class="flex space-x-1">
                        <span class="w-40 text-left">Lokasi belum dipatroli</span>
                        <span>:</span>
                        <strong class="text-red-700">{{ $jumlahBelum }}</strong>
                    </p>
                </div>

                {{-- Progress bar --}}
                <div class="mt-4">
                    <div class="relative h-4 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 transition-all duration-300 ease-in-out"
                            style="width: {{ $persentase }}%"></div>
                        <div
                            class="absolute inset-0 flex items-center justify-center text-xs font-medium text-gray-800">
                            {{ number_format($persentase, 1) }}%
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aktivitas Terakhir --}}
            <div class="p-5 bg-white border border-gray-200 rounded-lg shadow-md max-h-64 overflow-y-auto">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Aktivitas Terakhir</h2>
                <ul class="space-y-3 text-sm text-gray-700">
                    @forelse ($aktivitasTerakhir as $patroli)
                        <li class="flex items-start space-x-2">
                            <svg class="w-5 h-5 mr-2 text-green-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 448 512" fill="currentColor">
                                <path
                                    d="M64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zM337 209L209 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L303 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" />
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm leading-tight break-words">
                                    <span class="font-medium text-gray-800">{{ $patroli->waktu_patroli }}</span> -
                                    <span>{{ $patroli->lokasiPatroli->nama_lokasi }}</span>
                                </p>
                            </div>
                        </li>
                    @empty
                        <li class="text-gray-500">Belum ada aktivitas hari ini.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- Tabel Lokasi Belum Dipatroli --}}
        <div class="mt-12 bg-white p-4">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Daftar Lokasi Belum Dipatroli</h2>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="table-auto w-full text-sm text-gray-700">
                    <thead class="bg-blue-200 text-gray-800 uppercase text-xs">
                        <tr class="text-center">
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3 text-left">Nama Lokasi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($belumDipatroli as $index => $lokasi)
                            <tr class="hover:bg-gray-100 text-center">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 text-left">{{ $lokasi->nama_lokasi }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-4 text-center text-gray-500">Semua lokasi sudah
                                    dipatroli.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
