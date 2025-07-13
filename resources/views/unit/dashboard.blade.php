<x-app-layout :title="'Dashboard Unit'">
    {{-- RINGKASAN JUMLAH --}}
    <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2 lg:grid-cols-3">
        {{-- Total Patroli --}}
        <div class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100">
                <svg class="w-7 h-7 text-blue-600" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path d="M256 0c4.6 0 9.2 1 13.4 2.9L457.7 82.8c22 9.3 38.4 31 38.3 57.2c-.5 99.2-41.3 280.7-213.6 363.2c-16.7 8-36.1 8-52.8 0C57.3 420.7 16.5 239.2 16 140c-.1-26.2 16.3-47.9 38.3-57.2L242.7 2.9C246.8 1 251.4 0 256 0zm0 66.8v378.1C394 378 431.1 230.1 432 141.4L256 66.8z"/>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-base font-semibold text-gray-500">Total Patroli</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $totalPatroli }}</p>
            </div>
        </div>

        {{-- Patroli Aman --}}
        <div class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100">
                <svg class="w-7 h-7 text-green-600" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64h320c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zM337 209L209 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47 94-94c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-base font-semibold text-gray-500">Status Aman</h3>
                <p class="text-2xl font-bold text-green-600">{{ $statusPatroli['aman'] ?? 0 }}</p>
            </div>
        </div>

        {{-- Patroli Darurat --}}
        <div class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100">
                <svg class="w-7 h-7 text-red-600" fill="currentColor" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                    <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-base font-semibold text-gray-500">Status Darurat</h3>
                <p class="text-2xl font-bold text-red-600">{{ $statusPatroli['darurat'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    {{-- GRAFIK AKTIVITAS PATROLI --}}
    <div class="mt-8 bg-white p-6 rounded-lg shadow-lg border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-700">Aktivitas Patroli</h3>
            <select id="filter" class="border border-gray-300 rounded-lg shadow-sm text-sm">
                <option value="harian" selected>Harian</option>
                <option value="mingguan">Mingguan</option>
                <option value="bulanan">Bulanan</option>
            </select>
        </div>
        <div class="relative h-72">
            <canvas id="patroliChart" class="w-full h-full"></canvas>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            }
        }
    });

    document.getElementById('filter').addEventListener('change', function () {
        const selected = this.value;
        currentFilter = selected;
        patroliChart.data.labels = dataSet[selected].labels;
        patroliChart.data.datasets[0].data = dataSet[selected].data;
        patroliChart.update();
    });
</script>
