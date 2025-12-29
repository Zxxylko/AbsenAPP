<?php

namespace App\Imports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Attendance([
            'id_karyawan'   => $row['id_karyawan'] ?? null,
            'nama_karyawan' => $row['nama_karyawan'] ?? null,
            'departemen'    => $row['departemen'] ?? null,
            'tanggal'       => $row['tanggal'] ?? null,
            'jam_masuk'     => $row['jam_masuk'] ?? null,
            'jam_pulang'    => $row['jam_pulang'] ?? null,
            'status_masuk'  => $row['status_masuk'] ?? null,
            'status_pulang' => $row['status_pulang'] ?? null,
            'keterangan'    => $row['keterangan'] ?? null,
            'lembur'        => $row['lembur'] ?? null,
        ]);
    }

}
