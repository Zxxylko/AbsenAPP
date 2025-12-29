<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi {{ $month }}</title>
    <style>
        @page {
            margin: 40px 25px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #000;
        }

        h2 {
            text-align: center;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        p.info {
            text-align: center;
            margin-bottom: 20px;
            font-size: 11px;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: middle;
            word-wrap: break-word;
            font-size: 10px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) { background-color: #fafafa; }

        thead { display: table-header-group; }
        tfoot { display: table-row-group; }
        tr { page-break-inside: avoid; }
    </style>
</head>
<body>
    <h2>Laporan Absensi Bulan {{ $month }}</h2>
    <p class="info">Departemen: <strong>{{ $departemen ?? 'Semua' }}</strong></p>

    @php
        // ✅ Gunakan chunks dari controller (fallback ke data kalau gak ada)
        $chunks = isset($chunks) ? $chunks : (isset($data) ? $data->chunk(100) : collect());
    @endphp

    @if($chunks->flatten()->count() > 0)
        @foreach($chunks as $chunkIndex => $chunk)
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Karyawan</th>
                        <th>Nama Karyawan</th>
                        <th>Departemen</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status Masuk</th>
                        <th>Status Pulang</th>
                        <th>Keterangan</th>
                        <th>Lembur</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chunk as $index => $item)
                        <tr>
                            <td>{{ ($chunkIndex * 100) + $index + 1 }}</td>
                            <td>{{ $item->id_karyawan }}</td>
                            <td>{{ $item->nama_karyawan }}</td>
                            <td>{{ $item->departemen }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $item->jam_masuk ?: '-' }}</td>
                            <td>{{ $item->jam_pulang ?: '-' }}</td>
                            <td>{{ $item->status_masuk ?: '-' }}</td>
                            <td>{{ $item->status_pulang ?: '-' }}</td>
                            <td>{{ $item->keterangan ?: '-' }}</td>
                            <td>{{ $item->lembur ?: '0 jam' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- 🔸 Page break otomatis --}}
            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach
    @else
        <p style="text-align:center; margin-top: 30px; font-style: italic;">
            Tidak ada data absensi untuk periode ini.
        </p>
    @endif
</body>
</html>
