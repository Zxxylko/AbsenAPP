<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\THLExport;

class THLReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->query('month', now()->format('Y-m'));
        $format = $request->query('format');
        $filename = $request->query('filename') ?: "Rekap_THL_{$month}";
        $token = $request->query('download_token');

        // Ambil data absensi rekap per karyawan
        $reports = Attendance::selectRaw("
                nama_karyawan,
                departemen,
                SUM(CASE WHEN keterangan = 'Hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN keterangan = 'Sakit' THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN keterangan = 'Izin' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN keterangan = 'Alpha' THEN 1 ELSE 0 END) as alpha,
                SUM(CASE WHEN lembur > 0 THEN 1 ELSE 0 END) as lembur
            ")
            ->whereYear('tanggal', substr($month, 0, 4))
            ->whereMonth('tanggal', substr($month, 5, 2))
            ->groupBy('nama_karyawan', 'departemen')
            ->orderBy('departemen')
            ->get();

        // Tambahkan detail tanggal untuk tooltip
        foreach ($reports as $r) {
            $baseQuery = Attendance::where('nama_karyawan', $r->nama_karyawan)
                                   ->where('departemen', $r->departemen)
                                   ->whereYear('tanggal', substr($month, 0, 4))
                                   ->whereMonth('tanggal', substr($month, 5, 2));

            $r->tanggal_hadir  = (clone $baseQuery)->where('keterangan', 'Hadir')
                ->pluck('tanggal')->map(fn($t) => date('d M', strtotime($t)))->toArray();

            $r->tanggal_sakit  = (clone $baseQuery)->where('keterangan', 'Sakit')
                ->pluck('tanggal')->map(fn($t) => date('d M', strtotime($t)))->toArray();

            $r->tanggal_izin   = (clone $baseQuery)->where('keterangan', 'Izin')
                ->pluck('tanggal')->map(fn($t) => date('d M', strtotime($t)))->toArray();

            $r->tanggal_alpha  = (clone $baseQuery)->where('keterangan', 'Alpha')
                ->pluck('tanggal')->map(fn($t) => date('d M', strtotime($t)))->toArray();

            $r->tanggal_lembur = (clone $baseQuery)->where('lembur', '>', 0)
                ->pluck('tanggal')->map(fn($t) => date('d M', strtotime($t)))->toArray();
        }

        /*
        |--------------------------------------------------------------------------
        | EXPORT HANDLER
        |--------------------------------------------------------------------------
        */
        if ($format) {

            // Simpan token agar JS tahu export selesai
            if ($token) {
                $key = "download_{$token}_finished";
                session()->put($key, true);
            }

            $fileName = "{$filename}.{$format}";

            if (in_array($format, ['xlsx','csv'])) {
                return Excel::download(new THLExport($reports, $month), $fileName);
            }

            if ($format === 'pdf') {
                $pdf = Pdf::loadView('exports.thl-pdf', [
                    'reports' => $reports,
                    'month'   => $month
                ])->setPaper('a4', 'landscape');

                return $pdf->download($fileName);
            }
        }

        // View utama
        return view('Athl.report', compact('reports', 'month'));
    }
}
