@extends('layouts.main')

@section('content')
    @php
        $totalHadir ??= $reports->sum('hadir');
    @endphp

    <div class="space-y-10 animate-fade-in">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
            <div class="space-y-2">
                <h1 class="text-4xl font-black text-ut-gray-900 dark:text-white tracking-tight flex items-center gap-4">
                    <div class="p-3 bg-ut-blue dark:bg-ut-yellow rounded-2xl text-white dark:text-ut-blue shadow-xl shadow-ut-blue/10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span>Rekap Absensi THL</span>
                </h1>
                <p class="text-ut-gray-500 dark:text-ut-gray-400 font-medium ml-1">Laporan komprehensif kehadiran personil THL LPPMP.</p>
            </div>

            <button id="openExportModal"
                class="inline-flex items-center gap-3 px-8 py-4 bg-ut-blue hover:bg-ut-blue-light text-white rounded-2xl text-sm font-black shadow-2xl shadow-ut-blue/30 transition-all transform hover:-translate-y-1 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Laporan
            </button>
        </div>

        {{-- Filter & Tools --}}
        <div class="bg-white/50 dark:bg-ut-gray-800/50 backdrop-blur-xl p-6 rounded-3xl border border-ut-gray-100 dark:border-ut-gray-700 shadow-xl flex flex-col md:flex-row gap-6 items-center">
            {{-- Filter Bulan --}}
            <div class="flex items-center gap-4 w-full md:w-auto">
                <span class="text-[10px] uppercase font-black text-ut-gray-400 tracking-[0.2em]">Periode:</span>
                <form action="{{ route('Athl.report') }}" method="GET" class="flex-grow md:flex-grow-0">
                    <select name="month" onchange="this.form.submit()"
                        class="w-full bg-white dark:bg-ut-gray-700 border border-ut-gray-100 dark:border-ut-gray-600 rounded-2xl px-6 py-3 text-sm font-bold focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue transition-all dark:text-white cursor-pointer shadow-sm">
                        @php
                            $bulanIndo = [
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            ];
                            $selectedMonth = request('month', now()->format('Y-m'));
                        @endphp
                        @for ($y = date('Y'); $y >= date('Y') - 2; $y--)
                            @foreach ($bulanIndo as $num => $nama)
                                @php $val = "$y-$num"; @endphp
                                <option value="{{ $val }}" {{ $selectedMonth == $val ? 'selected' : '' }}>
                                    {{ $nama }} {{ $y }}
                                </option>
                            @endforeach
                        @endfor
                    </select>
                </form>
            </div>

            <div class="h-10 w-px bg-ut-gray-100 dark:bg-ut-gray-700 hidden md:block"></div>

            {{-- Search --}}
            <div class="relative flex-grow group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-ut-gray-400 group-focus-within:text-ut-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Cari personil atau departemen..."
                    class="block w-full pl-14 pr-6 py-3.5 bg-white dark:bg-ut-gray-700 border border-ut-gray-100 dark:border-ut-gray-600 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue transition-all dark:text-white dark:placeholder-ut-gray-500 shadow-sm">
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-6 gap-6">
            @php
                $stats = [
                    ['Total Personil', $reports->count(), 'ut-blue', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-1a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'bg-ut-blue/5 dark:bg-ut-blue/10'],
                    ['Total Hadir', $reports->sum('hadir'), 'emerald', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'bg-emerald-50 dark:bg-emerald-900/10'],
                    ['Total Sakit', $reports->sum('sakit'), 'blue', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'bg-blue-50 dark:bg-blue-900/10'],
                    ['Total Izin', $reports->sum('izin'), 'indigo', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'bg-indigo-50 dark:bg-indigo-900/10'],
                    ['Total Alpha', $reports->sum('alpha'), 'rose', 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'bg-rose-50 dark:bg-rose-900/10'],
                    ['Total Lembur', $reports->sum('lembur'), 'ut-yellow', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'bg-ut-yellow/5 dark:bg-ut-yellow/10']
                ];
            @endphp

            @foreach ($stats as [$label, $value, $color, $icon, $bg])
                <div class="bg-white dark:bg-ut-gray-800 p-6 rounded-3xl shadow-sm border border-ut-gray-100 dark:border-ut-gray-700 flex flex-col items-center justify-center group hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-{{ $color == 'ut-blue' ? 'ut-blue' : ($color == 'ut-yellow' ? 'ut-yellow' : $color.'-500') }}"></div>
                    <div class="p-3 mb-4 rounded-xl {{ $bg }} text-{{ $color == 'ut-blue' ? 'ut-blue' : ($color == 'ut-yellow' ? 'ut-yellow' : $color.'-600') }} group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-black text-ut-gray-900 dark:text-white">{{ $value }}</h3>
                    <p class="text-[9px] text-ut-gray-400 font-black uppercase tracking-[0.2em] mt-2 text-center">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        {{-- Main Table --}}
        <div class="bg-white dark:bg-ut-gray-800 rounded-[2rem] shadow-xl border border-ut-gray-100 dark:border-ut-gray-700 overflow-hidden group">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-ut-gray-50 dark:bg-white/5 text-ut-gray-400 text-[10px] items-center uppercase font-black tracking-widest border-b border-ut-gray-50 dark:border-white/5">
                            <th class="py-6 px-8 text-left">No</th>
                            <th class="py-6 px-8 text-left">Personil</th>
                            <th class="py-6 px-8 text-left">Departemen</th>
                            <th class="py-6 px-8 text-center text-emerald-500">Hadir</th>
                            <th class="py-6 px-8 text-center text-blue-500">Sakit</th>
                            <th class="py-6 px-8 text-center text-indigo-500">Izin</th>
                            <th class="py-6 px-8 text-center text-rose-500">Alpha</th>
                            <th class="py-6 px-8 text-center text-ut-yellow font-black">Lembur</th>
                        </tr>
                    </thead>
                    <tbody id="reportTable" class="divide-y divide-ut-gray-50 dark:divide-white/5">
                        @forelse ($reports as $index => $data)
                            <tr class="hover:bg-ut-gray-50 dark:hover:bg-white/5 transition-all group/row">
                                <td class="py-5 px-8 text-ut-gray-400 font-black text-[10px]">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-5 px-8">
                                    <p class="font-bold text-ut-gray-900 dark:text-white group-hover/row:text-ut-blue dark:group-hover/row:text-ut-yellow transition-colors whitespace-nowrap">{{ $data->nama_karyawan }}</p>
                                </td>
                                <td class="py-5 px-8">
                                    <span class="px-3 py-1.5 rounded-xl bg-ut-gray-100 dark:bg-ut-gray-700 text-ut-gray-500 dark:text-ut-gray-300 font-bold text-[10px] uppercase tracking-wider block w-fit whitespace-nowrap">{{ $data->departemen }}</span>
                                </td>
                                @foreach(['hadir', 'sakit', 'izin', 'alpha'] as $key)
                                    @php
                                        $colorClass = match($key) {
                                            'hadir' => 'bg-emerald-50 text-emerald-600 ring-emerald-600/20',
                                            'sakit' => 'bg-blue-50 text-blue-600 ring-blue-600/20',
                                            'izin' => 'bg-indigo-50 text-indigo-600 ring-indigo-600/20',
                                            'alpha' => 'bg-rose-50 text-rose-600 ring-rose-600/20',
                                        };
                                        $emptyClass = 'bg-ut-gray-100 text-ut-gray-300 ring-transparent opacity-50';
                                    @endphp
                                    <td class="py-5 px-8 text-center">
                                        <span class="inline-flex items-center justify-center min-w-[40px] h-10 px-3 rounded-2xl font-black text-xs ring-1 transition-all group-hover/row:scale-110 {{ $data->$key > 0 ? $colorClass : $emptyClass }}">
                                            {{ $data->$key }}
                                        </span>
                                    </td>
                                @endforeach
                                <td class="py-5 px-8 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[40px] h-10 px-3 rounded-2xl font-black text-xs ring-1 transition-all group-hover/row:scale-110 {{ $data->lembur > 0 ? 'bg-ut-yellow/10 text-ut-yellow ring-ut-yellow/20' : 'bg-ut-gray-100 text-ut-gray-300 ring-transparent opacity-50' }}">
                                        {{ $data->lembur }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-20 text-center">
                                    <div class="flex flex-col items-center gap-4 opacity-30">
                                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        <p class="font-bold text-ut-gray-400">Belum ada data tersedia untuk periode terpilih.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Export --}}
    <div id="exportModal" class="hidden fixed inset-0 bg-ut-gray-900/60 backdrop-blur-md items-center justify-center z-[100] p-4">
        <div class="bg-white dark:bg-ut-gray-800 w-full max-w-lg p-10 rounded-[2.5rem] shadow-[0_35px_60px_-15px_rgba(0,0,0,0.3)] border border-white/20 dark:border-white/5 animate-slide-up relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-ut-blue/10 rounded-full blur-3xl"></div>
            
            <button id="closeExportModal" class="absolute top-8 right-8 p-2 text-ut-gray-400 hover:text-ut-gray-900 dark:hover:text-white hover:bg-ut-gray-100 dark:hover:bg-white/10 rounded-xl transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div class="mb-10 text-center space-y-3 relative z-10">
                <div class="w-20 h-20 bg-ut-blue text-white rounded-3xl flex items-center justify-center mx-auto shadow-2xl shadow-ut-blue/30 mb-6 group-hover:rotate-12 transition-transform">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                </div>
                <h2 class="text-3xl font-black text-ut-gray-900 dark:text-white tracking-tight">Export Laporan</h2>
                <p class="text-ut-gray-500 dark:text-ut-gray-400 font-medium">Konfigurasi format dan periode data ekspor.</p>
            </div>

            <form id="exportForm" action="{{ route('Athl.report') }}" method="GET" class="space-y-8 relative z-10">
                <input type="hidden" id="downloadToken" name="download_token">

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-ut-gray-400 uppercase tracking-[0.2em] ml-4">Nama File Laporan</label>
                    <input type="text" name="filename"
                        class="w-full bg-ut-gray-50 dark:bg-ut-gray-700/50 border border-ut-gray-100 dark:border-ut-gray-600 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue dark:text-white transition-all shadow-inner"
                        placeholder="Contoh: Rekap-THL-Januari">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-ut-gray-400 uppercase tracking-[0.2em] ml-4">Periode</label>
                        <input type="month" name="month" value="{{ $selectedMonth }}"
                            class="w-full bg-ut-gray-50 dark:bg-ut-gray-700/50 border border-ut-gray-100 dark:border-ut-gray-600 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue dark:text-white transition-all shadow-inner cursor-pointer">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-ut-gray-400 uppercase tracking-[0.2em] ml-4">Format File</label>
                        <select name="format"
                            class="w-full bg-ut-gray-50 dark:bg-ut-gray-700/50 border border-ut-gray-100 dark:border-ut-gray-600 rounded-2xl px-6 py-4 text-sm font-bold focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue dark:text-white transition-all shadow-inner cursor-pointer">
                            <option value="xlsx">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" id="exportButton"
                        class="w-full py-5 bg-gradient-to-r from-ut-blue to-ut-blue-light text-white rounded-[1.5rem] font-black text-sm shadow-2xl shadow-ut-blue/30 transition-all transform hover:-translate-y-1 active:scale-[0.98] flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Proses & Download
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Global Loading --}}
    <div id="loadingOverlay" class="fixed inset-0 bg-ut-gray-900/40 backdrop-blur-sm hidden items-center justify-center z-[200]">
        <div class="w-20 h-20 border-8 border-white/20 border-t-ut-blue rounded-full animate-spin"></div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Search Functionality with smooth row filters
            const searchInput = document.getElementById("searchInput");
            if (searchInput) {
                const rows = document.querySelectorAll("#reportTable tr");
                searchInput.addEventListener("input", (e) => {
                    const keyword = e.target.value.toLowerCase();
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(keyword)) {
                            row.style.display = "";
                            row.classList.add('animate-fade-in');
                        } else {
                            row.style.display = "none";
                        }
                    });
                });
            }

            // Modal Control with smooth animations
            const exportModal = document.getElementById("exportModal");
            const openBtn = document.getElementById("openExportModal");
            const closeBtn = document.getElementById("closeExportModal");

            openBtn?.addEventListener("click", () => {
                exportModal.classList.remove("hidden");
                exportModal.classList.add("flex");
                document.body.style.overflow = 'hidden';
            });

            const hideModal = () => {
                exportModal.classList.add("hidden");
                exportModal.classList.remove("flex");
                document.body.style.overflow = '';
            };

            closeBtn?.addEventListener("click", hideModal);
            exportModal.addEventListener("click", (e) => {
                if (e.target === exportModal) hideModal();
            });

            // Handle Export Form
            document.getElementById('exportForm')?.addEventListener('submit', () => {
                const loadingOverlay = document.getElementById('loadingOverlay');
                loadingOverlay.classList.remove('hidden');
                loadingOverlay.classList.add('flex');
                
                setTimeout(() => {
                    hideModal();
                    loadingOverlay.classList.add('hidden');
                    loadingOverlay.classList.remove('flex');
                }, 2000);
            });
        });
    </script>
@endsection
