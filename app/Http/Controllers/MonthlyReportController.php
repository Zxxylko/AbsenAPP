<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;


class MonthlyReportController extends Controller
{
    public function index()
    {
        // Get unique months from database
        $driver = DB::getDriverName();
        $selectMonth = $driver === 'pgsql' 
            ? "to_char(tanggal, 'YYYY-MM') as month_val, to_char(tanggal, 'Month YYYY') as month_name"
            : "strftime('%Y-%m', tanggal) as month_val, strftime('%m %Y', tanggal) as month_name";

        $months = Attendance::selectRaw($selectMonth)
            ->where('departemen', '<>', 'Perusahaan')
            ->groupBy('month_val', 'month_name')
            ->orderBy('month_val', 'desc')
            ->get();

        $departemenList = Attendance::where('departemen', '<>', 'Perusahaan')
            ->distinct()
            ->pluck('departemen')
            ->sort()
            ->values();

        return view('monthly_report.index', compact('months', 'departemenList'));
    }

    // ✅ Fetch data buat refresh tabel setelah import (tanpa reload)
    public function fetchData(Request $request)
    {
        $departemen = $request->query('departemen');
        $month = $request->query('month');

        $query = Attendance::query()->where('departemen', '<>', 'Perusahaan');

        if ($departemen && $departemen !== 'All') {
            $query->where('departemen', $departemen);
        }

        if ($month) {
            $query->whereYear('tanggal', substr($month, 0, 4))
                  ->whereMonth('tanggal', substr($month, 5, 2));
        }

        $reports = $query->orderBy('tanggal', 'desc')
                         ->orderBy('id_karyawan', 'asc')
                         ->get()
                         ->map(function($r) {
                             $hari = date('N', strtotime($r->tanggal));
                             $r->formatted_date = Carbon::parse($r->tanggal)->translatedFormat('d F Y');
                             $r->jam_masuk_fmt = $r->jam_masuk ? date('H:i', strtotime($r->jam_masuk)) : '-';
                             $r->jam_pulang_fmt = $r->jam_pulang ? date('H:i', strtotime($r->jam_pulang)) : '-';
                             
                             // Status Masuk
                             if (empty($r->jam_masuk)) {
                                 $r->status_masuk_label = 'Tidak Absen';
                                 $r->status_masuk_color = 'bg-ut-gray-200 text-ut-gray-700';
                             } else {
                                 $isWeekend = $hari >= 6;
                                 if ($isWeekend) {
                                     $r->status_masuk_label = strtotime($r->jam_masuk) < strtotime('08:30') ? 'Tepat Waktu' : 'Lembur';
                                     $r->status_masuk_color = $r->status_masuk_label === 'Tepat Waktu' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800';
                                 } else {
                                     $r->status_masuk_label = strtotime($r->jam_masuk) > strtotime('08:30') ? 'Terlambat' : 'Tepat Waktu';
                                     $r->status_masuk_color = $r->status_masuk_label === 'Terlambat' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
                                 }
                             }

                             // Status Pulang
                             if (empty($r->jam_pulang)) {
                                 $r->status_pulang_label = 'Tidak Absen';
                                 $r->status_pulang_color = 'bg-ut-gray-200 text-ut-gray-700';
                             } else {
                                 $limitNormal = strtotime('16:30') + ($hari == 5 ? 1800 : 0);
                                 if ($hari >= 6) {
                                     $r->status_pulang_label = 'Lembur';
                                     $r->status_pulang_color = 'bg-blue-100 text-blue-800';
                                 } elseif (strtotime($r->jam_pulang) < $limitNormal) {
                                     $r->status_pulang_label = 'Pulang Cepat';
                                     $r->status_pulang_color = 'bg-red-100 text-red-800';
                                 } elseif (strtotime($r->jam_pulang) > strtotime('18:30')) {
                                     $r->status_pulang_label = 'Lembur';
                                     $r->status_pulang_color = 'bg-blue-100 text-blue-800';
                                 } else {
                                     $r->status_pulang_label = 'Normal';
                                     $r->status_pulang_color = 'bg-green-100 text-green-800';
                                 }
                             }

                             return $r;
                         });

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    

        // ✅ Import Excel
        public function import(Request $request)
        {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ]);

            $file = $request->file('file')->getPathname();
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getSheetByName('Lap. Log Absen');

            if (!$sheet) {
                return response()->json([
                    'success' => false,
                    'errors' => ['Sheet "Lap. Log Absen" tidak ditemukan!']
                ]);
            }

            // Ambil periode dari C3
            $periodeRaw = trim((string)$sheet->getCell('C3')->getValue());
            preg_match('/\d{4}-\d{2}-\d{2}/', $periodeRaw, $matches);
            $firstDate = $matches[0] ?? null;

            if (!$firstDate) {
                return response()->json([
                    'success' => false,
                    'errors' => ['Format periode di C3 tidak valid (harus YYYY-MM-DD ~ YYYY-MM-DD)']
                ]);
            }

            $year = substr($firstDate, 0, 4);
            $month = substr($firstDate, 5, 2);

            // Ambil daftar tanggal di baris 4
            $tanggalHeader = [];
            for ($col = 'A'; $col !== 'AF'; $col++) {
                $val = trim((string)$sheet->getCell($col . '4')->getValue());
                if ($val !== '') $tanggalHeader[$col] = intval($val);
            }

            $newReports = [];
            $duplicate = 0;
            $errorCount = 0;

            $highestRow = $sheet->getHighestRow();

            for ($row = 5; $row <= $highestRow; $row += 2) {
                $id_karyawan = trim((string)$sheet->getCell("C{$row}")->getValue());
                $nama = trim((string)$sheet->getCell("K{$row}")->getValue());
                $departemen = trim((string)$sheet->getCell("U{$row}")->getValue());
                $rowJam = $row + 1;

                    // ❌ Skip kalau departemennya "Perusahaan"
                    if (strtolower($departemen) === 'perusahaan') {
                        continue;
                    }
                    
                foreach ($tanggalHeader as $col => $tgl) {
                    $jam_raw = trim((string)$sheet->getCell($col . $rowJam)->getValue());
                    $clean = preg_replace('/[^0-9]/', '', $jam_raw);

                    preg_match_all('/\d{3,4}/', $clean, $matches);

                    $jams = [];
                    if (!empty($matches[0])) {
                        foreach ($matches[0] as $jm) {
                            $jams[] = str_pad($jm, 4, '0', STR_PAD_LEFT);
                        }
                        sort($jams, SORT_STRING);
                    }

                    $jam_pagi = [];
                    $jam_sore = [];
                    foreach ($jams as $jam) {
                        $hour = intval(substr($jam, 0, 2));
                        if ($hour < 12) $jam_pagi[] = $jam;
                        else $jam_sore[] = $jam;
                    }

                    $jam_masuk = !empty($jam_pagi) ? substr($jam_pagi[0], 0, 2) . ':' . substr($jam_pagi[0], 2, 2) : null;
                    $jam_pulang = !empty($jam_sore) ? substr(end($jam_sore), 0, 2) . ':' . substr(end($jam_sore), 2, 2) : null;

                    // fallback jika masih kosong
                    if (!$jam_masuk && !$jam_pulang && !empty($jams)) {
                        if (isset($jams[0])) $jam_masuk = substr($jams[0], 0, 2) . ':' . substr($jams[0], 2, 2);
                        if (isset($jams[1])) $jam_pulang = substr($jams[1], 0, 2) . ':' . substr($jams[1], 2, 2);
                    }

                    $tanggalFix = sprintf('%s-%s-%02d', $year, $month, $tgl);
                    $tanggal = $this->fixInvalidDate($tanggalFix);
                    if (!$tanggal) continue;

                    $hari = date('N', strtotime($tanggal));
                    $jam_normal_pulang = ($hari == 5) ? "17:00" : "16:30";

                    $status_masuk = (!empty($jam_masuk) && strtotime($jam_masuk) > strtotime("08:30")) ? "Terlambat" : "Tepat Waktu";

                    if (empty($jam_pulang)) {
                        $status_pulang = "Tidak Absen";
                        $lembur = "0 jam";
                    } else {
                        $selisih = strtotime($jam_pulang) - strtotime($jam_normal_pulang);
                        $lembur = $selisih > 0 ? floor($selisih / 3600) . " jam " . floor(($selisih % 3600) / 60) . " menit" : "0 jam";
                        $status_pulang = $selisih > 0 ? "Lembur" : "Normal";

                        if ($hari >= 6) {
                            $selisihWeekend = strtotime($jam_pulang) - strtotime("08:00");
                            $jamOnly = floor($selisihWeekend / 3600);
                            $menitOnly = floor(($selisihWeekend % 3600) / 60);
                            $lembur = $selisihWeekend > 0 ? "$jamOnly jam $menitOnly menit" : "0 jam";
                            $status_pulang = $selisihWeekend > 0 ? "Lembur" : "Normal";
                        }
                    }

                    

                    // ✅ Keterangan otomatis
                    if (empty($jam_masuk) && empty($jam_pulang)) {
                        $keterangan = "Alpha";
                    } elseif (empty($jam_masuk) || empty($jam_pulang)) {
                        $keterangan = "Belum ada keterangan";
                    } else {
                        $keterangan = "Hadir";
                    }

                    // Update atau create
                    try {
                        $attendance = Attendance::updateOrCreate(
                            ['id_karyawan' => $id_karyawan, 'tanggal' => $tanggal],
                            [
                                'nama_karyawan' => $nama,
                                'departemen' => $departemen,
                                'jam_masuk' => $jam_masuk,
                                'jam_pulang' => $jam_pulang,
                                'status_masuk' => $status_masuk,
                                'status_pulang' => $status_pulang,
                                'keterangan' => $keterangan,
                                'lembur' => $lembur,
                                'shift' => 'Reguler',
                            ]
                        );

                        if ($attendance->wasRecentlyCreated) $newReports[] = $attendance;
                        else $duplicate++;
                    } catch (\Exception $e) {
                        $errorCount++;
                        Log::error("DB error: " . $e->getMessage());
                        continue;
                    }
                }
            }

            // Rekap total ke DataAbsensiThl
            $rekapData = Attendance::select(
                'nama_karyawan',
                'departemen',
                DB::raw("SUM(CASE WHEN keterangan = 'Hadir' THEN 1 ELSE 0 END) as hadir"),
                DB::raw("SUM(CASE WHEN keterangan = 'Sakit' THEN 1 ELSE 0 END) as sakit"),
                DB::raw("SUM(CASE WHEN keterangan = 'Izin' THEN 1 ELSE 0 END) as izin"),
                DB::raw("SUM(CASE WHEN keterangan = 'Alpha' THEN 1 ELSE 0 END) as alpha"),
                DB::raw("SUM(CASE WHEN status_pulang LIKE '%Lembur%' THEN 1 ELSE 0 END) as lembur")
            )
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->groupBy('nama_karyawan', 'departemen')
            ->get();

            foreach ($rekapData as $r) {
                \App\Models\DataAbsensiThl::updateOrCreate(
                    ['nama' => $r->nama_karyawan, 'departemen' => $r->departemen],
                    [
                        'hadir' => $r->hadir,
                        'sakit' => $r->sakit,
                        'izin' => $r->izin,
                        'alpha' => $r->alpha,
                        'lembur' => $r->lembur,
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => "✅ Import selesai: " . count($newReports) . " baru, {$duplicate} duplikat, {$errorCount} error.",
            ]);
        }



       private function fixInvalidDate($date)   
        {
            if (empty($date)) return null;

            try {
                $parsed = \Carbon\Carbon::parse($date);
            } catch (\Exception $e) {
                // kalau invalid, ambil tanggal terakhir di bulan itu
                $parsed = \Carbon\Carbon::createFromFormat('Y-m-d', substr($date, 0, 7) . '-01')->endOfMonth();
            }

            return $parsed->format('Y-m-d');
        }



    // ✅ Export PDF / Excel
    public function export(Request $request)
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $month = $request->query('month');
        $departemen = $request->query('departemen');
        $format = $request->query('format', 'xlsx');

        $query = Attendance::query();

        if ($departemen && $departemen !== 'All') {
            $query->where('departemen', $departemen);
        }

        if ($month) {
            $query->whereYear('tanggal', substr($month, 0, 4))
                ->whereMonth('tanggal', substr($month, 5, 2));
        }

        $data = $query->orderBy('tanggal', 'asc')->get();

        // ✅ Filter hanya tanggal valid
        $data = $data->filter(fn($d) => !empty($d->tanggal) && strtotime($d->tanggal));

        if ($data->isEmpty()) {
            return back()->with('error', 'Tidak ada data absensi untuk bulan tersebut.');
        }

        $bulan = $month ? Carbon::parse($month . '-01')->translatedFormat('F Y') : 'Semua';
        $namaFile = "Laporan_Absensi_{$departemen}_{$bulan}.{$format}";

        switch ($format) {
            case 'csv':
                return Excel::download(new AttendanceExport($data), $namaFile, \Maatwebsite\Excel\Excel::CSV);

            case 'pdf':
                // ✅ Pecah data jadi chunk per 100 baris biar gak berat pas render PDF
                $chunks = collect([$data]); // fallback default
                if ($data->count() > 100) {
                    $chunks = $data->chunk(100);
                }

                Log::info("Export success: {$departemen} - {$bulan}, format: {$format}, data: " . $data->count());

                $pdf = Pdf::loadView('monthly_report.pdf', [
                    'chunks' => $chunks,
                    'month' => $bulan,
                    'departemen' => $departemen ?? 'Semua'
                ])->setPaper('A4', 'landscape');

                return $pdf->download($namaFile);

            default:
                return Excel::download(new AttendanceExport($data), $namaFile, \Maatwebsite\Excel\Excel::XLSX);
        }
    }

// ✅ Update keterangan
    public function updateKeterangan(Request $request, $id)
    {
        $request->validate(['keterangan' => 'nullable|string|max:255']);
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $attendance->keterangan = $request->keterangan ?: null;
        $attendance->save();

        return response()->json(['success' => true]);

    }
}
