<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SIM PATROLI - Selamat Datang</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="font-inter bg-white text-gray-800">

    <header class="bg-white shadow-md fixed top-0 left-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <img class="h-auto w-20" src="{{ asset('assets/sucofindo.png') }}" alt="Logo SUCOFINDO" />
            </div>

            <!-- Tombol login + hamburger di mobile -->
            <div class="flex items-center space-x-4 md:hidden">
                <!-- Tombol Login (mobile only) -->
                <a href="{{ route('login') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-full transition text-sm">
                    Login
                </a>

                <!-- Hamburger button -->
                <button id="menu-toggle" class="text-gray-800 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Nav Menu (desktop only) -->
            <nav class="hidden md:flex space-x-8 text-base font-medium text-gray-800">
                <a href="#tentang" class="hover:text-blue-600 transition">Tentang</a>
                <a href="#visi-misi" class="hover:text-blue-600 transition">Visi & Misi</a>
                <a href="#fitur" class="hover:text-blue-600 transition">Fitur</a>
                <a href="#kontak" class="hover:text-blue-600 transition">Kontak</a>
            </nav>

            <!-- Tombol login (desktop only) -->
            @if (Route::has('login'))
                <nav class="hidden md:inline-block">
                    @auth
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-normal px-5 py-2 rounded-full transition">
                                Dashboard Admin
                            </a>
                        @elseif (auth()->user()->role === 'petugas_security')
                            <a href="{{ route('petugas-security.dashboard') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-normal px-5 py-2 rounded-full transition">
                                Dashboard Petugas Security
                            </a>
                        @elseif (auth()->user()->role === 'kabid_dukbis')
                            <a href="{{ route('kabid-dukbis.dashboard') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-normal px-5 py-2 rounded-full transition">
                                Dashboard Kabid Dukbis
                            </a>
                        @elseif (auth()->user()->role === 'unit')
                            <a href="{{ route('unit.dashboard') }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-normal px-5 py-2 rounded-full transition">
                                Dashboard Unit
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-full shadow transition">
                            Login
                        </a>
                    @endauth
                </nav>
            @endif
        </div>

        <!-- Mobile Menu (toggle content) -->
        <div id="mobile-menu" class="md:hidden hidden px-6 pb-4 space-y-2">
            <a href="#tentang" class="block text-gray-800 hover:text-blue-600">Tentang</a>
            <a href="#visi-misi" class="block text-gray-800 hover:text-blue-600">Visi & Misi</a>
            <a href="#fitur" class="block text-gray-800 hover:text-blue-600">Fitur</a>
            <a href="#kontak" class="block text-gray-800 hover:text-blue-600">Kontak</a>
        </div>
    </header>

    <!-- Hero Section -->
     <section class="bg-gray-50 flex items-center justify-center px-12 py-[72px] md:py-24 lg:py-32">
        <div class="max-w-6xl mx-auto flex flex-col-reverse md:flex-row items-center gap-10">
            <!-- Text Content -->
            <div class="md:w-1/2 text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-extrabold text-blue-600 leading-tight mb-6">
                    Selamat Datang di SIM PATROLI</span>
                </h1>
                <p class="text-gray-700 text-lg mb-6">
                    SIM PATROLI adalah sistem berbasis website untuk memantau
                    aktivitas patroli petugas security secara real-time, efisien,
                    dan akurat di lingkungan kerja PT SUCOFINDO Cilacap.
                </p>
                <a href="{{ route('login') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-full shadow transition">
                    Masuk Sekarang
                </a>
            </div>
            <!-- Logo Image -->
            <div class="md:w-1/2 flex justify-center">
                <img class="w-64 h-auto" src="{{ asset('assets/logo-shield.png') }}" alt="Logo SIM PATROLI" />
            </div>
        </div>
    </section>

    <!-- Tentang SUCOFINDO -->
    <section id="tentang" class="py-10 px-10 bg-gray-50">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
            <!-- Gambar -->
            <div class="flex justify-center">
                <img src="{{ asset('assets/gedung-sucofindo.jpg') }}" alt="Gedung SUCOFINDO"
                    class="rounded-xl shadow-lg w-full max-w-2xl h-[360px] object-cover">
            </div>


            <!-- Teks Tentang -->
            <div>
                <h2 class="text-2xl font-bold text-blue-700 mb-6 text-center md:text-left">Sejarah Singkat PT SUCOFINDO
                </h2>
                <p class="text-lg text-gray-700 text-justify leading-relaxed mb-4">
                    PT SUCOFINDO (Persero) adalah Badan Usaha Milik Negara (BUMN) yang bergerak di bidang inspeksi,
                    pengujian, sertifikasi, konsultansi, dan pelatihan. Dengan pengalaman lebih dari 60 tahun, SUCOFINDO
                    mendukung
                    pertumbuhan industri nasional melalui layanan yang inovatif, andal, dan terpercaya.
                </p>
                <p class="text-lg text-gray-700 text-justify leading-relaxed">
                    PT Superintending Company of Indonesia (SUCOFINDO) didirikan pada tanggal 22 Oktober 1956 sebagai
                    perusahaan inspeksi pertama di Indonesia. Perusahaan ini merupakan hasil kerja sama antara
                    Pemerintah Republik
                    Indonesia dan SGS Geneva, dan kini menjadi pemimpin jasa TIC (Testing, Inspection, Certification) di
                    Indonesia.
                </p>
            </div>
        </div>
    </section>

    <!-- Visi dan Misi -->
    <section id="visi-misi" class="py-10 px-4 bg-blue-50">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-2xl font-bold text-blue-700 mb-10 text-center">Visi & Misi PT SUCOFINDO</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-left">
                <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold text-blue-600 mb-2">Visi</h3>
                    <p class="text-gray-600 text-justify">
                        Menjadi Perusahaan Kelas Dunia yang kompetitif, andal dan terpercaya di bidang inspeksi,
                        pengujian, sertifikasi, konsultasi, dan pelatihan.
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold text-blue-600 mb-2">Misi</h3>
                    <p class="text-gray-600 text-justify">
                        Menciptakan nilai ekonomi kepada para pemangku kepentingan terutama pelanggan, pemegang saham
                        dan pegawai melalui layanan jasa inspeksi, pengujian, sertifikasi, konsultansi serta jasa
                        terkait
                        lainnya untuk menjamin kepastian berusaha.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Unggulan -->
    <section id="fitur" class="py-10 px-4 bg-blue-50">
        <div class="max-w-6xl mx-auto text-center">
            <h2 class="text-2xl font-bold text-blue-700 mb-10">Fitur Unggulan SIM PATROLI</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-left">
                <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition text-center">
                    <div class="text-blue-600 mb-4">
                        <svg class="mx-auto w-10 h-10 fill-current" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 384 512">
                            <path
                                d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-blue-600 mb-2">Scan QR Lokasi</h3>
                    <p class="text-gray-600">
                        Petugas memindai QR Code di setiap titik lokasi untuk validasi kehadiran secara otomatis.
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition text-center">
                    <div class="text-blue-600 mb-4">
                        <svg class="mx-auto w-10 h-10 fill-current" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 448 512">
                            <path
                                d="M128 0c17.7 0 32 14.3 32 32l0 32 128 0 0-32c0-17.7 14.3-32 32-32s32 14.3 32 32l0 32 48 0c26.5 0 48 21.5 48 48l0 48L0 160l0-48C0 85.5 21.5 64 48 64l48 0 0-32c0-17.7 14.3-32 32-32zM0 192l448 0 0 272c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 192zm64 80l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm128 0l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zM64 400l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16zm144-16c-8.8 0-16 7.2-16 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0zm112 16l0 32c0 8.8 7.2 16 16 16l32 0c8.8 0 16-7.2 16-16l0-32c0-8.8-7.2-16-16-16l-32 0c-8.8 0-16 7.2-16 16z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-blue-600 mb-2">Jadwal & Reminder</h3>
                    <p class="text-gray-600">
                        Admin membuat jadwal patroli, dan sistem mengirimkan pengingat kepada petugas sesuai waktu
                        tugas.
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition text-center">
                    <div class="text-blue-600 mb-4">
                        <svg class="mx-auto w-10 h-10 fill-current" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 576 512">
                            <path
                                d="M0 64C0 28.7 28.7 0 64 0L224 0l0 128c0 17.7 14.3 32 32 32l128 0 0 47-92.8 37.1c-21.3 8.5-35.2 29.1-35.2 52c0 56.6 18.9 148 94.2 208.3c-9 4.8-19.3 7.6-30.2 7.6L64 512c-35.3 0-64-28.7-64-64L0 64zm384 64l-128 0L256 0 384 128zm39.1 97.7c5.7-2.3 12.1-2.3 17.8 0l120 48C570 277.4 576 286.2 576 296c0 63.3-25.9 168.8-134.8 214.2c-5.9 2.5-12.6 2.5-18.5 0C313.9 464.8 288 359.3 288 296c0-9.8 6-18.6 15.1-22.3l120-48zM527.4 312L432 273.8l0 187.8c68.2-33 91.5-99 95.4-149.7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-blue-600 mb-2">Laporan & Insiden</h3>
                    <p class="text-gray-600">
                        Petugas mengisi form patroli dan melaporkan kejadian darurat langsung ke admin via Whatsapp.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="py-10 px-4 bg-white">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-2xl font-bold text-blue-700 mb-2">Kontak</h2>
            <p class="text-gray-700 text-lg mb-4">
                Hubungi kami untuk informasi lebih lanjut.
            </p>
            <div class="grid md:grid-cols-3 gap-6 text-center mt-8 max-w-none mx-auto">
                <div>
                    <h4 class="text-blue-600 font-semibold mb-1">Alamat</h4>
                    <p class="text-gray-600 leading-relaxed">
                        Jl. Soekarno Hatta No. 280 Ds Menganti,<br />
                        Kec. Kesugihan Cilacap, Jawa Tengah<br />
                    </p>
                </div>
                <div>
                    <h4 class="text-blue-600 font-semibold mb-1">Email</h4>
                    <p class="text-gray-600 leading-relaxed">cilacap@sucofindo.co.id</p>
                </div>
                <div>
                    <h4 class="text-blue-600 font-semibold mb-1">Telepon</h4>
                    <p class="text-gray-600 leading-relaxed">(+62-282) 540009</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <x-footer />

    <script>
        const toggleBtn = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        toggleBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>

</html>
