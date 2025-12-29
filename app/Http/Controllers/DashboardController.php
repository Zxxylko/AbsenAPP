<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $today = Carbon::today();

            // Bulan kemarin (safe calculation)
            $bulanKemarinObj = $today->copy()->subMonth();
            $bulanKemarin = $bulanKemarinObj->translatedFormat('F');
            $tahunKemarin = $bulanKemarinObj->format('Y');

            /*** Rekap harian: 2 tanggal terakhir ***/
            // FIXED: Use subDays() to safely calculate dates
            $dayLast1 = $today->copy()->subDays(2)->toDateString();
            $dayLast2 = $today->copy()->subDays(1)->toDateString();

            $absensi = DB::table('attendances')
                ->whereIn('tanggal', [$dayLast1, $dayLast2])
                ->get();

            $rekapHarian = $absensi
                ->groupBy('tanggal')
                ->map(function ($items, $tanggal) {
                    return (object) [
                        'tanggal' => $tanggal,
                        'hadir' => $items->where('keterangan', 'Hadir')->count(),
                        'izin_sakit' => $items->whereIn('keterangan', ['Izin', 'Sakit'])->count(),
                        'alpha' => $items->where('keterangan', 'Alpha')->count(),
                        'lembur' => $items->where('lembur', '!=', null)->count()
                    ];
                })
                ->sortKeys();

            /*** Statistik ringkas bulan kemarin ***/
            $absensiBulan = DB::table('attendances')
                ->whereYear('tanggal', $bulanKemarinObj->year)
                ->whereMonth('tanggal', $bulanKemarinObj->month)
                ->get();

            $totalHadir = $absensiBulan->where('keterangan', 'Hadir')->count();
            $totalTidakHadir = $absensiBulan->whereIn('keterangan', ['Izin', 'Sakit', 'Alpha'])->count();

            $tepatWaktu = $absensiBulan->where('status_masuk', 'Tepat Waktu')->count();
            $terlambat = $absensiBulan->where('status_masuk', 'Terlambat')->count();

            $persenTepatWaktu = $totalHadir > 0 ? round(($tepatWaktu / $totalHadir) * 100, 1) : 0;
            $persenTerlambat = $totalHadir > 0 ? round(($terlambat / $totalHadir) * 100, 1) : 0;

            /*** Top 5 pegawai (data_absensi_thl) ***/
            $thl = DB::table('data_absensi_thl')->get();

            $topPegawai = $thl
                ->sortByDesc('hadir')
                ->take(5)
                ->values();

            return view('dashboard', compact(
                'totalHadir',
                'totalTidakHadir',
                'persenTepatWaktu',
                'persenTerlambat',
                'topPegawai',
                'rekapHarian',
                'bulanKemarin',
                'tahunKemarin'
            ));
        } catch (\Exception $e) {
            // Graceful fallback with default values
            return view('dashboard', [
                'totalHadir' => 0,
                'totalTidakHadir' => 0,
                'persenTepatWaktu' => 0,
                'persenTerlambat' => 0,
                'topPegawai' => collect([]),
                'rekapHarian' => collect([]),
                'bulanKemarin' => Carbon::now()->subMonth()->translatedFormat('F'),
                'tahunKemarin' => Carbon::now()->subMonth()->format('Y'),
            ])->with('error', 'Terjadi kesalahan saat memuat data dashboard.');
        }
    }
}
