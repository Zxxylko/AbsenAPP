<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';
    public $timestamps = false;

    protected $fillable = [
        'id_karyawan', 'nama_karyawan', 'departemen', 'tanggal',
        'jam_masuk', 'jam_pulang', 'status_masuk', 'status_pulang',
        'keterangan', 'lembur'
    ];
}
