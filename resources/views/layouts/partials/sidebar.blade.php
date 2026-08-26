<!-- Sidebar Overlay (Mobile only) -->
<div 
    x-show="sidebarOpen" 
    @click="sidebarOpen = false" 
    class="fixed inset-0 z-40 bg-slate-950/70 backdrop-blur-md lg:hidden transition-opacity"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    style="display: none;"
></div>

<!-- Sidebar Container (Clean & Stable, No Effects) -->
<aside 
    :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 lg:w-20'"
    class="fixed inset-y-0 left-0 z-50 flex flex-col bg-slate-900 border-r border-slate-800 shadow-2xl lg:shadow-none transition-all duration-300 ease-in-out lg:static shrink-0"
>
    <!-- Brand Header -->
    <div class="flex items-center justify-between h-16 px-4 bg-slate-950/40 border-b border-slate-800 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 overflow-hidden">
            <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shrink-0 shadow-sm">
                <i class="fa-solid fa-laptop-code text-lg"></i>
            </div>
            <div class="transition-all duration-300 overflow-hidden" :class="!sidebarOpen && 'lg:hidden lg:opacity-0 lg:w-0'">
                <h1 class="font-bold text-white text-base tracking-wide whitespace-nowrap">POS System</h1>
                <p class="text-[10px] text-blue-400 font-semibold tracking-widest uppercase whitespace-nowrap">Computer Store</p>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="text-slate-400 hover:text-white lg:hidden transition-colors p-1 rounded-lg">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
    </div>

    <!-- Navigation Menus -->
    <div class="flex-1 overflow-y-auto overflow-x-hidden px-3 py-4 space-y-6">
        
        <!-- Main Section -->
        <div>
            <p class="px-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" :class="!sidebarOpen && 'lg:hidden'">
                Menu
            </p>
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-6 text-center text-base {{ request()->routeIs('dashboard') ? 'text-white' : 'text-blue-400' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Sale & Transactions Section -->
        <div>
            <p class="px-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" :class="!sidebarOpen && 'lg:hidden'">
                Sale & Transactions
            </p>
            <div class="space-y-1">
                <!-- POS Terminal -->
                <a href="{{ route('pos.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('pos.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-cash-register w-6 text-center text-base {{ request()->routeIs('pos.*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">POS Terminal</span>
                </a>

                <!-- Sales History -->
                <a href="{{ route('sales.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('sales.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-receipt w-6 text-center text-base {{ request()->routeIs('sales.*') ? 'text-white' : 'text-indigo-400' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Sales</span>
                </a>

                <!-- Customers -->
                <a href="{{ route('customers.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('customers.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-users-gear w-6 text-center text-base {{ request()->routeIs('customers.*') ? 'text-white' : 'text-teal-400' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Customers</span>
                </a>
            </div>
        </div>

        <!-- Inventory Section -->
        <div>
            <p class="px-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" :class="!sidebarOpen && 'lg:hidden'">
                Inventory & Stock
            </p>
            <div class="space-y-1">
                <!-- Products -->
                <a href="{{ route('products.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('products.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked w-6 text-center text-base {{ request()->routeIs('products.*') ? 'text-white' : 'text-amber-400' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Products</span>
                </a>

                <!-- Purchases -->
                <a href="{{ route('purchases.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('purchases.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-truck-ramp-box w-6 text-center text-base {{ request()->routeIs('purchases.*') ? 'text-white' : 'text-cyan-400' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Purchases</span>
                </a>

                <!-- Suppliers -->
                <a href="{{ route('suppliers.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('suppliers.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-truck-field w-6 text-center text-base {{ request()->routeIs('suppliers.*') ? 'text-white' : 'text-teal-400' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Suppliers</span>
                </a>
            </div>
        </div>

        <!-- Services & Maintenance Section -->
        <div>
            <p class="px-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" :class="!sidebarOpen && 'lg:hidden'">
                Services & Maintenance
            </p>
            <div class="space-y-1">
                <!-- Repairs -->
                <a href="{{ route('repairs.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('repairs.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-screwdriver-wrench w-6 text-center text-base {{ request()->routeIs('repairs.*') ? 'text-white' : 'text-rose-400' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Repairs</span>
                </a>

                <!-- Expenses -->
                <a href="{{ route('expenses.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('expenses.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-wallet w-6 text-center text-base {{ request()->routeIs('expenses.*') ? 'text-white' : 'text-violet-400' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Expenses</span>
                </a>
            </div>
        </div>

        <!-- Administration Section -->
        <div>
            <p class="px-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" :class="!sidebarOpen && 'lg:hidden'">
                Administration
            </p>
            <div class="space-y-1">
                <!-- Users & Roles -->
                <a href="{{ route('users.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-user-shield w-6 text-center text-base {{ request()->routeIs('users.*') ? 'text-white' : 'text-teal-400' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Users & Roles</span>
                </a>

                <!-- Staff -->
                <a href="{{ route('staff.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('staff.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-users w-6 text-center text-base {{ request()->routeIs('staff.*') ? 'text-white' : 'text-sky-400' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Staff</span>
                </a>
            </div>
        </div>

        <!-- Reports Section -->
        <div>
            <p class="px-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2" :class="!sidebarOpen && 'lg:hidden'">
                Reports
            </p>
            <div class="space-y-1">
                <!-- Reports -->
                <a href="{{ route('reports.index') }}" 
                   class="flex items-center px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs('reports.*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-line w-6 text-center text-base {{ request()->routeIs('reports.*') ? 'text-white' : 'text-yellow-400' }}"></i>
                    <span class="ml-3 truncate" :class="!sidebarOpen && 'lg:hidden'">Reports</span>
                </a>
            </div>
        </div>

    </div>
</aside>