<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\THLData;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class THLController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('file');
            $data = Excel::toCollection(null, $file)->first();

            if (!$data || $data->isEmpty()) {
                return back()->with('error', '❌ File kosong atau format tidak dikenali.');
            }

            // Pre-process attendance data for faster lookup
            $attendanceMap = [];
            $uniqueAttendance = $attendanceData->unique('nama_karyawan');
            foreach ($uniqueAttendance as $att) {
                $cleanName = strtolower(trim($att->nama_karyawan));
                $attendanceMap[$cleanName] = $att->departemen;
            }

            $inserted = 0;
            $skipped = [];

            DB::transaction(function () use ($data, $attendanceMap, &$inserted, &$skipped) {
                foreach ($data as $rowIndex => $row) {
                    if ($rowIndex === 0) continue;

                    $namaRaw = trim($row[1] ?? '');
                    if (!$namaRaw) continue;
                    
                    $nama = strtolower($namaRaw);
                    $nik = $row[2] ?? null;
                    $kota = $row[3] ?? '-';
                    $tanggal_lahir_raw = $row[4] ?? null;
                    $alamat = $row[6] ?? '-';
                    $email = $row[7] ?? '-';

                    // 🔁 konversi tanggal
                    $tanggal_lahir = null;
                    if ($tanggal_lahir_raw) {
                        $bulanMap = [
                            'januari' => '01', 'februari' => '02', 'maret' => '03', 'april' => '04',
                            'mei' => '05', 'juni' => '06', 'juli' => '07', 'agustus' => '08',
                            'september' => '09', 'oktober' => '10', 'november' => '11', 'desember' => '12'
                        ];
                        $str = strtolower(trim($tanggal_lahir_raw));
                        if (preg_match('/(\d{1,2})\s+([a-z]+)\s+(\d{4})/', $str, $m)) {
                            $tgl = str_pad($m[1], 2, '0', STR_PAD_LEFT);
                            $bulan = $bulanMap[$m[2]] ?? '01';
                            $tahun = $m[3];
                            $tanggal_lahir = "$tahun-$bulan-$tgl";
                        } elseif (strtotime($tanggal_lahir_raw)) {
                            $tanggal_lahir = date('Y-m-d', strtotime($tanggal_lahir_raw));
                        }
                    }

                    $departemen = null;
                    $bestScore = 0;

                    // 1. Check Exact Match First (O(1))
                    if (isset($attendanceMap[$nama])) {
                        $departemen = $attendanceMap[$nama];
                        $bestScore = 100;
                    } else {
                        // 2. Fuzzy Match Only if No Exact Match (O(N))
                        foreach ($attendanceMap as $namaDb => $dep) {
                            similar_text($nama, $namaDb, $score);
                            if ($score > $bestScore) {
                                $bestScore = $score;
                                $departemen = $dep;
                                if ($score > 95) break; // Good enough
                            }
                        }
                    }

                    if ($bestScore >= 80 && $departemen) {
                        THLData::updateOrCreate(
                            ['nik' => $nik],
                            [
                                'nama' => ucwords($nama),
                                'departemen' => $departemen,
                                'kota' => $kota,
                                'tanggal_lahir' => $tanggal_lahir,
                                'alamat' => $alamat,
                                'email' => $email,
                            ]
                        );
                        $inserted++;
                    } else {
                        $skipped[] = ['nama_excel' => $namaRaw, 'skor' => round($bestScore, 1)];
                    }
                }
            });

            $msg = "✅ Berhasil import {$inserted} data THL.";
            if (!empty($skipped)) {
                $msg .= "<br>⚠️ Data dilewati (" . count($skipped) . "):<br>";
                foreach ($skipped as $s) {
                    $msg .= "• {$s['nama_excel']} ({$s['skor']}%)<br>";
                }
            }

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            Log::error('THL Import Error: ' . $e->getMessage());
            return back()->with('error', '❌ Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $dataThl = THLData::orderBy('nama', 'asc')->get();
        return view('thl.index', compact('dataThl'));
    }
}
