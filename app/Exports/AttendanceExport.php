<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Attendance::orderBy('tanggal', 'desc')->get();
    }

    public function map($attendance): array
    {
        return [
            $attendance->id_karyawan,
            $attendance->nama_karyawan,
            $attendance->departemen,
            $attendance->tanggal,
            $attendance->jam_masuk,
            $attendance->jam_pulang,
            $attendance->status_masuk,
            $attendance->status_pulang,
            $attendance->keterangan,
            $attendance->lembur,
            $attendance->shift ?? '-', // khusus Security
        ];
    }

    public function headings(): array
    {
        return [
            'ID Karyawan',
            'Nama',
            'Departemen',
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Status Masuk',
            'Status Pulang',
            'Keterangan',
            'Lembur',
            'Shift'
        ];
    }
}
