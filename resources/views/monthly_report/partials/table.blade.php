<table class="table-auto w-full border border-collapse">
    <thead>
        <tr class="bg-gray-200">
            <th class="border px-2 py-1">ID Karyawan</th>
            <th class="border px-2 py-1">Nama</th>
            <th class="border px-2 py-1">Departemen</th>
            <th class="border px-2 py-1">Tanggal</th>
            <th class="border px-2 py-1">Jam Masuk</th>
            <th class="border px-2 py-1">Jam Pulang</th>
            <th class="border px-2 py-1">Status Masuk</th>
            <th class="border px-2 py-1">Status Pulang</th>
            <th class="border px-2 py-1">Keterangan</th>
            <th class="border px-2 py-1">Lembur</th>
        </tr>
    </thead>
    <tbody>
        @forelse($reports as $r)
            <tr class="hover:bg-gray-100">
                <td class="border px-2 py-1">{{ $r->id_karyawan }}</td>
                <td class="border px-2 py-1">{{ $r->nama_karyawan }}</td>
                <td class="border px-2 py-1">{{ $r->departemen }}</td>
                <td class="border px-2 py-1">{{ $r->tanggal }}</td>
                <td class="border px-2 py-1">{{ $r->jam_masuk ?? '-' }}</td>
                <td class="border px-2 py-1">{{ $r->jam_pulang ?? '-' }}</td>
                <td class="border px-2 py-1">{{ $r->status_masuk }}</td>
                <td class="border px-2 py-1">{{ $r->status_pulang }}</td>
                <td class="border px-2 py-1">{{ $r->keterangan }}</td>
                <td class="border px-2 py-1">{{ $r->lembur }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center py-2">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>
