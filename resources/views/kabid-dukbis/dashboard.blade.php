<x-app-layout :title="'Dashboard Kabid Dukbis'">
    <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2 lg:grid-cols-3">
        {{-- Jumlah Petugas Security --}}
        <a href="{{ route('kabid-dukbis.data-petugas-security') }}">
            <div
                class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700 cursor-pointer">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100">
                    <svg class="w-7 h-7 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 640 512">
                        <path
                            d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c1.8 0 3.5-.2 5.3-.5c-76.3-55.1-99.8-141-103.1-200.2c-16.1-4.8-33.1-7.3-50.7-7.3l-91.4 0zm308.8-78.3l-120 48C358 277.4 352 286.2 352 296c0 63.3 25.9 168.8 134.8 214.2c5.9 2.5 12.6 2.5 18.5 0C614.1 464.8 640 359.3 640 296c0-9.8-6-18.6-15.1-22.3l-120-48c-5.7-2.3-12.1-2.3-17.8 0zM591.4 312c-3.9 50.7-27.2 116.7-95.4 149.7l0-187.8L591.4 312z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-base font-semibold text-gray-500">Jumlah Petugas Security</h3>
                    <p class="text-2xl font-bold text-blue-600 dark:text-white">{{ $jumlahPetugas }}</p>
                </div>
            </div>
        </a>

        {{-- Card Jumlah Lokasi Patroli --}}
        <a href="{{ route('kabid-dukbis.data-lokasi-patroli') }}">
            <div
                class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700 cursor-pointer">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"
                        fill="currentColor">
                        <path
                            d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-base font-semibold text-gray-500">Jumlah Lokasi Patroli</h3>
                    <p class="text-2xl font-bold text-blue-600 dark:text-white">{{ $jumlahLokasiPatroli }}</p>
                </div>
            </div>
        </a>

        {{-- Card Jumlah Laporan Patroli --}}
        <a href="{{ route('kabid-dukbis.laporan-patroli') }}">
            <div
                class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 dark:bg-gray-800 dark:border-gray-700 cursor-pointer">
                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100">
                    <svg class="w-6 h-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                        viewBox="0 0 576 512">
                        <path
                            d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 47-92.8 37.1c-21.3 8.5-35.2 29.1-35.2 52c0 56.6 18.9 148 94.2 208.3c-9 4.8-19.3 7.6-30.2 7.6L64 512c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zm39.1 97.7c5.7-2.3 12.1-2.3 17.8 0l120 48C570 277.4 576 286.2 576 296c0 63.3-25.9 168.8-134.8 214.2c-5.9 2.5-12.6 2.5-18.5 0C313.9 464.8 288 359.3 288 296c0-9.8 6-18.6 15.1-22.3l120-48zM527.4 312L432 273.8l0 187.8c68.2-33 91.5-99 95.4-149.7z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-base font-semibold text-gray-500">Jumlah Laporan Patroli</h3>
                    <p class="text-2xl font-bold text-blue-600 dark:text-white">{{ $jumlahLaporanPatroli }}</p>
                </div>
            </div>
        </a>

        {{-- Grafik Aktivitas Patroli --}}
        <div
            class="lg:col-span-3 mt-4 bg-white p-6 rounded-lg shadow border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-white">Aktivitas Patroli</h3>
                <select id="filter" class="border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="harian" selected>Harian</option>
                    <option value="mingguan">Mingguan</option>
                    <option value="bulanan">Bulanan</option>
                </select>
            </div>
            <div class="relative h-72">
                <canvas id="patroliChart" class="w-full h-full"></canvas>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const ctx = document.getElementById('patroliChart').getContext('2d');

    const dataSet = {
        harian: {
            labels: @json($harianLabels),
            data: @json($harianData)
        },
        mingguan: {
            labels: @json($mingguanLabels),
            data: @json($mingguanData)
        },
        bulanan: {
            labels: @json($bulananLabels),
            data: @json($bulananData)
        }
    };

    let currentFilter = 'harian';

    let patroliChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dataSet[currentFilter].labels,
            datasets: [{
                label: 'Jumlah Aktivitas Patroli',
                data: dataSet[currentFilter].data,
                backgroundColor: 'rgba(59, 130, 246, 0.5)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#111'
                    }
                }
            }
        }
    });

    document.getElementById('filter').addEventListener('change', function() {
        const selected = this.value;
        currentFilter = selected;

        patroliChart.data.labels = dataSet[selected].labels;
        patroliChart.data.datasets[0].data = dataSet[selected].data;
        patroliChart.update();
    });
</script>
