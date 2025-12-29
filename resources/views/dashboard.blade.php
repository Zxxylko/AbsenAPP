@extends('layouts.main')

@section('content')
    @php
        // Dynamic Greeting with WIB timezone
        $hour = \Carbon\Carbon::now('Asia/Jakarta')->format('H');
        $greeting = 'Selamat Malam';
        if ($hour >= 5 && $hour < 11) $greeting = 'Selamat Pagi';
        else if ($hour >= 11 && $hour < 15) $greeting = 'Selamat Siang';
        else if ($hour >= 15 && $hour < 19) $greeting = 'Selamat Sore';

        // Defensive defaults
        $totalHadir ??= 0;
        $totalTidakHadir ??= 0;
        $persenTepatWaktu ??= 0;
        $persenTerlambat ??= 0;
        $topPegawai ??= collect([]);
        $rekapHarian ??= collect([]);
        $bulanKemarin ??= \Carbon\Carbon::now()->subMonth()->translatedFormat('F');
        $tahunKemarin ??= \Carbon\Carbon::now()->subMonth()->format('Y');

        $totalAbsen = max($totalHadir + $totalTidakHadir, 1);
        $sumPercent = max($persenTepatWaktu + $persenTerlambat, 1);
        $persenTepatWaktu = round(($persenTepatWaktu / $sumPercent) * 100, 1);
        $persenTerlambat = round(100 - $persenTepatWaktu, 1);
    @endphp

    <div class="space-y-10 animate-fade-in px-4 md:px-0">

        {{-- Welcome Header --}}
        <div class="relative overflow-hidden premium-glass rounded-[2.5rem] p-10 md:p-14 border border-white/20 dark:border-white/10 shadow-2xl group">
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-ut-yellow/20 rounded-full blur-[100px] group-hover:bg-ut-yellow/30 transition-all duration-1000"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-ut-blue/10 rounded-full blur-[80px] group-hover:bg-ut-blue/20 transition-all duration-1000"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-10">
                <div class="space-y-4">
                    <h2 class="text-4xl md:text-6xl font-black text-ut-gray-900 dark:text-white tracking-tighter leading-tight">
                        {{ $greeting }},
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-ut-blue to-ut-blue-light dark:from-ut-yellow dark:to-white">
                            {{ ucfirst(Auth::user()->username ?? 'Pengguna') }}
                        </span>
                    </h2>
                    <p class="text-ut-gray-500 dark:text-ut-gray-400 text-lg md:text-xl font-medium max-w-lg">
                        Sistem sedang memantau kehadiran tim Anda secara otomatis hari ini.
                    </p>
                </div>

                <div class="flex items-center gap-6 bg-white/50 dark:bg-ut-gray-800/50 backdrop-blur-3xl px-8 py-5 rounded-[2rem] border border-white/30 dark:border-white/10 shadow-2xl self-start group/clock transform hover:scale-105 transition-all">
                    <div class="p-4 bg-ut-blue dark:bg-ut-yellow rounded-2xl text-white dark:text-ut-blue shadow-lg group-hover/clock:rotate-12 transition-transform animate-pulse">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex flex-col">
                        <span id="clock" class="text-3xl font-black text-ut-blue dark:text-ut-yellow tracking-tighter tabular-nums"></span>
                        <span class="text-[10px] uppercase tracking-[0.3em] font-black text-ut-gray-400">JAM AKTIF UNIVERSITAS</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Card Template --}}
            @php
                $stats = [
                    ['label' => 'Total Kehadiran', 'value' => $totalHadir, 'color' => 'bg-emerald-500', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/10', 'icon_color' => 'text-emerald-600 dark:text-emerald-400', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Tidak Hadir', 'value' => $totalTidakHadir, 'color' => 'bg-rose-500', 'bg' => 'bg-rose-50 dark:bg-rose-900/10', 'icon_color' => 'text-rose-600 dark:text-rose-400', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z']
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="group relative bg-white dark:bg-ut-gray-800 rounded-3xl p-8 shadow-sm border border-ut-gray-100 dark:border-ut-gray-700 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                    <div class="absolute top-0 left-0 w-full h-1.5 {{ $stat['color'] }} rounded-t-3xl"></div>
                    <div class="flex justify-between items-start">
                        <div class="space-y-1">
                            <h3 class="text-sm font-bold text-ut-gray-400 uppercase tracking-widest">{{ $stat['label'] }}</h3>
                            <p class="text-5xl font-black text-ut-gray-900 dark:text-white">{{ $stat['value'] }}</p>
                        </div>
                        <div class="p-4 {{ $stat['bg'] }} {{ $stat['icon_color'] }} rounded-2xl group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/></svg>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Discipline Analysis (Double Width) --}}
            <div class="lg:col-span-2 bg-white dark:bg-ut-gray-800 rounded-3xl p-8 shadow-sm border border-ut-gray-100 dark:border-ut-gray-700 hover:shadow-2xl transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-ut-blue to-ut-yellow rounded-t-3xl"></div>
                <div class="flex flex-col h-full justify-between gap-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-sm font-bold text-ut-gray-400 uppercase tracking-widest">Analisa Kedisiplinan</h3>
                        <div class="flex gap-4">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-xs font-bold text-ut-gray-600 dark:text-ut-gray-300">Tepat Waktu</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-ut-yellow"></span>
                                <span class="text-xs font-bold text-ut-gray-600 dark:text-ut-gray-300">Terlambat</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="h-10 w-full bg-ut-gray-100 dark:bg-ut-gray-700 rounded-2xl p-1.5 shadow-inner">
                            <div class="flex h-full rounded-xl overflow-hidden shadow-sm">
                                <div class="bg-gradient-to-r from-emerald-500 to-emerald-400 h-full flex items-center justify-center text-[10px] font-black text-white transition-all duration-1000 ease-out"
                                     style="width: 0%" data-width="{{ $persenTepatWaktu }}">
                                    {{ $persenTepatWaktu }}%
                                </div>
                                <div class="bg-ut-yellow h-full flex items-center justify-center text-[10px] font-black text-ut-blue transition-all duration-1000 ease-out"
                                     style="width: 0%" data-width="{{ $persenTerlambat }}">
                                    @if($persenTerlambat > 10) {{ $persenTerlambat }}% @endif
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-ut-gray-400 font-medium italic text-center">Berdasarkan data absensi bulan {{ $bulanKemarin }} {{ $tahunKemarin }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tables Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Top Pegawai --}}
            <div class="bg-white dark:bg-ut-gray-800 rounded-3xl shadow-sm border border-ut-gray-100 dark:border-ut-gray-700 overflow-hidden flex flex-col">
                <div class="p-8 border-b border-ut-gray-50 dark:border-ut-gray-700 flex items-center gap-4">
                    <div class="p-3 bg-ut-yellow/10 text-ut-yellow rounded-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-ut-gray-900 dark:text-white tracking-tight">Top Performance</h3>
                </div>
                <div class="p-4 flex-1">
                    <div class="space-y-3">
                        @forelse($topPegawai as $index => $pegawai)
                            <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-ut-gray-50 dark:hover:bg-white/5 transition-all group border border-transparent hover:border-ut-gray-200 dark:hover:border-white/10">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-ut-blue/5 dark:bg-ut-yellow/10 flex items-center justify-center font-black text-ut-blue dark:text-ut-yellow group-hover:scale-110 transition-transform">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-ut-gray-900 dark:text-white group-hover:text-ut-blue dark:group-hover:text-ut-yellow transition-colors">{{ $pegawai->nama }}</p>
                                        <p class="text-xs text-ut-gray-400 font-medium uppercase">{{ $pegawai->departemen }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-black text-ut-gray-900 dark:text-white">{{ $pegawai->hadir }}</p>
                                    <p class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest">Hadir</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <p class="text-ut-gray-400 font-medium">Belum ada data</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Daily Recap --}}
            <div class="lg:col-span-2 bg-white dark:bg-ut-gray-800 rounded-3xl shadow-sm border border-ut-gray-100 dark:border-ut-gray-700 overflow-hidden">
                <div class="p-8 border-b border-ut-gray-50 dark:border-ut-gray-700 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-ut-blue/10 text-ut-blue rounded-2xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-ut-gray-900 dark:text-white tracking-tight">Rekap Harian</h3>
                    </div>
                    <span class="px-4 py-1.5 bg-ut-blue text-white rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-ut-blue/20">
                        Bulan Ini
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-ut-gray-50 dark:bg-white/5 text-ut-gray-400 text-[10px] uppercase font-black tracking-widest">
                                <th class="px-8 py-4 text-left">Tanggal</th>
                                <th class="px-8 py-4 text-center">Kehadiran</th>
                                <th class="px-8 py-4 text-center">Izin/Sakit</th>
                                <th class="px-8 py-4 text-center">Ketidakhadiran</th>
                                <th class="px-8 py-4 text-center">Lembur</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ut-gray-50 dark:divide-white/5">
                            @forelse ($rekapHarian as $r)
                                <tr class="hover:bg-ut-gray-50 dark:hover:bg-white/5 transition-all group">
                                    <td class="px-8 py-5">
                                        <p class="font-bold text-ut-gray-900 dark:text-white group-hover:translate-x-1 transition-transform">{{ \Carbon\Carbon::parse($r->tanggal)->translatedFormat('d F Y') }}</p>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/10 text-emerald-600 dark:text-emerald-400 font-black text-xs ring-1 ring-emerald-600/20">
                                            {{ $r->hadir }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="inline-flex items-center justify-center px-4 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-900/10 text-blue-600 dark:text-blue-400 font-black text-xs ring-1 ring-blue-600/20">
                                            {{ $r->izin_sakit }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="{{ $r->alpha > 0 ? 'bg-rose-50 dark:bg-rose-900/10 text-rose-600 ring-rose-600/20' : 'bg-ut-gray-100 text-ut-gray-400 ring-transparent' }} inline-flex items-center justify-center px-4 py-1.5 rounded-xl font-black text-xs ring-1">
                                            {{ $r->alpha }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="{{ $r->lembur > 0 ? 'bg-ut-yellow/10 text-ut-yellow ring-ut-yellow/20' : 'bg-ut-gray-100 text-ut-gray-400 ring-transparent' }} inline-flex items-center justify-center px-4 py-1.5 rounded-xl font-black text-xs ring-1">
                                            {{ $r->lembur }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-20 text-center">
                                        <div class="flex flex-col items-center gap-4 opacity-50">
                                            <svg class="w-16 h-16 text-ut-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                            <p class="font-bold text-ut-gray-400">Belum ada data rekap tersedia.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const clockEl = document.getElementById('clock');
            if(clockEl) {
                clockEl.textContent = now.toLocaleTimeString('id-ID', { 
                    hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false 
                }).replace(/\./g, ':');
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        document.addEventListener('DOMContentLoaded', () => {
            // Animate Progress Bars with delay
            setTimeout(() => {
                document.querySelectorAll('[data-width]').forEach(el => {
                    el.style.width = el.getAttribute('data-width') + '%';
                });
            }, 500);
        });
    </script>
@endsection
