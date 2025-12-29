<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Absensi THL</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #111; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: center; }
        th { background: #e0e7ff; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Rekap Absensi THL - {{ date('F Y', strtotime($month)) }}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Departemen</th>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alpha</th>
                <th>Lembur</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $index => $r)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $r->nama_karyawan }}</td>
                    <td>{{ $r->departemen }}</td>
                    <td>{{ $r->hadir }}</td>
                    <td>{{ $r->sakit }}</td>
                    <td>{{ $r->izin }}</td>
                    <td>{{ $r->alpha }}</td>
                    <td>{{ $r->lembur }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
