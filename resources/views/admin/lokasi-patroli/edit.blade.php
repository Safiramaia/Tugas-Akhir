<x-app-layout :title="'Edit Lokasi Patroli'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Lokasi Patroli</h2>

        <div class="max-w-full md:max-w-4xl bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <form action="{{ route('lokasi-patroli.update', $lokasiPatroli->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                {{-- Nama Lokasi --}}
                <div>
                    <label for="nama_lokasi" class="block text-sm font-medium text-gray-700">
                        Nama Lokasi <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="nama_lokasi" name="nama_lokasi"
                        value="{{ old('nama_lokasi', $lokasiPatroli->nama_lokasi) }}" required
                        class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    @error('nama_lokasi')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        {{-- Latitude --}}
                        <label for="latitude" class="block text-sm font-medium text-gray-700">
                            Latitude <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="latitude" name="latitude"
                            value="{{ old('latitude', $lokasiPatroli->latitude) }}" required readonly
                            class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm text-sm">
                        @error('latitude')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Longitude --}}
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700">
                            Longitude <span class="text-red-600">*</span>
                        </label>
                        <input type="text" id="longitude" name="longitude"
                            value="{{ old('longitude', $lokasiPatroli->longitude) }}" required readonly
                            class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm text-sm">
                        @error('longitude')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Map untuk memilih lokasi --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Lokasi Pada Map</label>
                    <div id="map" style="height: 300px;" class="rounded-lg border border-gray-300"></div>
                </div>

                <div class="flex justify-end gap-4 pt-2">
                    <a href="{{ route('lokasi-patroli.index') }}"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">Kembali</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-semibold">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

{{-- Map Leaflet --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .map-label {
        font-size: 12px;
        font-weight: bold;
        color: rgb(9, 98, 192);
        background: white;
        padding: 4px 8px;
        border-radius: 4px;
        border: 1px solid blue;
    }
</style>

<script>
    // Inisialisasi peta dengan posisi latitude dan longitude dari data yang sudah ada
    let currentLat = {{ old('latitude', $lokasiPatroli->latitude) }};
    let currentLng = {{ old('longitude', $lokasiPatroli->longitude) }};
    let defaultPos = [-7.673995661475888, 109.06239516931424];
    let map = L.map('map').setView([currentLat, currentLng], 19);

    //Layer peta OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 22,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // Marker yang bisa dipindah
    let marker = L.marker([currentLat, currentLng], {
        draggable: true
    }).addTo(map);

    // Area polygon sebagai batas lokasi
    let area = L.polygon([
        [-7.674295, 109.062095],
        [-7.674295, 109.062695],
        [-7.673695, 109.062695],
        [-7.673695, 109.062095]
    ], {
        color: 'blue',
        fillColor: '#cce5ff',
        fillOpacity: 0.2
    }).addTo(map);

    //Label tetap di pusat
    L.marker(defaultPos)
        .addTo(map)
        .bindTooltip("PT Sucofindo Cabang Cilacap", {
            permanent: true,
            direction: 'center',
            className: 'map-label'
        })
        .openTooltip();

    //Update titik latitude dan longitude saat marker dipindah
    marker.on('move', function(e) {
        document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
        document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
    });

    //Update marker saat map diklik dalam area
    map.on('click', function(e) {
        if (area.getBounds().contains(e.latlng)) {
            marker.setLatLng(e.latlng);
            document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
            document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Diluar Area',
                text: 'Klik hanya diperbolehkan dalam area PT Sucofindo Cabang Cilacap!',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }
    });
</script>
