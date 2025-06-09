<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Kalender Jadwal Patroli - {{ $currentMonth->translatedFormat('F Y') }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 14px;
            margin: 40px;
            color: #000;
            background-color: #fff;
        }

        header {
            text-align: center;
            margin-bottom: 4px;
        }

        header h1 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .subjudul {
            text-align: center;
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        table.calendar {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.calendar thead tr {
            background-color: #eee;
        }

        table.calendar th,
        table.calendar td {
            border: 1px solid #333;
            width: 14.28%;
            height: 60px;
            vertical-align: top;
            padding: 4px 5px;
            word-wrap: break-word;
            font-size: 14px;
            line-height: 1.1;
        }

        table.calendar th {
            color: #000;
            font-weight: 700;
            text-align: center;
            font-size: 14px;
            letter-spacing: 1px;
            user-select: none;
        }

        .day-number {
            font-weight: bold;
            color: #054e78;
            text-align: center;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .petugas {
            text-align: center;
            font-size: 15px;
            line-height: 1.1;
            color: #000;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body>

    <header>
        <h1>PT SUCOFINDO CABANG CILACAP</h1>
        <div class="subjudul">Jadwal Patroli Bulan {{ $currentMonth->translatedFormat('F Y') }}</div>
    </header>

    @php
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();
        $startDayOfWeek = $startOfMonth->dayOfWeek;
        $totalDays = $currentMonth->daysInMonth;
        $currentDay = 1;

        $warnaPetugas = [
            1 => '#f97316', // orange terang
            2 => '#ef4444', // merah
            3 => '#a855f7', // ungu
            4 => '#fbbf24', // kuning
            5 => '#3b82f6', // biru
            6 => '#22c55e', // hijau
            7 => '#db2777', // pink magenta 
        ];
    @endphp

    <table class="calendar" role="table" aria-label="Kalender Jadwal Patroli">
        <thead>
            <tr>
                <th scope="col">Minggu</th>
                <th scope="col">Senin</th>
                <th scope="col">Selasa</th>
                <th scope="col">Rabu</th>
                <th scope="col">Kamis</th>
                <th scope="col">Jumat</th>
                <th scope="col">Sabtu</th>
            </tr>
        </thead>
        <tbody>
            @while ($currentDay <= $totalDays)
                <tr>
                    @for ($i = 0; $i < 7; $i++)
                        @php
                            $cellDate = null;
                            if (($currentDay == 1 && $i >= $startDayOfWeek) || $currentDay > 1) {
                                if ($currentDay <= $totalDays) {
                                    $cellDate = \Carbon\Carbon::createFromDate(
                                        $currentMonth->year,
                                        $currentMonth->month,
                                        $currentDay,
                                    );
                                    $currentDay++;
                                }
                            }
                        @endphp
                        <td>
                            @if ($cellDate)
                                <div class="day-number">{{ $cellDate->day }}</div>
                                @php
                                    $jadwalHariIni = $jadwalPatroli[$cellDate->toDateString()] ?? collect();
                                @endphp
                                @foreach ($jadwalHariIni as $jadwal)
                                    <div class="petugas"
                                        style="background-color: {{ $warnaPetugas[$jadwal->user_id] ?? '#e5e7eb' }};
                                               color: #000;
                                               padding: 4px;
                                               border-radius: 4px;
                                               margin-bottom: 2px;">
                                        {{ $jadwal->user->nama }}
                                    </div>
                                @endforeach
                            @endif
                        </td>
                    @endfor
                </tr>
            @endwhile
        </tbody>
    </table>

</body>

</html>
