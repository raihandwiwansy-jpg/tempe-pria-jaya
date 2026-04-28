<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Hub | Tempe Pria Jaya</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            letter-spacing: -0.02em;
        }

        .auth-canvas {
            background: radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.05) 0px, transparent 50%),
                        radial-gradient(at 100% 100%, rgba(245, 158, 11, 0.05) 0px, transparent 50%),
                        #F8FAFC;
        }

        .main-card {
            backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }

        .floating-icon {
            animation: floating 6s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translate(0,  0px); }
            50% { transform: translate(0, 20px); }
            100% { transform: translate(0, -0px); }
        }

        /* Styling Form Slot agar serasi dengan desain */
        .auth-form input {
            @apply w-full px-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-emerald-500/20 transition-all duration-300 text-sm font-semibold text-gray-700;
        }
        
        .auth-form label {
            @apply text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2 mb-2 block;
        }

        .auth-form button {
            @apply w-full py-4 bg-gray-900 hover:bg-emerald-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-[0.3em] transition-all duration-500 shadow-xl shadow-gray-200 hover:shadow-emerald-200 active:scale-95;
        }

        /* Tambahan fix untuk checkbox Laravel Breeze */
        .auth-form input[type="checkbox"] {
            @apply w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500;
        }
    </style>
</head>
<body class="auth-canvas min-h-screen flex items-center justify-center p-0 md:p-6 lg:p-8">

    {{-- Background Decorative Blobs --}}
    <div class="fixed inset-0 overflow-hidden -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-emerald-100 rounded-full blur-[120px] opacity-60"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-amber-100 rounded-full blur-[120px] opacity-60"></div>
    </div>

    <div class="w-full max-w-[1100px] main-card bg-white/70 rounded-none md:rounded-[3.5rem] shadow-[0_40px_100px_-20px_rgba(0,0,0,0.06)] overflow-hidden flex flex-col lg:flex-row min-h-screen md:min-h-[700px]">
        
        {{-- Left Side: Branding (Hidden on Mobile) --}}
        <div class="hidden lg:flex flex-col justify-between w-1/2 bg-emerald-700 p-16 text-white relative overflow-hidden">
            {{-- Abstract Pattern --}}
            <div class="absolute inset-0 opacity-10 pointer-events-none">
                <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
            </div>

            <div class="absolute top-0 right-0 opacity-10 floating-icon">
                <span class="material-symbols-rounded text-[500px] leading-none">spa</span>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-20 group cursor-default">
                    <div class="w-12 h-12 bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl flex items-center justify-center group-hover:bg-white group-hover:text-emerald-700 transition-all duration-500">
                        <span class="material-symbols-rounded">eco</span>
                    </div>
                    <span class="font-black text-2xl tracking-tighter italic text-white uppercase">PRIA JAYA <span class="text-emerald-400">HUB</span></span>
                </div>

                <h2 class="text-6xl font-black leading-[1] tracking-tighter mb-8 italic">
                    The Next <br>
                    <span class="text-emerald-300">Generation</span> <br>
                    Soybean.
                </h2>
                <p class="text-emerald-100/60 font-medium leading-relaxed max-w-sm text-sm">
                    Sistem manajemen terintegrasi untuk mengontrol kualitas, stok, dan jaringan distribusi dalam satu genggaman.
                </p>
            </div>

            <div class="relative z-10">
                <div class="flex items-center gap-6">
                    <div class="h-[1px] w-12 bg-emerald-400"></div>
                    <div class="flex gap-4 text-[9px] font-black tracking-[0.4em] text-emerald-200/50 uppercase">
                        <span>Reliable</span>
                        <span>•</span>
                        <span>Scalable</span>
                        <span>•</span>
                        <span>Secure</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side: Form --}}
        <div class="flex-1 p-8 md:p-12 lg:p-20 flex flex-col justify-center bg-white/40">
            <div class="max-w-[400px] mx-auto w-full">
                
                {{-- Header Login --}}
                <div class="mb-10 text-center lg:text-left">
                    {{-- Logo Mobile Only --}}
                    <div class="inline-flex lg:hidden items-center justify-center w-14 h-14 bg-emerald-600 text-white rounded-2xl mb-6 shadow-lg shadow-emerald-200 rotate-3">
                        <span class="material-symbols-rounded text-2xl">eco</span>
                    </div>
                    
                    <h3 class="text-3xl md:text-4xl font-[1000] text-gray-900 tracking-tight mb-2">Authenticating.</h3>
                    <p class="text-gray-400 text-xs md:text-sm font-medium uppercase tracking-widest">Identify yourself to access</p>
                </div>

                {{-- Laravel $slot --}}
                <div class="auth-form">
                    {{ $slot }}
                </div>

                {{-- Link Kembali --}}
                <div class="mt-10 pt-8 border-t border-gray-100">
                    <a href="/" class="group flex items-center justify-center lg:justify-start gap-3 transition-all">
                        <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                            <span class="material-symbols-rounded text-sm">west</span>
                        </div>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 group-hover:text-emerald-600 transition-colors">
                            Exit to Website
                        </span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Footer Info --}}
    <div class="fixed bottom-6 text-[9px] font-bold text-gray-400 uppercase tracking-widest hidden lg:block">
        &copy; 2026 Tempe Pria Jaya • Central Management System
    </div>

</body>
</html>