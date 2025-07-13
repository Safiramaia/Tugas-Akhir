<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Patroli - {{ $startDate }} s/d {{ $endDate }}</title>
    <style>
        /* Global Styles */
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            color: #000;
            margin: 40px;
        }

        h2,
        .text-center {
            text-align: center;
        }

        hr {
            border: 1px solid #000;
            margin: 10px 0;
        }

        /* Header (Kop Surat) */
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

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        thead {
            background-color: #f2f2f2;
        }

        th,
        td {
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

        /* Signature Section */
        .ttd-section {
            margin-top: 40px;
            width: 100%;
        }

        .ttd-kepala {
            width: 200px;
            float: right;
            text-align: left;
        }

        .ttd-kepala p {
            margin: 4px 0;
            line-height: 1.5;
        }

        .ttd-space {
            height: 60px;
        }

        .ttd-nama {
            display: inline-block;
            font-weight: bold;
            padding-bottom: 1px;
            min-width: 90px;
        }

        .qr-code {
            margin: 6px 0;
        }

        .qr-code img {
            width: 100px;
            height: auto;
        }
    </style>
</head>

<body>
    <!-- KOP SURAT -->
    <div class="kop-surat">
        <div class="kop-logo">
            <img src="{{ isset($preview) && $preview ? asset('assets/sucofindo.png') : public_path('assets/sucofindo.png') }}" alt="Logo">
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
        Periode : {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s/d
        {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
    </p>

    <!-- TABEL DATA -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Petugas</th>
                <th>Lokasi</th>
                <th>Unit Kerja</th>
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
                    <td class="text-center">{{ $item->unit->nama_unit ?? '-' }}</td>
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

    <!-- TANDA TANGAN DAN QR CODE -->
    <div class="ttd-section">
        <div class="ttd-kepala">
            <p>Cilacap, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Disahkan secara elektronik oleh :</p>
            <p>Kepala Bidang Dukungan Bisnis</p>

            <div class="qr-code">
                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
            </div>

            <p class="ttd-nama">{{ auth()->user()->nama ?? 'Nama Kepala' }}</p>
        </div>
    </div>
</body>

</html>
