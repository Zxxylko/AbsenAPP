<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class THLData extends Model
{
    use HasFactory;

    protected $table = 'thl_data';

    protected $fillable = [
        'nama',
        'nik',
        'departemen',
        'kota',
        'tanggal_lahir',
        'ijazah',
        'alamat',
        'email',
    ];
    public $timestamps = false;

}
