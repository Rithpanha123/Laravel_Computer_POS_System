<header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 z-30 shrink-0 sticky top-0 shadow-sm/50">
    <!-- Left: Hamburger Toggle & Page Title -->
    <div class="flex items-center space-x-3">
        <button 
            @click="sidebarOpen = !sidebarOpen" 
            class="p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition-colors"
        >
            <i class="fa-solid fa-bars-staggered text-lg"></i>
        </button>
        <div class="hidden sm:block">
            <h1 class="text-lg font-bold text-gray-800">@yield('page_heading', 'Dashboard')</h1>
        </div>
    </div>

    <!-- Center: Search Bar -->
    <div class="hidden md:flex items-center flex-1 max-w-md mx-6">
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 text-sm">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input 
                type="text" 
                placeholder="Search orders, products, invoices..." 
                class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
            >
        </div>
    </div>

    <!-- Right: Actions & User Menu -->
    <div class="flex items-center space-x-3">
        <!-- POS Quick Button -->
        <a href="{{ route('pos.index') }}" class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-semibold rounded-xl transition">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>New Sale</span>
        </a>

        <!-- Notifications -->
        <div class="relative" x-data="{ notifyOpen: false }">
            <button @click="notifyOpen = !notifyOpen" class="p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 relative transition-colors">
                <i class="fa-regular fa-bell text-lg"></i>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
            </button>
        </div>

        <!-- User Profile Dropdown -->
        <div class="relative" x-data="{ userMenuOpen: false }">
            <button 
                @click="userMenuOpen = !userMenuOpen" 
                class="flex items-center space-x-2.5 p-1.5 rounded-xl hover:bg-gray-50 focus:outline-none transition"
            >
                @if(Auth::user()->profile_picture ?? false)
                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="w-8 h-8 rounded-xl object-cover border border-gray-200">
                @else
                    <div class="w-8 h-8 rounded-xl bg-slate-900 text-white flex items-center justify-center text-xs font-bold shadow-sm">
                        {{ strtoupper(substr(Auth::user()->username ?? 'A', 0, 1)) }}
                    </div>
                @endif
                <div class="hidden lg:block text-left">
                    <p class="text-xs font-semibold text-gray-800 leading-tight">{{ Auth::user()->full_name ?? Auth::user()->username ?? 'User' }}</p>
                    <p class="text-[10px] text-gray-400 leading-tight">{{ Auth::user()->role->role_name ?? 'Staff' }}</p>
                </div>
                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 ml-1"></i>
            </button>

            <!-- Dropdown Menu -->
            <div 
                x-show="userMenuOpen" 
                @click.away="userMenuOpen = false" 
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="absolute right-0 mt-2 w-56 bg-white border border-gray-100 rounded-2xl shadow-xl py-2 z-50"
                style="display: none;"
            >
                <div class="px-4 py-2 border-b border-gray-100">
                    <p class="text-xs font-semibold text-gray-800">{{ Auth::user()->full_name ?? Auth::user()->username }}</p>
                    <p class="text-[11px] text-gray-400 truncate">{{ Auth::user()->email ?? 'user@pos.com' }}</p>
                </div>

                <div class="py-1">
                    <a href="#" class="flex items-center px-4 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 transition">
                        <i class="fa-regular fa-user w-5 text-gray-400"></i> Profile Settings
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 transition">
                        <i class="fa-solid fa-sliders w-5 text-gray-400"></i> System Configuration
                    </a>
                </div>

                <div class="border-t border-gray-100 pt-1">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50 transition">
                            <i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>