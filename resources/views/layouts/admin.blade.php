<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Pria Jaya</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #065f46; border-radius: 10px; }

        .sidebar-gradient {
            background: linear-gradient(180deg, #064e3b 0%, #022c22 100%);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.85rem;
            transition: all 0.3s;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 0.125rem;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            transform: translateX(4px);
        }

        .nav-active {
            background: rgba(255, 255, 255, 0.1) !important;
            border-left: 4px solid #fbbf24;
            color: #fbbf24 !important;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
    </style>
</head>

<body class="bg-[#F4F7F5] antialiased overflow-hidden">

<div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <div x-show="sidebarOpen"
         x-transition.opacity
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/60 z-[60] lg:hidden"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 w-72 sidebar-gradient text-white flex flex-col transition-transform duration-300 transform lg:relative lg:translate-x-0 z-[70] shadow-2xl flex-shrink-0">

        <div class="p-6 md:p-8 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="bg-yellow-400 p-2 rounded-xl text-green-900 shadow-lg shadow-yellow-400/20">
                    <span class="material-symbols-rounded text-2xl block">eco</span>
                </div>
                <div class="min-w-0">
                    <h2 class="text-lg font-black tracking-tighter italic truncate">PRIA JAYA</h2>
                    <p class="text-[9px] font-bold text-green-400 tracking-[0.2em] uppercase truncate">Management</p>
                </div>
            </div>
        </div>

        <nav class="flex-1 px-4 overflow-y-auto pb-6 custom-scrollbar">
            <div class="mb-6">
                <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] px-4 mb-2">Core Analytics</p>

                <a href="{{ url('/admin/dashboard') }}" class="nav-item {{ request()->is('admin/dashboard') ? 'nav-active' : '' }}">
                    <span class="material-symbols-rounded text-xl">dashboard</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Dashboard</span>
                </a>

                <a href="{{ url('/admin/gudang') }}" class="nav-item {{ request()->is('admin/gudang*') ? 'nav-active' : '' }}">
                    <span class="material-symbols-rounded text-xl">inventory_2</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Stok Gudang</span>
                </a>

                {{-- MENU PRODUKSI (DIPISAH SUPAYA TIDAK MENTAL) --}}
                <a href="{{ url('/admin/produksi') }}" class="nav-item {{ request()->is('admin/produksi') ? 'nav-active' : '' }}">
                    <span class="material-symbols-rounded text-xl">rebase_edit</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Bahan Baku</span>
                </a>

                <a href="{{ url('/admin/produksi-jadi') }}" class="nav-item {{ request()->is('admin/produksi-jadi*') ? 'nav-active' : '' }}">
                    <span class="material-symbols-rounded text-xl">inventory</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Produksi Jadi</span>
                </a>
            </div>

            <div class="mb-6">
                <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] px-4 mb-2">Market & Sales</p>
                <a href="{{ route('admin.barang_reseller.index') }}" class="nav-item {{ request()->routeIs('admin.barang_reseller.*') ? 'nav-active' : '' }}">
                    <span class="material-symbols-rounded text-xl">storefront</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Katalog</span>
                </a>
                <a href="{{ url('/admin/pesanan') }}" class="nav-item {{ request()->is('admin/pesanan*') ? 'nav-active' : '' }}">
                    <span class="material-symbols-rounded text-xl">shopping_cart</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Pesanan Masuk</span>
                </a>
                <a href="{{ url('/admin/reseller') }}" class="nav-item {{ request()->is('admin/reseller*') ? 'nav-active' : '' }}">
                    <span class="material-symbols-rounded text-xl">group</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Data Reseller</span>
                </a>
                <a href="{{ url('/admin/setoran') }}" class="nav-item {{ request()->is('admin/setoran*') ? 'nav-active' : '' }}">
                    <span class="material-symbols-rounded text-xl">paid</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Setoran</span>
                </a>
            </div>

            <div class="mb-6">
                <p class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] px-4 mb-2">Finance & HR</p>
                <a href="{{ url('/admin/keuangan') }}" class="nav-item {{ request()->is('admin/keuangan*') ? 'nav-active' : '' }}">
                    <span class="material-symbols-rounded text-xl">payments</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Laporan Keuangan</span>
                </a>
                <a href="{{ url('/admin/karyawan') }}" class="nav-item {{ request()->is('admin/karyawan*') ? 'nav-active' : '' }}">
                    <span class="material-symbols-rounded text-xl">badge</span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Karyawan</span>
                </a>
            </div>
        </nav>

        <div class="p-6 border-t border-white/5 flex-shrink-0 bg-black/10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-400 rounded-xl flex items-center justify-center font-black text-green-900 flex-shrink-0">
                    {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : '?' }}
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-black truncate">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-[9px] font-bold text-green-400 uppercase tracking-tighter">Super Admin</p>
                </div>
            </div>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <header class="glass-header h-20 flex items-center justify-between px-4 md:px-8 flex-shrink-0 z-50">
            <div class="flex items-center gap-3 md:gap-4 min-w-0">
                <button @click="sidebarOpen = true" class="lg:hidden text-green-800 p-2 hover:bg-green-100 rounded-xl transition-all">
                    <span class="material-symbols-rounded">menu</span>
                </button>
                <h1 class="text-sm md:text-xl font-[1000] text-gray-800 tracking-tight uppercase truncate">
                    @yield('title')
                </h1>
            </div>

            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="group bg-red-50 text-red-600 p-2.5 md:px-5 md:py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all duration-300 flex items-center gap-2">
                        <span class="material-symbols-rounded text-sm md:text-lg group-hover:rotate-180 transition-transform">logout</span>
                        <span class="hidden md:inline">Log Out</span>
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-8 relative">
            <div class="fixed top-0 right-0 w-96 h-96 bg-green-200/10 rounded-full blur-[100px] pointer-events-none -z-10"></div>

            <div class="animate-fade-in">
                @yield('content')
            </div>
        </main>

    </div>
</div>

</body>
</html>
