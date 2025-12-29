@extends('layouts.main')

@section('content')
<div class="space-y-10 animate-fade-in px-4 md:px-0">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
        <div class="space-y-2">
            <h1 class="text-4xl font-black text-ut-gray-900 dark:text-white tracking-tight flex items-center gap-4">
                <div class="p-3 bg-ut-blue dark:bg-ut-yellow rounded-2xl text-white dark:text-ut-blue shadow-xl shadow-ut-blue/10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <span>Kode Undangan</span>
            </h1>
            <p class="text-ut-gray-500 dark:text-ut-gray-400 font-medium ml-1">Generate dan kelola kode akses pendaftaran THL baru.</p>
        </div>

        <div class="flex items-center gap-4 w-full md:w-auto">
            <button type="button" id="deleteSelectedBtn"
                class="hidden px-6 py-4 bg-rose-500 hover:bg-rose-600 text-white rounded-2xl text-sm font-black shadow-2xl shadow-rose-500/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                Hapus Terpilih
            </button>
        </div>
    </div>

    {{-- GENERATE CARD --}}
    <div class="bg-white/50 dark:bg-ut-gray-800/50 backdrop-blur-xl p-8 md:p-10 rounded-[2.5rem] border border-ut-gray-100 dark:border-ut-gray-700 shadow-xl overflow-hidden relative">
        <div class="absolute -top-10 -left-10 w-40 h-40 bg-ut-blue/5 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
            <div class="space-y-3">
                <h2 class="text-2xl font-black text-ut-gray-900 dark:text-white tracking-tight uppercase tracking-wider">Generate Kode Otomatis</h2>
                <p class="text-ut-gray-500 dark:text-ut-gray-400 font-medium">Pilih jumlah kuota yang dibutuhkan untuk grup pendaftaran baru.</p>
            </div>

            <form action="{{ route('invites.store') }}" method="POST" id="generateForm" class="flex flex-col sm:flex-row items-center gap-6">
                @csrf
                <div class="relative w-full sm:w-48 group">
                    <select name="jumlah" id="jumlah"
                        class="w-full bg-white dark:bg-ut-gray-700 border border-ut-gray-100 dark:border-ut-gray-600 rounded-2xl px-6 py-4 text-sm font-black focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue dark:text-white transition-all shadow-sm appearance-none cursor-pointer">
                        <option value="1">1 KODE</option>
                        <option value="3">3 KODE</option>
                        <option value="5">5 KODE</option>
                        <option value="10">10 KODE</option>
                        <option value="20">20 KODE</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-ut-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>

                {{-- Role Selection --}}
                <div class="relative w-full sm:w-48 group">
                    <select name="role" id="role"
                        class="w-full bg-white dark:bg-ut-gray-700 border border-ut-gray-100 dark:border-ut-gray-600 rounded-2xl px-6 py-4 text-sm font-black focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue dark:text-white transition-all shadow-sm appearance-none cursor-pointer">
                        <option value="staff">STAFF</option>
                        <option value="admin">ADMIN</option>
                        <option value="viewer">VIEWER</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-ut-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </div>

                <div class="flex gap-4 w-full sm:w-auto">
                    <button type="button" id="generateBtn"
                        class="flex-grow sm:flex-grow-0 px-8 py-4 bg-ut-blue hover:bg-ut-blue-light text-white rounded-2xl text-sm font-black shadow-2xl shadow-ut-blue/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        GENERATE
                    </button>
                    
                    <button type="submit" id="submitBtn"
                        class="hidden flex-grow sm:flex-grow-0 px-8 py-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl text-sm font-black shadow-2xl shadow-emerald-500/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 animate-slide-up">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        SIMPAN
                    </button>
                </div>
            </form>
        </div>

        <div id="preview" class="mt-10 hidden animate-fade-in">
            <div class="h-px w-full bg-ut-gray-100 dark:bg-ut-gray-700 mb-8"></div>
            <h3 class="font-black text-ut-gray-400 text-[10px] uppercase tracking-[0.3em] mb-6 ml-4">Preview Kode Baru</h3>
            <ul id="kodeList" class="grid grid-cols-2 md:grid-cols-5 gap-4"></ul>
        </div>
    </div>

    {{-- TABLE CARD --}}
    <div class="bg-white dark:bg-ut-gray-800 rounded-[2.5rem] shadow-2xl border border-ut-gray-100 dark:border-ut-gray-700 overflow-hidden group">
        <form id="deleteSelectedForm" action="{{ route('invites.bulkDelete') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-ut-gray-50 dark:bg-white/5 text-ut-gray-400 text-[10px] items-center uppercase font-black tracking-widest border-b border-ut-gray-50 dark:border-white/5">
                        <th class="py-6 px-10 text-left">
                            <label class="relative flex items-center cursor-pointer">
                                <input type="checkbox" id="selectAll" class="w-5 h-5 rounded-lg bg-ut-gray-100 dark:bg-white/10 border-transparent text-ut-blue focus:ring-ut-blue transition-all">
                            </label>
                        </th>
                        <th class="py-6 px-10 text-left">Kode Undangan</th>
                        <th class="py-6 px-10 text-left">Role</th>
                        <th class="py-6 px-10 text-left">Status Kuota</th>
                        <th class="py-6 px-10 text-left">Waktu Pembuatan</th>
                        <th class="py-6 px-10 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody id="inviteTable" class="divide-y divide-ut-gray-50 dark:divide-white/5">
                    @forelse($invites as $index => $invite)
                        <tr class="hover:bg-ut-gray-50 dark:hover:bg-white/5 transition-all group/row">
                            <td class="py-6 px-10">
                                <input type="checkbox" name="selected_ids[]" value="{{ $invite->id }}"
                                    form="deleteSelectedForm" class="rowCheckbox w-5 h-5 rounded-lg bg-ut-gray-100 dark:bg-white/10 border-transparent text-ut-blue focus:ring-ut-blue transition-all">
                            </td>
                            <td class="py-6 px-10">
                                <span class="font-black text-ut-blue dark:text-ut-yellow text-lg tracking-widest font-mono group-hover/row:scale-110 transition-transform inline-block">{{ $invite->code }}</span>
                            </td>
                            <td class="py-6 px-10">
                                @if($invite->role === 'admin')
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-purple-500/10 text-purple-500 text-[10px] font-black uppercase tracking-widest">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                        Admin
                                    </span>
                                @elseif($invite->role === 'viewer')
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 text-amber-500 text-[10px] font-black uppercase tracking-widest">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        Viewer
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-ut-blue/10 text-ut-blue text-[10px] font-black uppercase tracking-widest">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                        Staff
                                    </span>
                                @endif
                            </td>
                            <td class="py-6 px-10">
                                @if($invite->is_used)
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-500/10 text-rose-500 text-[10px] font-black uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                        Sudah Digunakan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Tersedia (Aktif)
                                    </span>
                                @endif
                            </td>
                            <td class="py-6 px-10 text-ut-gray-500 dark:text-ut-gray-400 font-bold text-xs uppercase tracking-wider">
                                {{ $invite->created_at ? $invite->created_at->translatedFormat('d F Y • H:i') : '-' }}
                            </td>
                            <td class="py-6 px-10 text-center">
                                <form action="{{ route('invites.destroy', $invite->id) }}" method="POST" class="inline deleteForm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="p-3 text-rose-500 hover:bg-rose-500/10 rounded-xl transition-all singleDeleteBtn">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-24 text-center">
                                <div class="flex flex-col items-center gap-4 opacity-30">
                                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
                                    <p class="text-xl font-black text-ut-gray-900 dark:text-white tracking-tight">Belum Ada Kode Undangan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SweetAlert2 Custom Styling for Premium Look --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const isDark = document.documentElement.classList.contains('dark');
    const swalConfig = {
        customClass: {
            popup: 'premium-swal-popup',
            title: 'premium-swal-title',
            confirmButton: 'premium-swal-confirm',
            cancelButton: 'premium-swal-cancel'
        },
        buttonsStyling: false,
        background: isDark ? '#1e293b' : '#ffffff',
        color: isDark ? '#f1f5f9' : '#1e293b'
    };
</script>

<style>
    .premium-swal-popup { border-radius: 2rem !important; padding: 3rem !important; border: 1px solid rgba(255,255,255,0.1) !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5) !important; }
    .premium-swal-confirm { background: #004085 !important; color: white !important; font-weight: 800 !important; font-size: 0.875rem !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; padding: 1rem 2rem !important; border-radius: 1rem !important; margin: 0.5rem !important; transition: all 0.3s !important; }
    .premium-swal-confirm:hover { background: #0056b3 !important; transform: translateY(-2px) !important; box-shadow: 0 10px 20px -5px rgba(0,64,133,0.3) !important; }
    .premium-swal-cancel { background: rgba(148,163,184,0.1) !important; color: #94A3B8 !important; font-weight: 800 !important; font-size: 0.875rem !important; text-transform: uppercase !important; letter-spacing: 0.1em !important; padding: 1rem 2rem !important; border-radius: 1rem !important; margin: 0.5rem !important; }
    .premium-swal-cancel:hover { background: rgba(148,163,184,0.2) !important; color: #475569 !important; }
</style>

<script>
    function generateCode(length = 5) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return Array.from({ length }, () => chars.charAt(Math.floor(Math.random() * chars.length))).join('');
    }

    document.getElementById('generateBtn').addEventListener('click', function() {
        const jumlah = parseInt(document.getElementById('jumlah').value);
        const kodeList = document.getElementById('kodeList');
        const preview = document.getElementById('preview');
        const submitBtn = document.getElementById('submitBtn');

        kodeList.innerHTML = '';
        let hiddenInputs = '';

        for (let i = 0; i < jumlah; i++) {
            const code = generateCode();
            const li = document.createElement('li');
            li.className = 'p-4 bg-ut-blue/5 dark:bg-white/5 border border-ut-blue/10 dark:border-white/10 rounded-2xl flex items-center justify-center font-mono font-black text-ut-blue dark:text-ut-yellow tracking-[0.2em] transform hover:scale-105 transition-all duration-300';
            li.textContent = code;
            kodeList.appendChild(li);
            hiddenInputs += `<input type="hidden" name="codes[]" value="${code}">`;
        }

        document.querySelectorAll('input[name="codes[]"]').forEach(e => e.remove());
        document.getElementById('generateForm').insertAdjacentHTML('beforeend', hiddenInputs);

        preview.classList.remove('hidden');
        submitBtn.classList.remove('hidden');

        Swal.fire({
            ...swalConfig,
            icon: 'info',
            title: 'KODE BERHASIL DI-GENERATE',
            text: `${jumlah} kode akses pendaftaran sementara telah disiapkan. Klik simpan untuk mengaktifkan kode tersebut.`,
        });
    });

    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    const deleteForm = document.getElementById('deleteSelectedForm');

    selectAll.addEventListener('change', function() {
        rowCheckboxes.forEach(cb => cb.checked = selectAll.checked);
        toggleDeleteButton();
    });

    rowCheckboxes.forEach(cb => cb.addEventListener('change', toggleDeleteButton));

    function toggleDeleteButton() {
        const anyChecked = Array.from(rowCheckboxes).some(cb => cb.checked);
        deleteBtn.classList.toggle('hidden', !anyChecked);
        if (anyChecked) deleteBtn.classList.add('animate-slide-up');
    }

    deleteBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const checkedBoxes = Array.from(document.querySelectorAll('.rowCheckbox')).filter(cb => cb.checked);
        
        Swal.fire({
            ...swalConfig,
            title: `Hapus ${checkedBoxes.length} Kode?`,
            text: "Data yang dihapus tidak dapat dipulihkan kembali dari basis data.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'YA, HAPUS TERPILIH',
            cancelButtonText: 'BATAL'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteBtn.disabled = true;
                deleteBtn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> MEMPROSES...`;
                setTimeout(() => deleteForm.submit(), 800);
            }
        });
    });

    document.querySelectorAll('.singleDeleteBtn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = btn.closest('.deleteForm');
            Swal.fire({
                ...swalConfig,
                title: 'Hapus Kode Akses?',
                text: "Keanggotaan THL yang belum mendaftar dengan kode ini tidak akan dapat melanjutkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'YA, HAPUS',
                cancelButtonText: 'BATAL'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
                    setTimeout(() => form.submit(), 600);
                }
            });
        });
    });
</script>
@endsection
