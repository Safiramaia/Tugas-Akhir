<x-app-layout :title="'Scan QR Lokasi Patroli'">
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-2 text-gray-800 text-center">Scan QR Patroli</h2>

        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md max-w-xl mx-auto">
            {{-- Area kamera untuk Scan QR --}}
            <div id="reader" class="w-full border rounded-md"></div>

            {{-- Menampilkan hasil setelah QR discan --}}
            <div id="result" class="mt-4 text-center font-semibold text-blue-600"></div>
        </div>
    </div>
</x-app-layout>

{{-- Memuat library HTML5 QR Code --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const resultContainer = document.getElementById('result');
    const html5QrCode = new Html5Qrcode("reader");

    //Konfigurasi scanner dengan kamera belakang
    const config = {
        fps: 10,
        qrbox: { width: 300, height: 300 },
        videoConstraints: {
            facingMode: "environment"
        }
    };

    // Callback saat QR berhasil discan
    function onScanSuccess(decodedText, decodedResult) {
        html5QrCode.stop().then(() => {
            resultContainer.innerText = `QR Berhasil: ${decodedText}`;

            // Jika QR berisi URL, langsung diarahkan ke URL tersebut
            if (decodedText.startsWith('http://') || decodedText.startsWith('https://')) {
                window.location.href = decodedText;
            } else {
                alert('QR code tidak valid!');
            }
        });
    }

    // Menjalankan scanner saat halaman dibuka
    html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
        .catch(err => {
            alert("Gagal memulai kamera : " + err);
        });
});
</script>
