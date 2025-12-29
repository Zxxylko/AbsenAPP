<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Absensi UT</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'ut': {
                            'blue': '#004085',
                            'blue-light': '#0056b3',
                            'yellow': '#FFC61E',
                            'yellow-hover': '#e0ac1a',
                            'gray': {
                                50: '#F8FAFC',
                                100: '#F1F5F9',
                                200: '#E2E8F0',
                                300: '#CBD5E1',
                                400: '#94A3B8',
                                500: '#64748B',
                                600: '#475569',
                                700: '#334155',
                                800: '#1E293B',
                                900: '#0F172A',
                            }
                        }
                    },
                    fontFamily: {
                        sans: ['Instrument Sans', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s ease-out forwards',
                        'slide-up': 'slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .login-bg {
            background-color: #0F172A;
            background-image: 
                radial-gradient(at 0% 0%, rgba(0, 64, 133, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(255, 198, 30, 0.1) 0px, transparent 50%);
            position: relative;
            overflow: hidden;
        }
        .login-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2394a3b8' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2v-4h4v-2h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2v-4h4v-2H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="min-h-full font-sans login-bg flex items-center justify-center p-4 md:p-6">

    <div class="w-full max-w-md relative">
        <!-- Floating Blobs -->
        <div class="absolute -top-16 -left-16 w-48 h-48 bg-ut-blue/20 rounded-full blur-[80px] animate-pulse-slow"></div>
        <div class="absolute -bottom-16 -right-16 w-48 h-48 bg-ut-yellow/10 rounded-full blur-[80px] animate-pulse-slow"></div>

        <div class="relative z-10 space-y-6 md:space-y-8">
            {{-- Header --}}
            <div class="text-center space-y-3 animate-fade-in">
                <div class="inline-flex p-3 bg-white/5 rounded-2xl border border-white/10 shadow-xl backdrop-blur-md mb-2 transform hover:scale-105 transition-transform duration-500">
                    <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl bg-ut-blue flex items-center justify-center text-white font-black text-2xl md:text-3xl shadow-[0_0_30px_rgba(0,64,133,0.5)] border-2 border-ut-yellow/30">
                        UT
                    </div>
                </div>
                <div class="space-y-1">
                    <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">
                        Sistem <span class="text-ut-yellow">Absensi</span>
                    </h1>
                    <p class="text-ut-gray-400 font-bold uppercase tracking-widest text-[9px] md:text-[10px]">Tenaga Harian Lepas • Universitas Terbuka</p>
                </div>
            </div>

            {{-- Form Card --}}
            <div class="glass-card p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-[0_30px_60px_-15px_rgba(0,0,0,0.5)] animate-slide-up relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-0.5 bg-gradient-to-l from-ut-yellow to-transparent"></div>
                
                {{-- Messages --}}
                @if ($errors->any())
                    <div class="rounded-xl bg-rose-500/10 p-4 mb-6 border border-rose-500/20 backdrop-blur-md animate-fade-in text-left">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-rose-500 rounded-lg flex items-center justify-center shadow-lg shadow-rose-500/20">
                                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="space-y-0.5">
                                <p class="text-xs text-rose-200 font-black uppercase tracking-wider">Kesalahan Login</p>
                                <div class="text-[10px] text-rose-300/80 font-medium">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="rounded-xl bg-emerald-500/10 p-4 mb-6 border border-emerald-500/20 backdrop-blur-md animate-fade-in text-left">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="space-y-0.5">
                                <p class="text-xs text-emerald-200 font-black uppercase tracking-wider">Berhasil</p>
                                <p class="text-[10px] text-emerald-300/80 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-5 text-left" method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="space-y-2">
                        <label for="username" class="text-[9px] font-black text-ut-gray-400 uppercase tracking-widest ml-4">Username</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-ut-gray-500 group-focus-within:text-ut-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input id="username" name="username" type="text" autocomplete="username" required
                                class="block w-full pl-11 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm font-bold placeholder-ut-gray-600 focus:outline-none focus:ring-2 focus:ring-ut-blue/30 focus:border-ut-blue focus:bg-white transition-all focus:text-ut-gray-900"
                                placeholder="Masukkan username Anda">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-[9px] font-black text-ut-gray-400 uppercase tracking-widest ml-4">Kata Sandi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-ut-gray-500 group-focus-within:text-ut-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="block w-full pl-11 pr-4 py-3.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm font-bold placeholder-ut-gray-600 focus:outline-none focus:ring-2 focus:ring-ut-blue/30 focus:border-ut-blue focus:bg-white transition-all focus:text-ut-gray-900"
                                placeholder="••••••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded-md bg-white/5 border-white/10 text-ut-blue focus:ring-ut-blue transition-all cursor-pointer">
                            <span class="text-[10px] font-bold text-ut-gray-400 group-hover:text-ut-gray-200 transition-colors uppercase tracking-wider">Ingat saya</span>
                        </label>
                        <a href="#" class="text-[9px] font-black text-ut-blue uppercase tracking-wider hover:text-ut-yellow transition-colors">Lupa sandi?</a>
                    </div>

                    <button type="submit"
                        class="relative w-full overflow-hidden group/btn bg-ut-blue py-4 px-4 rounded-xl text-sm font-black text-white shadow-[0_15px_30px_-5px_rgba(0,64,133,0.5)] hover:shadow-[0_20px_40px_-5px_rgba(0,64,133,0.6)] transition-all transform hover:-translate-y-0.5 active:scale-[0.98]">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-1000"></div>
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Masuk ke Dashboard
                            <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </span>
                    </button>
                </form>

                <div class="mt-8 text-center pt-6 border-t border-white/5">
                    <p class="text-[10px] font-bold text-ut-gray-500 uppercase tracking-wider">
                        Belum memiliki akun?
                        <a href="{{ route('register') }}" class="text-ut-yellow hover:text-white font-black transition-colors ml-1 underline underline-offset-2 decoration-ut-yellow/30">Daftar sekarang</a>
                    </p>
                </div>
            </div>

            {{-- Footer Text --}}
            <div class="text-center space-y-2 animate-fade-in delay-500 opacity-50">
                <div class="flex items-center justify-center gap-2">
                    <div class="h-px w-8 bg-ut-gray-700"></div>
                    <p class="text-[8px] font-black text-ut-gray-500 uppercase tracking-widest">Secure Portal</p>
                    <div class="h-px w-8 bg-ut-gray-700"></div>
                </div>
                <p class="text-[8px] text-ut-gray-600 font-medium">© {{ date('Y') }} Universitas Terbuka</p>
            </div>
        </div>
    </div>

</body>
</html>
