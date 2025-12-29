<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAbsensiThl extends Model
{
    use HasFactory;

    protected $table = 'data_absensi_thl'; // pastiin nama tabelnya sesuai migrasi

    protected $fillable = [
        'nama',
        'departemen',
        'hadir',
        'sakit',
        'izin',
        'alpha',
        'lembur',
    ];
}
