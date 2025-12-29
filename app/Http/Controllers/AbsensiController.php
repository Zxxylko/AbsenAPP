<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensis = Absensi::all();
        return view('absensi.index', compact('absensis'));
    }

    public function store(Request $request)
    {
        $tanggal = Carbon::today()->toDateString();

        $absen = Absensi::firstOrCreate(
            ['nama' => $request->nama, 'tanggal' => $tanggal],
            ['jam_masuk' => Carbon::now()->toTimeString()]
        );

        if ($absen->jam_keluar == null && $absen->jam_masuk != null && $absen->wasRecentlyCreated == false) {
            $absen->update(['jam_keluar' => Carbon::now()->toTimeString()]);
        }

        return redirect()->back()->with('success', 'Absensi berhasil disimpan!');
    }
}
