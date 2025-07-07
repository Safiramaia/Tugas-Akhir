<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Laporan Patroli - {{ $startDate }} s/d {{ $endDate }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            color: #000;
            margin: 40px;
        }

        h2, .text-center {
            text-align: center;
        }

        hr {
            border: 1px solid #000;
            margin: 10px 0;
        }

        .kop-surat {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .kop-logo {
            display: table-cell;
            width: 100px;
            vertical-align: middle;
        }

        .kop-logo img {
            width: 100%;
            height: auto;
        }

        .kop-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 6px;
            text-align: center;
        }

        .kop-info h1 {
            font-size: 16px;
            margin: 0;
            line-height: 1.4;
        }

        .kop-info p {
            font-size: 14px;
            margin: 0;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        thead {
            background-color: #f2f2f2;
        }

        th, td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .no-wrap {
            white-space: nowrap;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <div class="kop-logo">
            <img src="{{ asset('assets/sucofindo.png') }}" alt="Logo">
        </div>
        <div class="kop-info">
            <h1>PT SUCOFINDO CABANG CILACAP</h1>
            <p>Jl. Soekarno Hatta No. 280, Ds Menganti - Kec Kesugihan Cilacap, Jawa Tengah</p>
            <p>Telp : (+62-282) 540009 | Email : cilacap@sucofindo.co.id</p>
        </div>
    </div>
    <hr>

    <!-- JUDUL DAN PERIODE -->
    <h2>Laporan Patroli</h2>
    <p class="text-center">
        Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}
        s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
    </p>

    <!-- TABEL DATA -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Petugas</th>
                <th>Lokasi</th>
                <th class="no-wrap">Tanggal</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->user->nama ?? '-' }}</td>
                    <td class="text-left">{{ $item->lokasiPatroli->nama_lokasi ?? '-' }}</td>
                    <td class="no-wrap">{{ \Carbon\Carbon::parse($item->tanggal_patroli)->format('d-m-Y') }}</td>
                    <td>{{ $item->waktu_patroli }}</td>
                    <td>{{ ucfirst($item->status) }}</td>
                    <td class="text-left">{{ $item->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
