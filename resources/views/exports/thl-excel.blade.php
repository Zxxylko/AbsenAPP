<table border="1">
    <thead>
        <tr style="background-color:#e0e7ff; color:#111827;">
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
