<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Reseller Pria Jaya</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #059669; border-radius: 10px; }

        .sidebar-reseller {
            background: linear-gradient(180deg, #064e3b 0%, #022c22 100%);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1rem;
            border-radius: 1.25rem;
            transition: all 0.3s;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 0.25rem;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            transform: translateX(4px);
        }

        .nav-active {
            background: rgba(255, 255, 255, 0.1) !important;
            border-left: 4px solid #34d399;
            color: #34d399 !important;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
    </style>
</head>

<body class="bg-[#F8FAF9] antialiased overflow-hidden">

<div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <div x-show="sidebarOpen" 
         x-transition.opacity
         @click="sidebarOpen = false" 
         class="fixed inset-0 bg-black/60 z-[60] lg:hidden"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 w-72 sidebar-reseller text-white flex flex-col transition-transform duration-300 transform lg:relative lg:translate-x-0 z-[70] shadow-2xl flex-shrink-0">
        
        <div class="p-8 flex-shrink-0">
            <div class="flex items-center gap-4 group">
                <div class="bg-white/10 p-2.5 rounded-2xl backdrop-blur-md border border-white/10 group-hover:bg-emerald-400 transition-all duration-500">
                    <span class="material-symbols-rounded text-white group-hover:text-green-900 text-3xl">storefront</span>
                </div>
                <div class="min-w-0">
                    <h2 class="text-xl font-black tracking-tighter leading-none italic truncate text-white uppercase">RESELLER<span class="text-emerald-400">HUB</span></h2>
                    <p class="text-[10px] font-bold text-emerald-500/80 tracking-[0.3em] uppercase mt-1 truncate">Pria Jaya Mitra</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 overflow-y-auto py-2 custom-scrollbar">
            <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] px-4 mb-4">Mitra Menu</p>
            
            <a href="/reseller/dashboard" class="nav-item {{ request()->is('reseller/dashboard') ? 'nav-active' : '' }}">
                <span class="material-symbols-rounded">dashboard</span>
                <span class="text-[10px] font-black uppercase tracking-widest">Dashboard</span>
            </a>

            <a href="/reseller/pesanan" class="nav-item {{ request()->is('reseller/pesanan*') ? 'nav-active' : '' }}">
                <span class="material-symbols-rounded">local_mall</span>
                <span class="text-[10px] font-black uppercase tracking-widest">Pesanan Saya</span>
            </a>

            <a href="/reseller/setoran" class="nav-item {{ request()->is('reseller/setoran*') ? 'nav-active' : '' }}">
                <span class="material-symbols-rounded">payments</span>
                <span class="text-[10px] font-black uppercase tracking-widest">Setoran</span>
            </a>

            <a href="/reseller/info-barang" class="nav-item {{ request()->is('reseller/info-barang*') ? 'nav-active' : '' }}">
                <span class="material-symbols-rounded">inventory</span>
                <span class="text-[10px] font-black uppercase tracking-widest">Info Barang</span>
            </a>

            <div class="mt-8 px-2 hidden sm:block">
                <div class="bg-gradient-to-br from-green-900/50 to-emerald-900/50 rounded-[2rem] p-5 border border-white/5 relative overflow-hidden group">
                    <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                        <span class="material-symbols-rounded text-6xl">support_agent</span>
                    </div>
                    <p class="text-[9px] font-black text-emerald-400 uppercase tracking-widest mb-1">Butuh Bantuan?</p>
                    <p class="text-xs font-bold text-white leading-snug">Chat WhatsApp <br>Admin Produksi</p>
                    <a href="https://wa.me/6285270170261" target="_blank" class="mt-4 flex items-center justify-center gap-2 bg-emerald-500 text-green-950 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-tighter hover:bg-white transition-colors">
                        Chat Sekarang
                    </a>
                </div>
            </div>
        </nav>

        <div class="p-6 border-t border-white/5 bg-black/10 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center font-black text-green-900 shadow-lg flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-black truncate text-white">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-tighter">Mitra Resmi</p>
                </div>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <header class="glass-header h-20 flex items-center justify-between px-4 md:px-8 flex-shrink-0 z-50">
            <div class="flex items-center gap-3 md:gap-4 min-w-0">
                <button @click="sidebarOpen = true" class="lg:hidden text-emerald-800 p-2 hover:bg-emerald-50 rounded-xl transition-all">
                    <span class="material-symbols-rounded">menu</span>
                </button>
                <h1 class="text-sm md:text-xl font-[1000] text-gray-800 tracking-tight uppercase truncate">
                    @yield('title')
                </h1>
            </div>

            <div class="flex items-center gap-3">
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="group bg-red-50 text-red-500 p-2.5 md:px-5 md:py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all duration-300 flex items-center gap-2">
                        <span class="material-symbols-rounded text-sm md:text-lg group-hover:rotate-180 transition-transform">logout</span>
                        <span class="hidden md:inline">Log Out</span>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-8 relative">
            <div class="fixed top-0 right-0 w-80 h-80 bg-emerald-100/20 rounded-full blur-[100px] pointer-events-none -z-10"></div>
            
            <div class="animate-fade-in">
                @yield('content')
            </div>
        </main>

    </div>
</div>

</body>
</html>