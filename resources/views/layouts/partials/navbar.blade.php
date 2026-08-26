<header class="h-16 bg-white/80 dark:bg-slate-900/85 backdrop-blur-xl border-b border-gray-100 dark:border-slate-800/80 flex items-center justify-between px-4 sm:px-6 z-30 shrink-0 sticky top-0 shadow-sm transition-all duration-300 animate-slide-blur">
    <!-- Left: Hamburger Toggle & Page Title (✨ Logo/Title Reveal) -->
    <div class="flex items-center space-x-3">
        <button 
            @click="sidebarOpen = !sidebarOpen" 
            class="p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-800 active:scale-95 focus:outline-none transition-all duration-200 btn-pop"
            title="Toggle Sidebar"
        >
            <i class="fa-solid fa-bars-staggered text-lg transition-transform duration-300"></i>
        </button>
        <div class="hidden sm:block overflow-hidden">
            <h1 class="text-lg font-bold text-gray-800 dark:text-gray-100 tracking-tight animate-reveal-title">
                @yield('page_heading', 'Dashboard')
            </h1>
        </div>
    </div>

    <!-- Center: Search Bar (🌈 Gradient Glow effect on focus) -->
    <div class="hidden md:flex items-center flex-1 max-w-md mx-6">
        <div class="relative w-full group">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 group-focus-within:text-blue-500 text-sm transition-colors duration-200">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input 
                type="text" 
                placeholder="Search orders, products, invoices..." 
                class="w-full pl-10 pr-4 py-2.5 bg-gray-50/80 dark:bg-slate-800/80 border border-gray-200 dark:border-slate-700/80 rounded-2xl text-xs sm:text-sm text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-900 shadow-inner gradient-glow transition-all duration-300"
            >
        </div>
    </div>

    <!-- Right: Actions, Theme Toggle & User Menu -->
    <div class="flex items-center space-x-3">
        <!-- POS Quick Button (🎯 Button Pop) -->
        <a href="{{ route('pos.index') }}" class="hidden sm:inline-flex items-center space-x-1.5 px-3.5 py-2 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-800/50 text-xs font-semibold rounded-xl active:scale-95 shadow-sm hover:shadow transition-all duration-200 btn-pop">
            <i class="fa-solid fa-plus text-xs animate-pulse"></i>
            <span>New Sale</span>
        </a>

        <!-- Dark/Light Mode Toggle Button (Alpine-Synced) -->
        <button 
            @click="
                darkMode = !darkMode;
                localStorage.setItem('theme', darkMode ? 'dark' : 'light');
                if (darkMode) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            "
            class="p-2.5 rounded-xl text-gray-500 dark:text-gray-400 hover:text-amber-500 dark:hover:text-amber-400 hover:bg-gray-100 dark:hover:bg-slate-800 active:scale-95 relative transition-all duration-200 btn-pop"
            title="Toggle Theme"
        >
            <!-- Show Sun when Dark mode is active -->
            <i class="fa-regular fa-sun text-lg transition-transform duration-300 hover:rotate-45" x-show="darkMode"></i>
            <!-- Show Moon when Light mode is active -->
            <i class="fa-regular fa-moon text-lg transition-transform duration-300 hover:-rotate-12" x-show="!darkMode"></i>
        </button>

        <!-- Notifications -->
        <div class="relative" x-data="{ notifyOpen: false }">
            <button @click="notifyOpen = !notifyOpen" class="p-2.5 rounded-xl text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-slate-800 active:scale-95 relative transition-all duration-200 btn-pop">
                <i class="fa-regular fa-bell text-lg"></i>
                <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white dark:ring-slate-900 animate-ping"></span>
                <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white dark:ring-slate-900"></span>
            </button>
        </div>

        <!-- User Profile Dropdown -->
        <div class="relative" x-data="{ userMenuOpen: false }">
            <button 
                @click="userMenuOpen = !userMenuOpen" 
                class="flex items-center space-x-2.5 p-1.5 rounded-xl hover:bg-gray-100/80 dark:hover:bg-slate-800/80 active:scale-95 focus:outline-none transition-all duration-200 btn-pop"
            >
                @if(Auth::user()->profile_picture ?? false)
                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="w-8 h-8 rounded-xl object-cover border border-gray-200 dark:border-slate-700 shadow-sm">
                @else
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-sm">
                        {{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 1)) }}
                    </div>
                @endif
                <div class="hidden lg:block text-left">
                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 leading-tight">{{ Auth::user()->full_name ?? Auth::user()->username ?? 'User' }}</p>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 leading-tight">{{ Auth::user()->role->role_name ?? 'Staff' }}</p>
                </div>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-1 transition-transform duration-200" :class="{ 'rotate-180': userMenuOpen }"></i>
            </button>

            <!-- Dropdown Menu with 🔗 Staggered Links & 💫 Active-line Animation -->
            <div 
                x-show="userMenuOpen" 
                @click.away="userMenuOpen = false" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95 translate-y-2 blur-sm"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0 blur-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0 blur-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-2 blur-sm"
                class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl shadow-2xl py-2 z-50 backdrop-blur-xl"
                style="display: none;"
            >
                <div class="px-4 py-2.5 border-b border-gray-100 dark:border-slate-800 stagger-item">
                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ Auth::user()->full_name ?? Auth::user()->username }}</p>
                    <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate">{{ Auth::user()->email ?? 'user@pos.com' }}</p>
                </div>

                <div class="py-1">
                    <a href="#" class="flex items-center px-4 py-2.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-150 menu-link-item stagger-item">
                        <i class="fa-regular fa-user w-5 text-gray-400"></i> Profile Settings
                    </a>
                    <a href="#" class="flex items-center px-4 py-2.5 text-xs font-medium text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-150 menu-link-item stagger-item">
                        <i class="fa-solid fa-sliders w-5 text-gray-400"></i> System Configuration
                    </a>
                </div>

                <div class="border-t border-gray-100 dark:border-slate-800 pt-1 stagger-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2.5 text-xs font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors duration-150">
                            <i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    /* 🌀 Slide + Blur Header Entrance */
    @keyframes slideBlurIn {
        0% { opacity: 0; transform: translateY(-15px); filter: blur(6px); }
        100% { opacity: 1; transform: translateY(0); filter: blur(0); }
    }

    /* ✨ Logo/Title Reveal Effect */
    @keyframes revealTitle {
        0% { opacity: 0; transform: translateX(-10px); }
        100% { opacity: 1; transform: translateX(0); }
    }

    .animate-slide-blur {
        animation: slideBlurIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .animate-reveal-title {
        animation: revealTitle 0.4s cubic-bezier(0.16, 1, 0.3, 1) 0.1s forwards;
        opacity: 0;
    }

    /* 🌈 Gradient Glow on Input Focus */
    .gradient-glow:focus {
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.2), inset 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    /* 💫 Active-Line / Hover Accent Animation for Menu Items */
    .menu-link-item {
        position: relative;
    }
    .menu-link-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%) scaleY(0);
        width: 3px;
        height: 60%;
        background-color: #2563eb;
        border-radius: 0 4px 4px 0;
        transition: transform 0.2s ease;
    }
    .menu-link-item:hover::before {
        transform: translateY(-50%) scaleY(1);
    }

    /* 🔗 Staggered Menu Links Effect */
    .stagger-item:nth-child(1) { animation: slideBlurIn 0.3s ease 0.05s both; }
    .stagger-item:nth-child(2) { animation: slideBlurIn 0.3s ease 0.1s both; }
    .stagger-item:nth-child(3) { animation: slideBlurIn 0.3s ease 0.15s both; }

    /* 🎯 Button Pop */
    .btn-pop {
        transition: transform 0.15s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .btn-pop:active {
        transform: scale(0.92);
    }
</style>