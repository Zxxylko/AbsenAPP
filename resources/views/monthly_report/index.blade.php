@extends('layouts.main')

@section('content')

<style>
    /* Premium Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { 
        width: 5px; 
        height: 5px; 
        transition: all 0.3s ease;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb { 
        background: linear-gradient(180deg, #0284c7 0%, #0ea5e9 100%); 
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }
    .custom-scrollbar:hover::-webkit-scrollbar { 
        width: 8px; 
        height: 8px; 
    }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { 
        background: #0284c7;
        box-shadow: 0 0 10px rgba(2, 132, 199, 0.3);
    }
    .custom-scrollbar::-webkit-scrollbar-track { 
        background: rgba(226, 232, 240, 0.1); 
        border-radius: 20px;
        margin: 4px;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(30, 41, 59, 0.3);
    }
    .custom-scrollbar::-webkit-scrollbar-corner {
        background: transparent;
    }

    /* Sticky Table Header */
    .sticky-header th {
        @apply sticky top-0 z-20 bg-ut-gray-50/95 dark:bg-ut-gray-900/95 backdrop-blur-md shadow-sm border-b border-ut-gray-100 dark:border-white/5;
    }

    /* Accordion Animation */
    .accordion-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
        opacity: 0;
    }
    .accordion-content.open {
        max-height: 2000px; 
        opacity: 1;
    }

    /* Skeleton Loader */
    .skeleton {
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
    }
    @keyframes skeleton-loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    /* Table Hover Scale */
    .table-row-hover {
        @apply transition-all duration-200 hover:bg-ut-gray-50 dark:hover:bg-white/5 active:scale-[0.99];
    }

    .avatar-circle {
        @apply w-10 h-10 rounded-full flex items-center justify-center font-black text-xs border-2 border-white dark:border-ut-gray-700 shadow-sm;
    }

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="space-y-10 animate-fade-in px-4 md:px-0">
    {{-- Header Content --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-1 md:space-y-2">
            <h1 class="text-3xl md:text-4xl font-black text-ut-gray-900 dark:text-white tracking-tight flex items-center gap-3 md:gap-4">
                <div class="p-2 md:p-3 bg-ut-blue dark:bg-ut-yellow rounded-xl md:rounded-2xl text-white dark:text-ut-blue shadow-xl shadow-ut-blue/10 shrink-0">
                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span>Laporan Absensi</span>
            </h1>
            <p class="text-xs md:text-sm text-ut-gray-500 dark:text-ut-gray-400 font-medium ml-1">Pantau dan kelola data kehadiran THL secara profesional.</p>
        </div>

        <div class="grid grid-cols-2 md:flex items-center gap-3 md:gap-4">
            <button id="openExportModal" class="px-5 py-3 md:px-6 md:py-3.5 bg-ut-blue hover:bg-ut-blue-light dark:bg-ut-yellow dark:text-ut-blue dark:hover:bg-ut-yellow/90 text-white rounded-xl md:rounded-2xl font-black text-[10px] md:text-sm shadow-2xl shadow-ut-blue/20 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2 md:gap-3">
                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                EXPORT
            </button>
            <form id="importForm" action="{{ route('reportmonthly.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="px-5 py-3 md:px-6 md:py-3.5 bg-white dark:bg-ut-gray-800 border border-ut-gray-100 dark:border-ut-gray-700 text-ut-gray-700 dark:text-ut-gray-300 rounded-xl md:rounded-2xl font-black text-[10px] md:text-sm shadow-sm hover:bg-ut-gray-50 dark:hover:bg-ut-gray-700 transition-all cursor-pointer flex items-center justify-center gap-2 md:gap-3">
                    <svg class="w-4 h-4 md:w-5 md:h-5 text-ut-blue dark:text-ut-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0l-4 4m4-4v12"/>
                    </svg>
                    IMPORT
                    <input type="file" name="file" class="hidden" onchange="this.form.submit()">
                </label>
            </form>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="bg-white/50 dark:bg-ut-gray-800/50 backdrop-blur-xl p-4 md:p-6 rounded-[2rem] md:rounded-[2.5rem] border border-ut-gray-100 dark:border-ut-gray-700 shadow-xl space-y-6 md:space-y-8">
        <div class="flex flex-col xl:flex-row items-stretch xl:items-center gap-4 md:gap-6">
            <div class="relative flex-grow group">
                <span class="absolute left-5 md:left-6 top-1/2 -translate-y-1/2 text-ut-gray-400 group-focus-within:text-ut-blue transition-colors">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </span>
                <input type="text" id="globalSearch" placeholder="Cari personil atau ID..." 
                       class="w-full pl-14 md:pl-16 pr-6 py-4 md:py-5 bg-white dark:bg-ut-gray-900 border border-ut-gray-50 dark:border-ut-gray-700 rounded-xl md:rounded-2xl focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue dark:text-white transition-all text-sm font-bold shadow-sm">
                <button id="clearSearch" class="absolute right-6 top-1/2 -translate-y-1/2 text-ut-gray-400 hover:text-rose-500 hidden transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Date Range Filter --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 md:gap-4 w-full xl:w-auto">
                <div class="relative flex-grow sm:w-44 xl:w-48">
                    <input type="date" id="startDate" class="w-full pl-5 pr-4 py-4 md:py-4 bg-white dark:bg-ut-gray-900 border border-ut-gray-50 dark:border-ut-gray-700 rounded-xl md:rounded-2xl focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue dark:text-white transition-all text-[10px] md:text-xs font-bold shadow-sm appearance-none" title="Mulai Tanggal">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-ut-gray-400 pointer-events-none text-[8px] md:text-[10px] font-black uppercase tracking-widest hidden sm:block opacity-40">Dari</span>
                </div>
                <div class="text-ut-gray-400 font-bold hidden sm:block">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
                <div class="relative flex-grow sm:w-44 xl:w-48">
                    <input type="date" id="endDate" class="w-full pl-5 pr-4 py-4 md:py-4 bg-white dark:bg-ut-gray-900 border border-ut-gray-50 dark:border-ut-gray-700 rounded-xl md:rounded-2xl focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue dark:text-white transition-all text-[10px] md:text-xs font-bold shadow-sm appearance-none" title="Sampai Tanggal">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-ut-gray-400 pointer-events-none text-[8px] md:text-[10px] font-black uppercase tracking-widest hidden sm:block opacity-40">Ke</span>
                </div>
            </div>

            <div class="w-full md:w-auto xl:w-80 shrink-0">
                <div class="flex flex-col gap-2 md:gap-3">
                    <label class="text-[8px] md:text-[10px] font-black text-ut-gray-400 uppercase tracking-widest md:tracking-[0.2em] ml-2 md:ml-4">Filter Divisi</label>
                    <div class="relative group">
                        <select id="deptSelect" class="w-full pl-5 md:pl-6 pr-12 py-3.5 md:py-4 bg-white dark:bg-ut-gray-900 border border-ut-gray-50 dark:border-ut-gray-700 rounded-xl md:rounded-2xl focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue dark:text-white transition-all text-xs md:text-sm font-bold shadow-sm appearance-none cursor-pointer">
                            <option value="All">Semua Divisi</option>
                            @foreach($departemenList as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-5 md:right-6 top-1/2 -translate-y-1/2 pointer-events-none text-ut-gray-400 group-focus-within:text-ut-blue transition-colors">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Monthly Sections --}}
    <div class="space-y-6" id="monthlyAccordions">
        @foreach($months as $m)
            <div class="bg-white dark:bg-ut-gray-800 rounded-[2.5rem] shadow-2xl border border-ut-gray-100 dark:border-ut-gray-700 overflow-hidden group/accordion month-section" data-month-val="{{ $m->month_val }}">
                <button class="w-full flex items-center justify-between px-5 md:px-8 py-4 md:py-6 text-left hover:bg-ut-gray-50 dark:hover:bg-white/5 transition-all accordion-trigger" 
                        data-month="{{ $m->month_val }}">
                    <div class="flex items-center gap-4 md:gap-6">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-ut-blue/5 dark:bg-ut-yellow/5 rounded-xl md:rounded-[1.25rem] flex items-center justify-center text-ut-blue dark:text-ut-yellow group-hover/accordion:scale-110 transition-transform duration-500 shrink-0">
                            <svg class="w-5 h-5 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col overflow-hidden">
                            <span class="text-base md:text-xl font-black text-ut-gray-900 dark:text-white tracking-tight uppercase tracking-wider truncate">{{ $m->month_name }}</span>
                            <span class="text-[8px] md:text-[10px] font-bold text-ut-gray-400 uppercase tracking-widest md:tracking-[0.2em] truncate">Kehadiran Bulanan</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="hidden md:inline-flex px-4 py-2 bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest rounded-full data-count-badge">Terdata</span>
                        <div class="p-2 rounded-xl bg-ut-gray-50 dark:bg-ut-gray-900 text-ut-gray-400 transform transition-all duration-300 chevron">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </button>
                <div class="accordion-content">
                    <div class="px-4 md:px-8 pb-8 md:pb-10">
                        <div class="overflow-auto custom-scrollbar rounded-2xl md:rounded-3xl border border-ut-gray-50 dark:border-ut-gray-700 max-h-[600px] relative">
                            <table class="min-w-full text-sm">
                                <thead class="sticky-header text-ut-gray-400 font-black uppercase text-[10px] tracking-widest">
                                    <tr>
                                        <th class="px-8 py-5 text-left">Profil / Nama</th>
                                        <th class="px-6 py-5 text-left">Divisi</th>
                                        <th class="px-6 py-5 text-left">Tanggal</th>
                                        <th class="px-6 py-5 text-center">Masuk</th>
                                        <th class="px-6 py-5 text-center">Pulang</th>
                                        <th class="px-6 py-5 text-left">Keterangan</th>
                                        <th class="px-8 py-5 text-right">Lembur</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-ut-gray-50 dark:divide-ut-gray-700 data-container" data-month-loaded="false">
                                    {{-- Data lazy loaded --}}
                                </tbody>
                            </table>
                        </div>
                        <div class="empty-state hidden py-20 text-center">
                            <div class="flex flex-col items-center gap-4 opacity-30">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <p class="text-lg font-black text-ut-gray-900 dark:text-white uppercase tracking-widest">Tidak Ada Data Ditemukan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Global Empty State --}}
    <div id="noResultsGlobal" class="hidden flex flex-col items-center justify-center py-32 text-center animate-fade-in">
        <div class="p-10 bg-white dark:bg-ut-gray-800 rounded-[3rem] shadow-2xl border border-ut-gray-100 dark:border-ut-gray-700 flex flex-col items-center gap-6">
            <div class="w-24 h-24 bg-rose-500/10 rounded-full flex items-center justify-center text-rose-500">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div class="space-y-2">
                <h3 class="text-2xl font-black text-ut-gray-900 dark:text-white uppercase tracking-tight">Data Tidak Ditemukan</h3>
                <p class="text-ut-gray-400 font-bold uppercase tracking-widest text-xs">Coba cari dengan kata kunci lain, pilih bulan berbeda, atau bersihkan filter.</p>
            </div>
            <button onclick="resetAllFilters()" class="px-8 py-4 bg-ut-blue text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-ut-blue/20 hover:scale-105 transition-transform">
                Bersihkan Semua Filter
            </button>
        </div>
    </div>
</div>

{{-- Export Modal --}}
<div id="exportModal" class="fixed inset-0 bg-ut-gray-900/60 backdrop-blur-md hidden items-center justify-center z-[100] animate-fade-in p-4">
    <div class="bg-white dark:bg-ut-gray-800 rounded-[2rem] md:rounded-[3rem] shadow-2xl w-full max-w-xl p-6 md:p-10 transform animate-slide-up relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-60 h-60 bg-ut-blue/5 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-8 md:mb-10">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="p-2 md:p-3 bg-ut-blue/10 dark:bg-ut-yellow/10 rounded-xl md:rounded-2xl text-ut-blue dark:text-ut-yellow">
                        <svg class="w-5 h-5 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-3xl font-black text-ut-gray-900 dark:text-white tracking-tight uppercase">Export Laporan</h2>
                        <p class="text-xs font-bold text-ut-gray-400 uppercase tracking-widest">Pilih parameter data untuk diunduh.</p>
                    </div>
                </div>
                <button id="closeExportModal" class="p-3 hover:bg-rose-500/10 hover:text-rose-500 rounded-2xl transition-all text-ut-gray-400">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="exportForm" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-ut-gray-400 uppercase tracking-[0.2em] ml-4">Periode Bulan</label>
                        <input type="month" name="month" id="exportMonth" required
                               class="w-full bg-ut-gray-50 dark:bg-ut-gray-900 border border-ut-gray-50 dark:border-ut-gray-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue dark:text-white transition-all font-bold">
                    </div>
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-ut-gray-400 uppercase tracking-[0.2em] ml-4">Divisi / Departemen</label>
                        <select name="departemen" id="exportDept" 
                                class="w-full bg-ut-gray-50 dark:bg-ut-gray-900 border border-ut-gray-50 dark:border-ut-gray-700 rounded-2xl px-6 py-4 focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue dark:text-white transition-all font-bold appearance-none cursor-pointer">
                            <option value="">Semua Divisi</option>
                            @foreach($departemenList as $dept)
                                <option value="{{ $dept }}">{{ $dept }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-[10px] font-black text-ut-gray-400 uppercase tracking-[0.2em] ml-4">Format Dokumen</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="format" value="xlsx" class="hidden peer" checked>
                            <div class="flex flex-col items-center gap-3 py-6 bg-ut-gray-50 dark:bg-ut-gray-900 rounded-3xl border-2 border-transparent peer-checked:border-emerald-500 peer-checked:bg-emerald-500/5 transition-all text-sm font-black text-ut-gray-400 peer-checked:text-emerald-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                EXCEL
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="format" value="pdf" class="hidden peer">
                            <div class="flex flex-col items-center gap-3 py-6 bg-ut-gray-50 dark:bg-ut-gray-900 rounded-3xl border-2 border-transparent peer-checked:border-ut-blue peer-checked:bg-ut-blue/5 transition-all text-sm font-black text-ut-gray-400 peer-checked:text-ut-blue">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                PDF
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="format" value="csv" class="hidden peer">
                            <div class="flex flex-col items-center gap-3 py-6 bg-ut-gray-50 dark:bg-ut-gray-900 rounded-3xl border-2 border-transparent peer-checked:border-amber-500 peer-checked:bg-amber-500/5 transition-all text-sm font-black text-ut-gray-400 peer-checked:text-amber-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                CSV
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full py-5 bg-ut-blue hover:bg-ut-blue-light text-white rounded-3xl font-black text-lg shadow-2xl shadow-ut-blue/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-4 mt-6">
                    MULAI DOWNLOAD
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    let currentDept = 'All';
    let searchQuery = '';
    const loadedData = {}; 

    function getInitials(name) {
        return name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
    }

    function getRandomColor(name) {
        const colors = ['bg-amber-500', 'bg-emerald-500', 'bg-ut-blue', 'bg-rose-500', 'bg-purple-500', 'bg-indigo-500', 'bg-cyan-500'];
        let hash = 0;
        for (let i = 0; i < name.length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
        return colors[Math.abs(hash) % colors.length];
    }

    // --- Accordion Logic ---
    document.querySelectorAll('.accordion-trigger').forEach(trigger => {
        trigger.addEventListener('click', async () => {
            const container = trigger.closest('.bg-white');
            const content = container.querySelector('.accordion-content');
            const chevron = trigger.querySelector('.chevron');
            const monthVal = trigger.dataset.month;
            const dataContainer = content.querySelector('.data-container');

            const isOpen = content.classList.contains('open');
            
            if (!isOpen) {
                content.classList.add('open');
                chevron.classList.add('rotate-180', 'text-ut-blue');
                
                if (dataContainer.dataset.monthLoaded === 'false') {
                    await fetchMonthData(monthVal, dataContainer);
                } else {
                    applyFilters(dataContainer);
                }
            } else {
                content.classList.remove('open');
                chevron.classList.remove('rotate-180', 'text-ut-blue');
            }
        });
    });

    // --- Loading Data Logic ---
    async function fetchMonthData(month, container) {
        container.innerHTML = Array(5).fill(0).map(() => `
            <tr>
                <td class="px-8 py-5"><div class="flex items-center gap-3"><div class="w-10 h-10 skeleton rounded-full"></div><div class="h-4 w-32 skeleton rounded"></div></div></td>
                <td class="px-6 py-5"><div class="h-4 w-24 skeleton rounded"></div></td>
                <td class="px-6 py-5"><div class="h-4 w-24 skeleton rounded"></div></td>
                <td class="px-6 py-5"><div class="h-10 w-20 mx-auto skeleton rounded-xl"></div></td>
                <td class="px-6 py-5"><div class="h-10 w-20 mx-auto skeleton rounded-xl"></div></td>
                <td class="px-6 py-5"><div class="h-8 w-24 skeleton rounded-lg"></div></td>
                <td class="px-8 py-5"><div class="h-4 w-16 ml-auto skeleton rounded"></div></td>
            </tr>
        `).join('');

        try {
            const response = await fetch(`/monthly-report/fetch?month=${month}`);
            const result = await response.json();
            
            if (result.success) {
                loadedData[month] = result.data;
                container.dataset.monthLoaded = 'true';
                renderData(month, container);
            }
        } catch (error) {
            container.innerHTML = '<tr><td colspan="7" class="text-center py-10"><div class="flex flex-col items-center gap-2 text-rose-500 font-bold"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> Gagal memuat data.</div></td></tr>';
        }
    }

    function renderData(month, container) {
        const items = loadedData[month];
        container.innerHTML = items.map(r => `
            <tr class="table-row-hover row-item border-b border-ut-gray-50 dark:border-ut-gray-700/50" 
                data-dept="${r.departemen}" 
                data-date="${r.tanggal}"
                data-search="${(r.nama_karyawan + ' ' + r.id_karyawan + ' ' + r.departemen).toLowerCase()}">
                <td class="px-8 py-5">
                    <div class="flex items-center gap-4">
                        <div class="avatar-circle ${getRandomColor(r.nama_karyawan)} text-white">
                            ${getInitials(r.nama_karyawan)}
                        </div>
                        <div class="flex flex-col">
                            <span class="font-black text-ut-gray-900 dark:text-white tracking-tight">${r.nama_karyawan}</span>
                            <span class="text-[10px] text-ut-gray-400 font-black tracking-widest uppercase">${r.id_karyawan}</span>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5">
                    <span class="px-3 py-1 bg-ut-gray-50 dark:bg-white/5 text-ut-gray-500 dark:text-ut-gray-400 font-black text-[10px] uppercase tracking-widest rounded-full ring-1 ring-inset ring-ut-gray-100 dark:ring-white/10">${r.departemen}</span>
                </td>
                <td class="px-6 py-5 text-ut-gray-500 dark:text-ut-gray-400 font-bold">${r.formatted_date}</td>
                <td class="px-6 py-5 text-center">
                    <div class="px-3 py-2 rounded-xl text-[10px] font-black tracking-widest uppercase ${r.status_masuk_color} shadow-sm border border-black/5">
                        ${r.jam_masuk_fmt}<br><span class="font-bold opacity-60 text-[8px]">${r.status_masuk_label}</span>
                    </div>
                </td>
                <td class="px-6 py-5 text-center">
                    <div class="px-3 py-2 rounded-xl text-[10px] font-black tracking-widest uppercase ${r.status_pulang_color} shadow-sm border border-black/5">
                        ${r.jam_pulang_fmt}<br><span class="font-bold opacity-60 text-[8px]">${r.status_pulang_label}</span>
                    </div>
                </td>
                <td class="px-6 py-5">
                    ${r.keterangan === 'Hadir' ? 
                        '<span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-500 font-black text-[10px] uppercase tracking-widest"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>HADIR</span>' :
                        `<select data-id="${r.id}" class="ket-dropdown bg-ut-gray-50 dark:bg-ut-gray-900 border border-ut-gray-100 dark:border-ut-gray-700 rounded-xl text-[10px] font-black uppercase tracking-widest py-2 px-4 focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue dark:text-gray-300 transition-all cursor-pointer">
                            <option value="" ${!r.keterangan ? 'selected' : ''}>TANPA KET.</option>
                            <option value="Izin" ${r.keterangan === 'Izin' ? 'selected' : ''}>IZIN</option>
                            <option value="Sakit" ${r.keterangan === 'Sakit' ? 'selected' : ''}>SAKIT</option>
                            <option value="Alpha" ${r.keterangan === 'Alpha' ? 'selected' : ''}>ALPHA</option>
                            <option value="Libur" ${r.keterangan === 'Libur' ? 'selected' : ''}>LIBUR</option>
                        </select>`
                    }
                </td>
                <td class="px-8 py-5 text-right font-black text-xs text-ut-gray-900 dark:text-white">${r.lembur}</td>
            </tr>
        `).join('');
        applyFilters(container);
    }

    // --- Filtering Logic ---
    const globalSearch = document.getElementById('globalSearch');
    const clearSearch = document.getElementById('clearSearch');
    const deptSelect = document.getElementById('deptSelect');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');

    globalSearch.addEventListener('input', (e) => {
        searchQuery = e.target.value.toLowerCase();
        clearSearch.classList.toggle('hidden', searchQuery === '');
        triggerGlobalFilter();
    });

    clearSearch.addEventListener('click', () => {
        globalSearch.value = '';
        searchQuery = '';
        clearSearch.classList.add('hidden');
        triggerGlobalFilter();
    });

    startDateInput.addEventListener('change', triggerGlobalFilter);
    endDateInput.addEventListener('change', triggerGlobalFilter);

    deptSelect.addEventListener('change', (e) => {
        currentDept = e.target.value;
        triggerGlobalFilter();
    });

    function triggerGlobalFilter() {
        let anyVisibleTotal = false;
        
        // Filter Month Sections
        document.querySelectorAll('.month-section').forEach(section => {
            section.classList.remove('hidden');
            const container = section.querySelector('.data-container');
            if(container.dataset.monthLoaded === 'true') {
                const visibleInMonth = applyFilters(container);
                if(visibleInMonth > 0) anyVisibleTotal = true;
            } else {
                anyVisibleTotal = true; 
            }
        });

        const noResults = document.getElementById('noResultsGlobal');
        const accordions = document.getElementById('monthlyAccordions');
        
        const hasDateRange = startDateInput.value || endDateInput.value;
        const hasFilters = searchQuery !== '' || currentDept !== 'All' || hasDateRange;
        
        if (!anyVisibleTotal && hasFilters) {
            accordions.classList.add('hidden');
            noResults.classList.remove('hidden');
        } else {
            accordions.classList.remove('hidden');
            noResults.classList.add('hidden');
        }
    }

    function applyFilters(container) {
        const rows = container.querySelectorAll('.row-item');
        let visibleCount = 0;
        
        const startDate = startDateInput.value ? new Date(startDateInput.value) : null;
        const endDate = endDateInput.value ? new Date(endDateInput.value) : null;

        rows.forEach(row => {
            const rowDate = new Date(row.dataset.date);
            const matchesDept = currentDept === 'All' || row.dataset.dept === currentDept;
            const matchesSearch = row.dataset.search.includes(searchQuery);
            
            let matchesDate = true;
            if (startDate && rowDate < startDate) matchesDate = false;
            if (endDate && rowDate > endDate) matchesDate = false;

            if (matchesDept && matchesSearch && matchesDate) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });

        const section = container.closest('.month-section');
        const empty = section.querySelector('.empty-state');
        const table = section.querySelector('table');
        
        const hasDateRange = startDateInput.value || endDateInput.value;
        const hasFilters = searchQuery !== '' || currentDept !== 'All' || hasDateRange;
        
        if (visibleCount === 0 && hasFilters) {
            table.classList.add('hidden');
            empty.classList.remove('hidden');
            section.classList.add('opacity-50');
        } else {
            table.classList.remove('hidden');
            empty.classList.add('hidden');
            section.classList.remove('opacity-50');
        }
        return visibleCount;
    }

    window.resetAllFilters = function() {
        globalSearch.value = '';
        searchQuery = '';
        clearSearch.classList.add('hidden');
        startDateInput.value = '';
        endDateInput.value = '';
        deptSelect.value = 'All';
        currentDept = 'All';
        triggerGlobalFilter();
    }

    // --- Modal Logic ---
    const modal = document.getElementById('exportModal');
    const openBtn = document.getElementById('openExportModal');
    const closeBtn = document.getElementById('closeExportModal');

    openBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });
    
    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });
    
    window.addEventListener('click', (e) => {
        if (e.target === modal) closeBtn.click();
    });

    // Handle export form submission
    document.getElementById('exportForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const month = document.getElementById('exportMonth').value;
        const dept = document.getElementById('exportDept').value;
        const format = this.querySelector('input[name="format"]:checked').value;
        window.location.href = `/monthly-report/export?month=${month}&departemen=${dept}&format=${format}`;
    });

    // --- Manual Keterangan Update ---
    document.addEventListener('change', async (e) => {
        if (e.target.classList.contains('ket-dropdown')) {
            const dropdown = e.target;
            const id = dropdown.dataset.id;
            const value = dropdown.value;
            
            dropdown.disabled = true;
            try {
                const response = await fetch(`/update-keterangan/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ keterangan: value })
                });
                if (response.ok) {
                    const month = dropdown.closest('.accordion-trigger')?.dataset.month || 
                                 dropdown.closest('.group')?.querySelector('.accordion-trigger').dataset.month;
                    if(loadedData[month]) {
                        const item = loadedData[month].find(i => i.id == id);
                        if(item) item.keterangan = value;
                    }
                }
            } finally {
                dropdown.disabled = false;
            }
        }
    });
    // Initialize filters
    triggerGlobalFilter();
});
</script>

@endsection
