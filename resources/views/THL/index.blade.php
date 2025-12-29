@extends('layouts.main')

@section('content')

    <div class="space-y-10 animate-fade-in">

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-1 md:space-y-2">
                <h1 class="text-3xl md:text-4xl font-black text-ut-gray-900 dark:text-white tracking-tight flex items-center gap-3 md:gap-4">
                    <div class="p-2 md:p-3 bg-ut-blue dark:bg-ut-yellow rounded-xl md:rounded-2xl text-white dark:text-ut-blue shadow-xl shadow-ut-blue/10 shrink-0">
                        <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <span>Data THL</span>
                </h1>
                <p class="text-xs md:text-sm text-ut-gray-500 dark:text-ut-gray-400 font-medium ml-1">Manajemen basis data personil dan informasi kepegawaian UT.</p>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-4 md:gap-6 w-full md:w-auto">
                {{-- SEARCH --}}
                <div class="relative w-full md:w-80 group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-ut-gray-400 group-focus-within:text-ut-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Cari personil..."
                        class="block w-full pl-14 pr-6 py-4 bg-white dark:bg-ut-gray-800 border border-ut-gray-100 dark:border-ut-gray-700 rounded-xl md:rounded-2xl text-sm font-medium focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue dark:text-white dark:placeholder-ut-gray-500 shadow-sm transition-all duration-300">
                </div>

                {{-- IMPORT BUTTON --}}
                <form id="importForm" method="POST" action="{{ route('THL.import') }}" enctype="multipart/form-data" class="w-full md:w-auto">
                    @csrf
                    <label for="importFile"
                        class="inline-flex items-center justify-center gap-3 w-full md:w-auto px-8 py-4 bg-ut-blue dark:bg-ut-blue-light hover:bg-ut-blue-light text-white rounded-xl md:rounded-2xl text-xs md:text-sm font-black shadow-2xl shadow-ut-blue/30 transition-all cursor-pointer transform hover:-translate-y-1 active:scale-95">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        IMPORT DATA
                    </label>
                    <input id="importFile" type="file" name="file" class="hidden" accept=".xlsx,.xls,.csv">
                </form>
            </div>
        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white dark:bg-ut-gray-800 rounded-[2rem] md:rounded-[2.5rem] shadow-2xl border border-ut-gray-100 dark:border-ut-gray-700 overflow-hidden group">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-ut-gray-50 dark:bg-ut-gray-900/50 text-ut-gray-400 text-[10px] items-center uppercase font-black tracking-widest border-b border-ut-gray-50 dark:border-white/5">
                            <th class="py-6 px-10 text-left">No</th>
                            <th class="py-6 px-10 text-left">Personil / NIK</th>
                            <th class="py-6 px-10 text-left">Kontak Resmi</th>
                            <th class="py-6 px-10 text-left">Divisi Penempatan</th>
                            <th class="py-6 px-10 text-center">Asal & Kelahiran</th>
                            <th class="py-6 px-10 text-left">Detail Alamat</th>
                        </tr>
                    </thead>
                    <tbody id="thlTable" class="divide-y divide-ut-gray-50 dark:divide-white/5">
                        @forelse ($dataThl as $index => $thl)
                            @php
                                $names = explode(' ', $thl->nama);
                                $initials = (isset($names[0][0]) ? $names[0][0] : '') . (isset($names[1][0]) ? $names[1][0] : '');
                                $colors = ['bg-amber-500', 'bg-emerald-500', 'bg-ut-blue', 'bg-rose-500', 'bg-purple-500', 'bg-indigo-500', 'bg-cyan-500'];
                                $color = $colors[crc32($thl->nama) % count($colors)];
                            @endphp
                            <tr class="hover:bg-ut-gray-50 dark:hover:bg-white/5 transition-all group/row active:scale-[0.998] cursor-default">
                                <td class="py-6 px-10 text-ut-gray-400 font-black text-[10px]">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-6 px-10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl {{ $color }} flex items-center justify-center text-white font-black text-xs shadow-lg group-hover/row:scale-110 transition-transform flex-shrink-0">
                                            {{ strtoupper($initials) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-black text-ut-gray-900 dark:text-white group-hover/row:text-ut-blue dark:group-hover/row:text-ut-yellow transition-colors text-base">{{ $thl->nama }}</span>
                                            <span class="text-xs text-ut-gray-400 font-bold uppercase tracking-widest">{{ $thl->nik }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-10">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2 text-xs text-ut-gray-600 dark:text-ut-gray-400 font-bold">
                                            <div class="p-1.5 bg-ut-blue/5 rounded-lg text-ut-blue">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            </div>
                                            {{ $thl->email }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-10">
                                    <span class="px-3 py-1.5 rounded-xl bg-ut-gray-100 dark:bg-ut-gray-700 text-ut-gray-500 dark:text-ut-gray-300 font-black text-[10px] uppercase tracking-widest block w-fit whitespace-nowrap shadow-sm border border-black/5">{{ $thl->departemen }}</span>
                                </td>
                                <td class="py-6 px-10 text-center">
                                    <div class="flex flex-col items-center">
                                        <span class="text-xs font-black text-ut-gray-900 dark:text-white uppercase tracking-tight">{{ $thl->kota }}</span>
                                        <span class="text-[10px] text-ut-gray-400 font-bold uppercase tracking-widest">{{ $thl->tanggal_lahir ? \Carbon\Carbon::parse($thl->tanggal_lahir)->translatedFormat('d M Y') : '-' }}</span>
                                    </div>
                                </td>
                                <td class="py-6 px-10">
                                    <p class="text-xs text-ut-gray-500 dark:text-ut-gray-400 max-w-xs truncate font-medium italic">"{{ $thl->alamat }}"</p>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="6" class="py-32 text-center">
                                    <div class="flex flex-col items-center gap-6 opacity-40">
                                        <div class="w-24 h-24 bg-ut-gray-100 dark:bg-ut-gray-900 rounded-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-ut-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-2xl font-black text-ut-gray-900 dark:text-white tracking-tight uppercase">Data Tidak Ditemukan</p>
                                            <p class="text-[10px] font-bold text-ut-gray-400 uppercase tracking-[0.2em]">Coba gunakan kata kunci lain atau bersihkan pencarian.</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- LOADING OVERLAY --}}
    <div id="loadingOverlay" class="hidden fixed inset-0 bg-ut-gray-900/60 backdrop-blur-xl flex items-center justify-center z-[9999] p-4">
        <div class="bg-white dark:bg-ut-gray-800 p-8 md:p-12 rounded-[2rem] md:rounded-[3rem] shadow-2xl w-full max-w-md text-center animate-slide-up border border-white/20 dark:border-white/5 relative overflow-hidden">
            <div class="absolute -top-12 -left-12 w-40 h-40 bg-ut-blue/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 space-y-8">
                <div class="relative w-24 h-24 mx-auto">
                    <div class="absolute inset-0 rounded-full border-8 border-ut-gray-100 dark:border-ut-gray-700/50"></div>
                    <div class="absolute inset-0 rounded-full border-8 border-ut-blue border-t-transparent animate-spin"></div>
                    <div class="absolute inset-4 bg-ut-blue/5 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-ut-blue animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                    </div>
                </div>

                <div class="space-y-2">
                    <h2 class="text-3xl font-black text-ut-gray-900 dark:text-white tracking-tight">Mengimpor Data</h2>
                    <p class="text-ut-gray-500 dark:text-ut-gray-400 font-medium">Sistem sedang memproses database personil Anda.</p>
                </div>

                <div class="space-y-4">
                    <div class="w-full bg-ut-gray-100 dark:bg-ut-gray-700 h-4 rounded-full overflow-hidden shadow-inner">
                        <div id="progressBar" class="h-full bg-gradient-to-r from-ut-blue to-ut-blue-light rounded-full transition-all duration-300 shadow-lg shadow-ut-blue/30" style="width: 0%;"></div>
                    </div>
                    <div class="flex justify-between items-center text-[11px] font-black text-ut-blue dark:text-ut-yellow uppercase tracking-[0.2em] px-2">
                        <span id="progressText">0% Diproses</span>
                        <span class="animate-pulse">Sinkronisasi...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // SEARCH FUNCTION with smooth animations
            const input = document.getElementById("searchInput");
            const rows = document.querySelectorAll("#thlTable tr");
            input.addEventListener("input", (e) => {
                const q = e.target.value.toLowerCase();
                rows.forEach(r => {
                    const cells = r.querySelectorAll('td');
                    if (cells.length > 1) {
                        if (r.textContent.toLowerCase().includes(q)) {
                            r.style.display = "";
                            r.classList.add('animate-fade-in');
                        } else {
                            r.style.display = "none";
                        }
                    }
                });
            });

            // IMPORT LOADING FUNCTION with real fetch
            const fileInput = document.getElementById("importFile");
            const overlay = document.getElementById("loadingOverlay");
            const bar = document.getElementById("progressBar");
            const text = document.getElementById("progressText");

            if (fileInput) {
                fileInput.addEventListener("change", async () => {
                    const file = fileInput.files[0];
                    if (!file) return;
                    
                    overlay.classList.remove("hidden");
                    overlay.classList.add("flex");
                    document.body.style.overflow = 'hidden';

                    const fd = new FormData();
                    fd.append("file", file);

                    // SMOOTH PROGRESS
                    let currentProgress = 0;
                    const interval = setInterval(() => {
                        if (currentProgress < 92) {
                            currentProgress += Math.random() * 5;
                            const capped = Math.min(currentProgress, 92);
                            bar.style.width = capped + "%";
                            text.textContent = Math.floor(capped) + "% DIPROSES";
                        }
                    }, 300);

                    try {
                        const response = await fetch("{{ route('THL.import') }}", {
                            method: "POST",
                            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                            body: fd
                        });
                        
                        clearInterval(interval);
                        bar.style.width = "100%";
                        text.textContent = "100% SELESAI";
                        
                        if (response.ok) {
                            setTimeout(() => window.location.reload(), 1000);
                        } else {
                            throw new Error("Import failed");
                        }
                    } catch (error) {
                        clearInterval(interval);
                        overlay.classList.add("hidden");
                        overlay.classList.remove("flex");
                         document.body.style.overflow = '';
                        alert("Terjadi kesalahan saat mengimpor data. Pastikan format file .xlsx atau .csv sudah benar.");
                    }
                });
            }
        });
    </script>
@endsection
