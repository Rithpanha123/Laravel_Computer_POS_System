<!-- Sidebar Overlay (Mobile only) -->
<div 
    x-show="sidebarOpen" 
    @click="sidebarOpen = false" 
    class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    style="display: none;"
></div>

<!-- Sidebar Container -->
<aside 
    :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 lg:w-20'"
    class="fixed inset-y-0 left-0 z-50 flex flex-col bg-slate-900 border-r border-slate-800 transition-all duration-300 ease-in-out lg:static shrink-0"
>
    <!-- Brand Header -->
    <div class="flex items-center justify-between h-16 px-4 bg-slate-950/70 border-b border-slate-800">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 overflow-hidden">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white shrink-0 shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-laptop-code text-lg"></i>
            </div>
            <div class="transition-opacity duration-200" :class="!sidebarOpen && 'lg:hidden'">
                <h1 class="font-bold text-white text-base tracking-wide whitespace-nowrap">POS System</h1>
                <p class="text-[10px] text-blue-400 font-medium tracking-wider uppercase">Computer Store</p>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white lg:hidden">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Navigation Menus -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
        <!-- Main Section -->
        <div>
            <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2" :class="!sidebarOpen && 'lg:hidden'">
                Main Menu
            </p>
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors group {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-6 text-center text-base {{ request()->routeIs('dashboard') ? 'text-white' : 'text-blue-400 group-hover:text-white' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Dashboard</span>
                </a>

                <!-- POS Terminal -->
                <a href="{{ route('pos.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors group">
                    <i class="fa-solid fa-cash-register w-6 text-center text-base text-emerald-400 group-hover:text-white"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">POS Terminal</span>
                </a>

                <!-- Sales History -->
                <a href="{{ route('sales.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors group">
                    <i class="fa-solid fa-receipt w-6 text-center text-base text-indigo-400 group-hover:text-white"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Sales</span>
                </a>
            </div>
        </div>

        <!-- Inventory Section -->
        <div>
            <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2" :class="!sidebarOpen && 'lg:hidden'">
                Inventory & Stock
            </p>
            <div class="space-y-1">
                <!-- Products -->
                <a href="{{ route('products.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors group">
                    <i class="fa-solid fa-boxes-stacked w-6 text-center text-base text-amber-400 group-hover:text-white"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Products</span>
                </a>

                <!-- Purchases -->
                <a href="{{ route('purchases.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors group">
                    <i class="fa-solid fa-truck-ramp-box w-6 text-center text-base text-cyan-400 group-hover:text-white"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Purchases</span>
                </a>

                <!-- Repairs -->
                <a href="#" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors group">
                    <i class="fa-solid fa-screwdriver-wrench w-6 text-center text-base text-rose-400 group-hover:text-white"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Repairs</span>
                </a>

                <!-- Expenses -->
                <a href="#" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors group">
                    <i class="fa-solid fa-wallet w-6 text-center text-base text-violet-400 group-hover:text-white"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Expenses</span>
                </a>
            </div>
        </div>

        <!-- Administration Section -->
        <div>
            <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2" :class="!sidebarOpen && 'lg:hidden'">
                Administration
            </p>
            <div class="space-y-1">
                <!-- User Management -->
                <a href="{{ route('users.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors group {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-users-gear w-6 text-center text-base {{ request()->routeIs('users.*') ? 'text-white' : 'text-teal-400 group-hover:text-white' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Users & Roles</span>
                </a>

                <!-- Reports -->
                <a href="#" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors group">
                    <i class="fa-solid fa-chart-line w-6 text-center text-base text-yellow-400 group-hover:text-white"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Reports</span>
                </a>
            </div>
        </div>
    </div>

    <!-- User Profile Footer -->
    <div class="p-3 bg-slate-950/80 border-t border-slate-800 flex items-center justify-between">
        <div class="flex items-center space-x-3 overflow-hidden">
            @if(Auth::user()->profile_picture ?? false)
                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="w-9 h-9 rounded-xl object-cover shrink-0 border border-slate-700">
            @else
                <div class="w-9 h-9 rounded-xl bg-blue-600/20 text-blue-400 border border-blue-500/30 flex items-center justify-center text-sm font-bold shrink-0">
                    {{ strtoupper(substr(Auth::user()->full_name ?? Auth::user()->username ?? 'A', 0, 1)) }}
                </div>
            @endif
            <div class="overflow-hidden transition-opacity duration-200" :class="!sidebarOpen && 'lg:hidden'">
                <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->full_name ?? Auth::user()->username ?? 'Admin' }}</p>
                <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->role->role_name ?? 'Administrator' }}</p>
            </div>
        </div>
    </div>
</aside>