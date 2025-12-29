@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto space-y-12 animate-fade-in px-4">

    {{-- HEADER --}}
    <div class="space-y-2">
        <h1 class="text-4xl font-black text-ut-gray-900 dark:text-white tracking-tight flex items-center gap-4">
            <div class="p-3 bg-ut-blue dark:bg-ut-yellow rounded-2xl text-white dark:text-ut-blue shadow-xl shadow-ut-blue/10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <span>Pengaturan Akun</span>
        </h1>
        <p class="text-ut-gray-500 dark:text-ut-gray-400 font-medium ml-1">Konfigurasi profil pribadi, keamanan, dan preferensi antarmuka.</p>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-6 rounded-3xl font-black text-sm uppercase tracking-widest flex items-center gap-4 animate-slide-up">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {!! session('success') !!}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/20 p-6 rounded-3xl animate-slide-up">
            <ul class="space-y-1 text-sm text-rose-500 font-bold uppercase tracking-wider">
                @foreach($errors->all() as $e)
                    <li class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        {{ $e }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        {{-- SIDEBAR NAVIGATION --}}
        <div class="space-y-4">
            <button class="w-full flex items-center justify-between p-5 bg-ut-blue dark:bg-ut-yellow text-white dark:text-ut-blue rounded-2xl shadow-xl shadow-ut-blue/20 font-black text-xs uppercase tracking-widest transition-all">
                Profil & Keamanan
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            <button class="w-full flex items-center justify-between p-5 hover:bg-ut-gray-50 dark:hover:bg-white/5 text-ut-gray-500 dark:text-ut-gray-400 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                Tampilan (Theming)
                <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
            <button class="w-full flex items-center justify-between p-5 hover:bg-ut-gray-50 dark:hover:bg-white/5 text-ut-gray-500 dark:text-ut-gray-400 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                Notifikasi
                <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
        </div>

        {{-- MAIN SETTINGS AREA --}}
        <div class="md:col-span-2 space-y-10">
            
            {{-- USERNAME SECTION --}}
            <div class="bg-white dark:bg-ut-gray-800 p-8 md:p-10 rounded-[2.5rem] border border-ut-gray-100 dark:border-ut-gray-700 shadow-xl group">
                <div class="flex items-center gap-4 mb-8">
                    <div class="p-3 bg-ut-blue/5 dark:bg-ut-yellow/5 rounded-xl text-ut-blue dark:text-ut-yellow">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-ut-gray-900 dark:text-white tracking-tight uppercase tracking-wider">Identitas Akun</h3>
                        <p class="text-xs text-ut-gray-400 font-bold uppercase tracking-widest">Gunakan username yang mudah diingat.</p>
                    </div>
                </div>

                <form action="{{ route('settings.updateUsername') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-ut-gray-400 uppercase tracking-[0.2em] ml-6">Username Saat Ini</label>
                        <input type="text" name="username" value="{{ Auth::user()->username }}" 
                            class="block w-full px-8 py-5 bg-ut-gray-50 dark:bg-ut-gray-900/50 border border-ut-gray-100 dark:border-ut-gray-600 rounded-2xl text-ut-gray-900 dark:text-white text-sm font-black focus:outline-none focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue transition-all" required>
                    </div>
                    <button class="px-8 py-4 bg-ut-blue dark:bg-ut-blue-light hover:bg-ut-blue-light text-white rounded-2xl text-xs font-black shadow-2xl shadow-ut-blue/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                        SIMPAN PERUBAHAN
                    </button>
                </form>
            </div>

            {{-- PASSWORD SECTION --}}
            <div class="bg-white dark:bg-ut-gray-800 p-8 md:p-10 rounded-[2.5rem] border border-ut-gray-100 dark:border-ut-gray-700 shadow-xl">
                <div class="flex items-center gap-4 mb-8">
                    <div class="p-3 bg-ut-blue/5 dark:bg-ut-yellow/5 rounded-xl text-ut-blue dark:text-ut-yellow">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-ut-gray-900 dark:text-white tracking-tight uppercase tracking-wider">Kata Sandi</h3>
                        <p class="text-xs text-ut-gray-400 font-bold uppercase tracking-widest">Perbarui keamanan akun Anda secara berkala.</p>
                    </div>
                </div>

                <form action="{{ route('settings.updatePassword') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-ut-gray-400 uppercase tracking-[0.2em] ml-6">Kata Sandi Lama</label>
                        <input type="password" name="current_password" placeholder="••••••••••••" class="block w-full px-8 py-5 bg-ut-gray-50 dark:bg-ut-gray-900/50 border border-ut-gray-100 dark:border-ut-gray-600 rounded-2xl text-ut-gray-900 dark:text-white text-sm font-black focus:outline-none focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue transition-all" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-ut-gray-400 uppercase tracking-[0.2em] ml-6">Sandi Baru</label>
                            <input type="password" name="new_password" placeholder="••••••••••••" class="block w-full px-8 py-5 bg-ut-gray-50 dark:bg-ut-gray-900/50 border border-ut-gray-100 dark:border-ut-gray-600 rounded-2xl text-ut-gray-900 dark:text-white text-sm font-black focus:outline-none focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue transition-all" required>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[10px] font-black text-ut-gray-400 uppercase tracking-[0.2em] ml-6">Konfirmasi</label>
                            <input type="password" name="new_password_confirmation" placeholder="••••••••••••" class="block w-full px-8 py-5 bg-ut-gray-50 dark:bg-ut-gray-900/50 border border-ut-gray-100 dark:border-ut-gray-600 rounded-2xl text-ut-gray-900 dark:text-white text-sm font-black focus:outline-none focus:ring-4 focus:ring-ut-blue/10 focus:border-ut-blue transition-all" required>
                        </div>
                    </div>
                    <button class="px-8 py-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl text-xs font-black shadow-2xl shadow-emerald-500/30 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        PERPARUI KATA SANDI
                    </button>
                </form>
            </div>

            {{-- THEME SECTION --}}
            <div class="bg-white dark:bg-ut-gray-800 p-8 md:p-10 rounded-[2.5rem] border border-ut-gray-100 dark:border-ut-gray-700 shadow-xl overflow-hidden relative group/theme">
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-ut-blue/5 dark:bg-ut-yellow/5 rounded-full blur-3xl group-hover/theme:scale-150 transition-transform duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-ut-blue/5 dark:bg-ut-yellow/5 rounded-xl text-ut-blue dark:text-ut-yellow">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-ut-gray-900 dark:text-white tracking-tight uppercase tracking-wider">Mode Tampilan</h3>
                            <p class="text-xs text-ut-gray-400 font-bold uppercase tracking-widest">Sesuaikan kenyamanan visual Anda.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 p-2 bg-ut-gray-50 dark:bg-ut-gray-900 rounded-[2rem] border border-ut-gray-100 dark:border-ut-gray-700">
                        <button onclick="setTheme('light')" id="lightThemeBtn" class="flex items-center gap-3 px-6 py-3 rounded-full font-black text-[10px] uppercase tracking-widest transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            Terang
                        </button>
                        <button onclick="setTheme('dark')" id="darkThemeBtn" class="flex items-center gap-3 px-6 py-3 rounded-full font-black text-[10px] uppercase tracking-widest transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                            Gelap
                        </button>
                    </div>
                </div>
            </div>

            {{-- PREFERENCES SECTION --}}
            <div class="bg-white dark:bg-ut-gray-800 p-8 md:p-10 rounded-[2.5rem] border border-ut-gray-100 dark:border-ut-gray-700 shadow-xl">
                <div class="flex items-center gap-4 mb-8">
                    <div class="p-3 bg-ut-blue/5 dark:bg-ut-yellow/5 rounded-xl text-ut-blue dark:text-ut-yellow">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-ut-gray-900 dark:text-white tracking-tight uppercase tracking-wider">Pemberitahuan</h3>
                        <p class="text-xs text-ut-gray-400 font-bold uppercase tracking-widest">Atur media pengiriman notifikasi sistem.</p>
                    </div>
                </div>

                <div class="space-y-4 px-2">
                    <label class="flex items-center justify-between p-6 bg-ut-gray-50 dark:bg-ut-gray-900/50 rounded-2xl border border-ut-gray-100 dark:border-ut-gray-700 cursor-pointer group hover:border-ut-blue/30 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white dark:bg-ut-gray-800 rounded-xl flex items-center justify-center text-ut-blue dark:text-ut-yellow shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <span class="text-sm font-black text-ut-gray-700 dark:text-ut-gray-300 uppercase tracking-widest">Notifikasi Email</span>
                        </div>
                        <input type="checkbox" checked class="w-6 h-6 rounded-lg bg-ut-gray-200 dark:bg-ut-gray-700 border-transparent text-ut-blue focus:ring-ut-blue transition-all">
                    </label>

                    <label class="flex items-center justify-between p-6 bg-ut-gray-50 dark:bg-ut-gray-900/50 rounded-2xl border border-ut-gray-100 dark:border-ut-gray-700 cursor-pointer group hover:border-ut-blue/30 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-white dark:bg-ut-gray-800 rounded-xl flex items-center justify-center text-ut-blue dark:text-ut-yellow shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                            <span class="text-sm font-black text-ut-gray-700 dark:text-ut-gray-300 uppercase tracking-widest">Notifikasi WhatsApp</span>
                        </div>
                        <input type="checkbox" class="w-6 h-6 rounded-lg bg-ut-gray-200 dark:bg-ut-gray-700 border-transparent text-ut-blue focus:ring-ut-blue transition-all">
                    </label>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function setTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
        }
        updateThemeUI();
    }

    function updateThemeUI() {
        const isDark = document.documentElement.classList.contains('dark');
        const lightBtn = document.getElementById('lightThemeBtn');
        const darkBtn = document.getElementById('darkThemeBtn');

        if (isDark) {
            darkBtn.classList.add('bg-white', 'dark:bg-ut-gray-800', 'text-ut-gray-900', 'dark:text-white', 'shadow-xl');
            darkBtn.classList.remove('text-ut-gray-400');
            lightBtn.classList.add('text-ut-gray-400');
            lightBtn.classList.remove('bg-white', 'dark:bg-ut-gray-800', 'text-ut-gray-900', 'dark:text-white', 'shadow-xl');
        } else {
            lightBtn.classList.add('bg-white', 'dark:bg-ut-gray-800', 'text-ut-gray-900', 'dark:text-white', 'shadow-xl');
            lightBtn.classList.remove('text-ut-gray-400');
            darkBtn.classList.add('text-ut-gray-400');
            darkBtn.classList.remove('bg-white', 'dark:bg-ut-gray-800', 'text-ut-gray-900', 'dark:text-white', 'shadow-xl');
        }
    }

    document.addEventListener('DOMContentLoaded', updateThemeUI);
</script>
@endsection
