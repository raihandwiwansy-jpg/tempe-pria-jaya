<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tempe Pria Jaya | Heritage Modernity</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=Playfair+Display:ital,wght@0,900;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', serif; }
        
        .hero-gradient {
            background: radial-gradient(circle at 0% 0%, rgba(16, 185, 129, 0.08) 0%, transparent 50%),
                        radial-gradient(circle at 100% 100%, rgba(245, 158, 11, 0.05) 0%, transparent 50%),
                        #FDFDFD;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        @keyframes revealUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-reveal { animation: revealUp 1s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(1deg); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }

        .product-card {
            transition: all 0.5s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
    </style>
</head>

<body class="text-gray-900 antialiased overflow-x-hidden hero-gradient">

    <nav class="fixed top-0 w-full z-50 p-4 md:p-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center glass-nav shadow-lg p-3 md:p-4 rounded-3xl md:rounded-[2.5rem]">
            <div class="flex items-center gap-2 md:gap-3 ml-2">
                <div class="w-9 h-9 md:w-11 md:h-11 bg-emerald-600 rounded-xl md:rounded-2xl flex items-center justify-center text-white shadow-lg rotate-3">
                    <span class="material-symbols-rounded text-xl md:text-2xl">eco</span>
                </div>
                <span class="font-black text-lg md:text-2xl tracking-tighter uppercase">Pria<span class="text-emerald-600">Jaya</span></span>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" class="md:hidden w-10 h-10 flex items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                    <span class="material-symbols-rounded">person</span>
                </a>
                <div class="hidden md:flex items-center gap-2 bg-gray-100/50 p-1.5 rounded-[2rem]">
                    <a href="{{ route('login') }}" class="px-5 py-2 text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-emerald-600 transition-all">Admin</a>
                    <a href="{{ route('login') }}" class="bg-emerald-600 px-6 py-2.5 rounded-full shadow-lg text-[10px] font-black uppercase tracking-widest text-white hover:bg-emerald-700 transition-all">
                        Reseller Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="relative min-h-screen flex items-center justify-center pt-28 pb-10 md:pt-32 md:pb-20 px-6">
        <div class="max-w-7xl w-full grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            
            <div class="lg:col-span-7 space-y-6 md:space-y-10 text-center lg:text-left animate-reveal">
                <div class="inline-flex items-center gap-3 px-4 py-2 bg-white border border-emerald-100 rounded-full shadow-sm">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span class="text-[9px] md:text-[11px] font-black uppercase tracking-[0.2em] text-emerald-700">Premium Soy Culture</span>
                </div>

                <h1 class="font-playfair text-5xl md:text-7xl lg:text-[100px] font-black text-gray-900 leading-[0.9] tracking-tighter">
                    Tempe <br> 
                    <span class="text-emerald-600 italic">Pria Jaya</span> <br>
                    <span class="text-gray-200">Berkualitas.</span>
                </h1>

                <p class="text-base md:text-xl text-gray-400 font-medium max-w-xl leading-relaxed mx-auto lg:mx-0">
                    Kami tidak hanya membuat tempe, kami menjaga warisan. UMKM inovatif dengan cita rasa autentik untuk gaya hidup modern.
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-4 md:gap-8 justify-center lg:justify-start pt-4">
                    <a href="https://wa.me/6285270170261?text=Halo%20Admin%20Tempe%20Pria%20Jaya" 
                       target="_blank" 
                       class="group relative w-full sm:w-auto bg-emerald-600 text-white px-8 md:px-12 py-5 md:py-7 rounded-2xl md:rounded-[2.5rem] font-black text-xs uppercase tracking-[0.2em] transition-all hover:bg-emerald-700 active:scale-95 flex items-center justify-center gap-4">
                        <i class="fab fa-whatsapp text-xl md:text-2xl"></i>
                        Daftar Reseller
                        <div class="absolute -right-2 -top-2 bg-amber-400 text-gray-900 px-2 py-1 rounded-lg text-[8px] font-black shadow-lg animate-bounce">HOT</div>
                    </a>
                    
                    <a href="https://instagram.com/tempepriajaya" target="_blank" class="flex items-center gap-4 group">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-full border border-gray-200 flex items-center justify-center group-hover:bg-gradient-to-tr group-hover:from-yellow-400 group-hover:to-purple-600 group-hover:text-white transition-all">
                            <i class="fab fa-instagram text-xl"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Instagram</p>
                            <p class="text-xs font-black text-gray-800">@tempepriajaya</p>
                        </div>
                    </a>
                </div>

                <div class="grid grid-cols-3 gap-4 md:gap-8 pt-8 md:pt-12 border-t border-gray-100 max-w-lg mx-auto lg:mx-0">
                    <div>
                        <p class="text-2xl md:text-3xl font-black text-gray-900">100+</p>
                        <p class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mitra</p>
                    </div>
                    <div>
                        <p class="text-2xl md:text-3xl font-black text-gray-900">Grade A</p>
                        <p class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kualitas</p>
                    </div>
                    <div>
                        <p class="text-2xl md:text-3xl font-black text-gray-900">Fresh</p>
                        <p class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase tracking-widest">Harian</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 relative animate-reveal" style="animation-delay: 0.2s">
                <div class="product-card relative z-10 bg-white p-3 md:p-5 rounded-[2.5rem] md:rounded-[4rem] shadow-2xl border border-white animate-float">
                    <div class="overflow-hidden rounded-[2rem] md:rounded-[3.5rem] bg-gray-100 aspect-[4/5] relative group">
                        
                        <img src="https://i.ibb.co.com/7dD6WD2R/produk-tempe.jpg" 
                             alt="Tempe Pria Jaya" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                        <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/60 to-transparent opacity-60"></div>
                        
                        <div class="absolute bottom-4 left-4 right-4 md:bottom-8 md:left-8 md:right-8 glass-nav p-5 md:p-8 rounded-2xl md:rounded-[2.5rem]">
                            <p class="text-emerald-600 font-black text-[8px] md:text-[10px] uppercase tracking-[0.2em] mb-1">Heritage Selection</p>
                            <h4 class="text-lg md:text-2xl font-black text-gray-900 tracking-tighter leading-tight">Tempe Heritage Leaf</h4>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="px-3 py-1 bg-emerald-600 text-white rounded-full text-[7px] md:text-[9px] font-black uppercase">Ready Stock</span>
                                <span class="text-[9px] md:text-xs font-bold text-gray-500 italic">Original</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="absolute -top-5 -right-5 w-24 h-24 bg-amber-200 rounded-full blur-2xl opacity-40"></div>
                <div class="absolute -bottom-5 -left-5 w-32 h-32 bg-emerald-200 rounded-full blur-2xl opacity-40"></div>
            </div>

        </div>
    </main>

    <footer class="py-10 px-6 border-t border-gray-100 bg-white">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3 opacity-50">
                <span class="material-symbols-rounded text-emerald-600">eco</span>
                <span class="font-black text-xs tracking-tighter uppercase">PRIA JAYA 2026</span>
            </div>
            <p class="text-[8px] md:text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em]">
                &copy; Heritage Innovation.
            </p>
            <div class="flex gap-6 text-gray-300">
                <i class="fab fa-instagram hover:text-emerald-600 transition-colors"></i>
                <i class="fab fa-whatsapp hover:text-emerald-600 transition-colors"></i>
            </div>
        </div>
    </footer>

</body>
</html>