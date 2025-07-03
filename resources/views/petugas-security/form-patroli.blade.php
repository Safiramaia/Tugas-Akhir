<x-app-layout :title="'Form Patroli'">
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Form Patroli</h2>

        <div class="max-w-full md:max-w-4xl bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <form action="{{ route('patroli.store') }}" method="POST" class="space-y-6">
                @csrf

                <input type="hidden" name="lokasi_id" value="{{ $lokasiPatroli->id }}">
                {{-- <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude"> --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Lokasi Patroli</label>
                        <input type="text" value="{{ $lokasiPatroli->nama_lokasi ?? 'Lokasi tidak ditemukan' }}"
                            readonly
                            class="mt-1 block w-full rounded-lg border border-blue-300 shadow-sm bg-blue-100 text-gray-700 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Patroli</label>
                        <input type="text" value="{{ now()->toDateString() }}" readonly
                            class="mt-1 block w-full rounded-lg border border-blue-300 shadow-sm bg-blue-100 text-gray-700 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Waktu Patroli</label>
                        <input type="text" value="{{ now()->format('H:i:s') }}" readonly
                            class="mt-1 block w-full rounded-lg border border-blue-300 shadow-sm bg-blue-100 text-gray-700 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-600">*</span>
                        </label>
                        <div class="flex items-center mb-2">
                            <input id="status-aman" type="radio" name="status" value="aman" required
                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <label for="status-aman" class="ml-2 text-sm text-gray-700">Aman</label>
                        </div>
                        <div class="flex items-center">
                            <input id="status-darurat" type="radio" name="status" value="darurat" required
                                class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                            <label for="status-darurat" class="ml-2 text-sm text-gray-700">Darurat</label>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Keterangan <span class="text-red-600">*</span>
                        </label>
                        <textarea name="keterangan" rows="3"
                            class="mt-1 block w-full rounded-lg border border-gray-300 shadow-sm bg-white focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ambil Foto <span class="text-red-600">*</span>
                        </label>

                        <div class="w-full border border-gray-200 rounded-lg p-4 bg-gray-50 flex flex-col items-center">
                            <video id="video" class="w-full rounded border" autoplay
                                style="aspect-ratio: 16 / 9; object-fit: cover;"></video>

                            <img id="preview" class="w-full rounded border border-gray-200 hidden mt-2"
                                style="aspect-ratio: 16 / 9; object-fit: cover;" />

                            <p id="infoText" class="text-center text-gray-600 mt-2 hidden">
                                Jika foto kurang pas, kamu bisa <strong>ambil ulang</strong> ya.
                            </p>

                            <button type="button" id="takePhotoBtn"
                                class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold w-full">
                                Ambil Foto
                            </button>

                            <button type="button" id="retakePhotoBtn"
                                class="mt-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-semibold w-full hidden">
                                Ambil Ulang Foto
                            </button>
                        </div>

                        <canvas id="canvas" style="display:none;"></canvas>
                        <input type="hidden" name="foto" id="fotoInput">
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('petugas-security.dashboard') }}"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg font-semibold">
                        Kembali
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                        Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>

<script>
    const video = document.getElementById('video');
    const preview = document.getElementById('preview');
    const infoText = document.getElementById('infoText');
    const takePhotoBtn = document.getElementById('takePhotoBtn');
    const retakePhotoBtn = document.getElementById('retakePhotoBtn');

    //Fungsi untuk memulai kamera
    function startCamera() {
        navigator.mediaDevices.enumerateDevices().then(devices => {
            const videoDevices = devices.filter(device => device.kind === 'videoinput');

            // Mencari kamera belakang atau environment
            const backCamera = videoDevices.find(device =>
                device.label.toLowerCase().includes('back') ||
                device.label.toLowerCase().includes('environment')
            );

            const selectedDeviceId = backCamera ? backCamera.deviceId : videoDevices[0]?.deviceId;

            if (!selectedDeviceId) {
                alert("Tidak ada kamera yang ditemukan.");
                return;
            }

            navigator.mediaDevices.getUserMedia({
                video: {
                    deviceId: {
                        exact: selectedDeviceId
                    }
                }
            }).then(stream => {
                video.srcObject = stream;
                video.classList.remove('hidden');
                preview.classList.add('hidden');
                infoText.classList.add('hidden');
                takePhotoBtn.classList.remove('hidden');
                retakePhotoBtn.classList.add('hidden');
            }).catch(err => {
                console.error("Gagal mengakses kamera:", err);
                alert("Tidak bisa mengakses kamera.");
            });
        }).catch(err => {
            console.error("Gagal mendapatkan daftar perangkat:", err);
        });
    }

    startCamera();

    //Mengambil foto dan menampilkan preview
    takePhotoBtn.addEventListener('click', () => {
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataURL = canvas.toDataURL('image/jpeg');
        document.getElementById('fotoInput').value = dataURL;

        //Menampilkan preview dan info, sembunyikan video dan tombol ambil foto
        video.classList.add('hidden');
        preview.src = dataURL;
        preview.classList.remove('hidden');
        infoText.classList.remove('hidden');
        takePhotoBtn.classList.add('hidden');
        retakePhotoBtn.classList.remove('hidden');
    });

    //Event listener untuk tombol ambil ulang foto
    retakePhotoBtn.addEventListener('click', () => {
        startCamera();
    });

    // document.addEventListener("DOMContentLoaded", function() {
    //     if (navigator.geolocation) {
    //         navigator.geolocation.getCurrentPosition(
    //             function(position) {
    //                 document.getElementById('latitude').value = position.coords.latitude;
    //                 document.getElementById('longitude').value = position.coords.longitude;
    //             },
    //             function(error) {
    //                 alert('Gagal mendapatkan lokasi. Pastikan GPS diaktifkan.');
    //             }
    //         );
    //     } else {
    //         alert("Browser Anda tidak mendukung geolocation.");
    //     }
    // });
</script>
