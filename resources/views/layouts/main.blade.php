<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - Sistem Absensi UT</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/darkmode.css') }}">
</head>

<body class="min-h-screen flex flex-col font-sans bg-ut-gray-50 dark:bg-ut-gray-900 text-ut-gray-900 dark:text-ut-gray-100 transition-colors duration-500 overflow-x-hidden">

    <!-- Header / Navbar -->
    <header class="fixed top-0 left-0 w-full z-50 glass-header text-white transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo / Brand -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-ut-blue font-black text-xl shadow-lg ring-2 ring-ut-yellow group-hover:rotate-6 transition-all duration-300">
                            UT
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-black tracking-tight text-white group-hover:translate-x-1 transition-transform">Sistem Absensi</span>
                            <span class="text-[10px] uppercase tracking-[0.2em] font-bold text-ut-yellow/80">Universitas Terbuka Gedung Lppmp</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-1 items-center">
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 rounded-xl text-sm font-black tracking-wide transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-white/10 text-ut-yellow shadow-inner' : 'text-gray-100 hover:bg-white/5 hover:text-ut-yellow' }}">
                        DASHBOARD
                    </a>

                    <!-- Dropdown Report -->
                    <div class="relative group">
                        <button class="px-4 py-2 rounded-xl flex items-center gap-2 text-sm font-black tracking-wide text-gray-100 hover:bg-white/5 hover:text-ut-yellow transition-all duration-300">
                            REPORTING
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="absolute left-0 mt-2 w-60 bg-white dark:bg-ut-gray-800 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] py-3 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform scale-95 group-hover:scale-100 origin-top-left z-50 border border-ut-gray-100 dark:border-ut-gray-700">
                            <a href="{{ route('Athl.report') }}" class="flex items-center gap-4 px-5 py-4 text-sm font-bold text-ut-gray-700 dark:text-ut-gray-300 hover:bg-ut-blue/5 hover:text-ut-blue dark:hover:bg-ut-blue/10 dark:hover:text-ut-yellow transition-all">
                                <div class="p-2.5 bg-blue-100 dark:bg-blue-900/30 rounded-2xl text-ut-blue dark:text-blue-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </div>
                                THL Report
                            </a>
                            <a href="{{ route('monthly.report') }}" class="flex items-center gap-4 px-5 py-4 text-sm font-bold text-ut-gray-700 dark:text-ut-gray-300 hover:bg-ut-blue/5 hover:text-ut-blue dark:hover:bg-ut-blue/10 dark:hover:text-ut-yellow transition-all">
                                <div class="p-2.5 bg-purple-100 dark:bg-purple-900/30 rounded-2xl text-purple-600 dark:text-purple-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                Monthly Report
                            </a>
                        </div>
                    </div>

                    @if(!auth()->user()->isViewer())
                    <a href="{{ route('THL.index') }}"
                        class="px-4 py-2 rounded-xl text-sm font-black tracking-wide transition-all duration-300 {{ request()->routeIs('THL.index') ? 'bg-white/10 text-ut-yellow shadow-inner' : 'text-gray-100 hover:bg-white/5 hover:text-ut-yellow' }}">
                        DATA THL
                    </a>
                    @endif

                    <!-- Dropdown Manajemen -->
                    <div class="relative group">
                        <button class="px-4 py-2 rounded-xl flex items-center gap-2 text-sm font-black tracking-wide text-gray-100 hover:bg-white/5 hover:text-ut-yellow transition-all duration-300">
                            MANAJEMEN
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="absolute right-0 mt-2 w-60 bg-white dark:bg-ut-gray-800 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] py-3 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform scale-95 group-hover:scale-100 origin-top-right z-50 border border-ut-gray-100 dark:border-ut-gray-700">
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('invites.index') }}" class="flex items-center gap-4 px-5 py-4 text-sm font-bold text-ut-gray-700 dark:text-ut-gray-300 hover:bg-ut-blue/5 hover:text-ut-blue dark:hover:bg-ut-blue/10 dark:hover:text-ut-yellow transition-all">
                                <div class="p-2.5 bg-yellow-100 dark:bg-yellow-900/30 rounded-2xl text-ut-yellow">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                </div>
                                Kode Undangan
                            </a>
                            @endif
                            <a href="{{ route('settings.index') }}" class="flex items-center gap-4 px-5 py-4 text-sm font-bold text-ut-gray-700 dark:text-ut-gray-300 hover:bg-ut-blue/5 hover:text-ut-blue dark:hover:bg-ut-blue/10 dark:hover:text-ut-yellow transition-all">
                                <div class="p-2.5 bg-gray-100 dark:bg-gray-900/30 rounded-2xl text-ut-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                Settings
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="mt-2 border-t border-ut-gray-100 dark:border-ut-gray-700">
                                @csrf
                                <button type="submit" class="w-full text-left px-5 py-4 text-sm font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all flex items-center gap-4">
                                    <div class="p-2.5 bg-rose-100 dark:bg-rose-900/30 rounded-2xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    </div>
                                    KELUAR SISTEM
                                </button>
                            </form>
                        </div>
                    </div>
                </nav>

                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn"
                    class="md:hidden p-3 rounded-2xl bg-white/10 hover:bg-white/20 focus:outline-none transition-all active:scale-90">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path class="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path class="close-icon hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobile-menu"
            class="hidden md:hidden bg-ut-blue/95 backdrop-blur-xl border-t border-white/10 absolute w-full left-0 shadow-2xl overflow-hidden rounded-b-[2rem]">
            <div class="px-4 pt-4 pb-8 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-base font-black text-white hover:bg-white/10 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard 
                </a>
                <a href="{{ route('Athl.report') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-base font-black text-gray-100 hover:bg-white/10 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    THL Report 
                </a>
                <a href="{{ route('monthly.report') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-base font-black text-gray-100 hover:bg-white/10 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Monthly Report 
                </a>
                @if(!auth()->user()->isViewer())
                <a href="{{ route('THL.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-base font-black text-gray-100 hover:bg-white/10 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Data THL 
                </a>
                @endif
                <div class="h-px bg-white/10 mx-6 my-2"></div>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('invites.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-base font-black text-gray-100 hover:bg-white/10 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                    Kode Undangan
                </a>
                @endif
                <a href="{{ route('settings.index') }}" class="flex items-center gap-4 px-6 py-4 rounded-2xl text-base font-black text-gray-100 hover:bg-white/10 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066-1.543.94-3.31-.826-2.37-2.37-1.724 1.724 0 00-1.065-2.572-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Settings 
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-4 px-6 py-4 rounded-2xl text-base font-black text-rose-300 hover:bg-rose-500/10 transition-all text-left">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout 
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow pt-28 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full animate-fade-in">
        @yield('content')
    </main>


    <!-- Footer -->
    <footer class="bg-white dark:bg-ut-gray-800/50 border-t border-ut-gray-100 dark:border-ut-gray-700 mt-auto">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-ut-blue flex items-center justify-center text-white font-black text-sm">UT</div>
                    <p class="text-sm font-bold text-ut-gray-500 dark:text-ut-gray-400">
                        &copy; {{ date('Y') }} <span class="text-ut-blue dark:text-ut-yellow">Universitas Terbuka</span>. All rights reserved.
                    </p>
                </div>
                <div class="flex items-center gap-6 text-xs font-black text-ut-gray-400 uppercase tracking-widest">
                    <a href="#" class="hover:text-ut-blue transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-ut-blue transition-colors">Terms of Service</a>
                    <a href="#" class="hover:text-ut-blue transition-colors">Help Center</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Global Scripts -->
    <script src="{{ asset('js/darkmode.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = mobileMenuBtn.querySelector('.menu-icon');
            const closeIcon = mobileMenuBtn.querySelector('.close-icon');

            mobileMenuBtn.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.contains('hidden');
                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    mobileMenu.classList.add('animate-slide-down');
                    document.body.style.overflow = 'hidden'; // Prevent background scroll
                } else {
                    mobileMenu.classList.add('hidden');
                    mobileMenu.classList.remove('animate-slide-down');
                    document.body.style.overflow = '';
                }
                menuIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });

            // Close menu on click outside
            window.addEventListener('click', (e) => {
                if (!mobileMenu.classList.contains('hidden') && !mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                    mobileMenuBtn.click();
                }
            });


            // Auto dark mode check
            if (localStorage.theme === "dark" || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add("dark");
            } else {
                document.documentElement.classList.remove("dark");
            }
        });
    </script>
</body>

</html>