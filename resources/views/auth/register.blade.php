<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | Sistem Absensi UT</title>

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
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                        'slide-up': 'slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
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

<body class="min-h-full font-sans login-bg flex items-center justify-center p-3">

    <div class="w-full max-w-sm relative">
        <!-- Floating Blobs -->
        <div class="absolute -top-12 -left-12 w-32 h-32 bg-ut-blue/20 rounded-full blur-[60px] animate-pulse-slow"></div>
        <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-ut-yellow/10 rounded-full blur-[60px] animate-pulse-slow"></div>

        <div class="relative z-10 space-y-4">
            {{-- Header --}}
            <div class="text-center space-y-2 animate-fade-in">
                <div class="inline-flex p-2 bg-white/5 rounded-xl border border-white/10 shadow-lg backdrop-blur-md transform hover:scale-105 transition-transform duration-500">
                    <div class="w-10 h-10 rounded-lg bg-ut-blue flex items-center justify-center text-white font-black text-xl shadow-[0_0_20px_rgba(0,64,133,0.5)] border border-ut-yellow/30">
                        UT
                    </div>
                </div>
                <div class="space-y-0.5">
                    <h1 class="text-xl font-black text-white tracking-tight">
                        Daftar <span class="text-ut-yellow">Akun</span>
                    </h1>
                    <p class="text-ut-gray-400 font-bold uppercase tracking-wider text-[8px]">Buat akun dengan kode undangan</p>
                </div>
            </div>

            {{-- Form Card --}}
            <div class="glass-card p-5 rounded-2xl shadow-[0_20px_40px_-10px_rgba(0,0,0,0.5)] animate-slide-up relative overflow-hidden">
                <div class="absolute top-0 right-0 w-16 h-0.5 bg-gradient-to-l from-ut-yellow to-transparent"></div>
                
                {{-- Messages --}}
                @if ($errors->any())
                    <div class="rounded-lg bg-rose-500/10 p-3 mb-4 border border-rose-500/20 animate-fade-in text-left">
                        <div class="flex gap-2">
                            <div class="flex-shrink-0 w-6 h-6 bg-rose-500 rounded flex items-center justify-center">
                                <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-rose-200 font-black uppercase">Gagal</p>
                                <div class="text-[9px] text-rose-300/80 font-medium">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="rounded-lg bg-emerald-500/10 p-3 mb-4 border border-emerald-500/20 animate-fade-in text-left">
                        <div class="flex gap-2">
                            <div class="flex-shrink-0 w-6 h-6 bg-emerald-500 rounded flex items-center justify-center">
                                <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-emerald-200 font-black uppercase">Berhasil</p>
                                <p class="text-[9px] text-emerald-300/80 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-3 text-left" method="POST" action="{{ route('register.post') }}">
                    @csrf

                    {{-- Username --}}
                    <div class="space-y-1">
                        <label for="username" class="text-[8px] font-black text-ut-gray-400 uppercase tracking-wider ml-3">Username</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-3.5 w-3.5 text-ut-gray-500 group-focus-within:text-ut-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input id="username" name="username" type="text" value="{{ old('username') }}" required
                                class="block w-full pl-9 pr-3 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white text-xs font-bold placeholder-ut-gray-600 focus:outline-none focus:ring-2 focus:ring-ut-blue/30 focus:border-ut-blue focus:bg-white transition-all focus:text-ut-gray-900"
                                placeholder="Buat username unik">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="space-y-1">
                        <label for="password" class="text-[8px] font-black text-ut-gray-400 uppercase tracking-wider ml-3">Kata Sandi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-3.5 w-3.5 text-ut-gray-500 group-focus-within:text-ut-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="block w-full pl-9 pr-3 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white text-xs font-bold placeholder-ut-gray-600 focus:outline-none focus:ring-2 focus:ring-ut-blue/30 focus:border-ut-blue focus:bg-white transition-all focus:text-ut-gray-900"
                                placeholder="Minimal 4 karakter">
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div class="space-y-1">
                        <label for="password_confirmation" class="text-[8px] font-black text-ut-gray-400 uppercase tracking-wider ml-3">Konfirmasi Sandi</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-3.5 w-3.5 text-ut-gray-500 group-focus-within:text-ut-blue transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="block w-full pl-9 pr-3 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white text-xs font-bold placeholder-ut-gray-600 focus:outline-none focus:ring-2 focus:ring-ut-blue/30 focus:border-ut-blue focus:bg-white transition-all focus:text-ut-gray-900"
                                placeholder="Ulangi kata sandi">
                        </div>
                    </div>

                    {{-- Invite Code --}}
                    <div class="space-y-1">
                        <label for="invite_code" class="text-[8px] font-black text-ut-gray-400 uppercase tracking-wider ml-3">Kode Undangan</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-3.5 w-3.5 text-ut-gray-500 group-focus-within:text-ut-yellow transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <input id="invite_code" name="invite_code" type="text" value="{{ old('invite_code') }}" required
                                class="block w-full pl-9 pr-3 py-2.5 bg-white/5 border border-ut-yellow/20 rounded-lg text-white text-xs font-bold placeholder-ut-gray-600 focus:outline-none focus:ring-2 focus:ring-ut-yellow/30 focus:border-ut-yellow focus:bg-white transition-all focus:text-ut-gray-900"
                                placeholder="Paste kode undangan">
                        </div>
                    </div>

                    <button type="submit"
                        class="relative w-full overflow-hidden group/btn bg-ut-blue py-3 px-4 rounded-lg text-xs font-black text-white shadow-[0_10px_20px_-5px_rgba(0,64,133,0.5)] hover:shadow-[0_15px_25px_-5px_rgba(0,64,133,0.6)] transition-all transform hover:-translate-y-0.5 active:scale-[0.98] mt-1">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700"></div>
                        <span class="relative z-10 flex items-center justify-center gap-1.5">
                            Buat Akun
                            <svg class="w-3.5 h-3.5 group-hover/btn:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                        </span>
                    </button>
                </form>

                <div class="mt-4 text-center pt-3 border-t border-white/5">
                    <p class="text-[9px] font-bold text-ut-gray-500 uppercase tracking-wider">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-ut-yellow hover:text-white font-black transition-colors ml-1 underline underline-offset-2 decoration-ut-yellow/30">Masuk</a>
                    </p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="text-center opacity-40">
                <p class="text-[7px] text-ut-gray-500 font-medium">© {{ date('Y') }} Universitas Terbuka</p>
            </div>
        </div>
    </div>

</body>
</html>