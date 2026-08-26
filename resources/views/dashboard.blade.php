@extends('layouts.app')

@section('title', 'ផ្ទាំងគ្រប់គ្រងទូទៅ - POS System')
@section('page_heading', 'Dashboard Overview')

@section('content')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6 animate-wave-page relative overflow-hidden pb-10">

    <!-- 💫 Ambient Background Glowing Orbs/Particles -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-500/10 dark:bg-blue-600/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow"></div>
    <div class="absolute top-1/3 -right-24 w-96 h-96 bg-indigo-500/10 dark:bg-indigo-600/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow" style="animation-delay: 1.5s;"></div>

    <!-- Top Statistics Cards (Row 1: 4 Main Financials with 🪄 3D Effect) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 relative z-10">
        
        <!-- Card 1: Today Sales -->
        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-5 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center justify-between card-3d stagger-card">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">ការលក់ថ្ងៃនេះ (Today Sales)</p>
                <h3 class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 count-up" data-target="{{ $todaySales }}" data-prefix="$" data-decimals="2">0.00</h3>
                <p class="text-[11px] font-medium {{ $salesGrowth >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500' }} mt-1 flex items-center">
                    <i class="fa-solid {{ $salesGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1"></i>
                    {{ $salesGrowth >= 0 ? '+' : '' }}{{ $salesGrowth }}% ធៀបម្សិលមិញ
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-500 to-indigo-500 text-white flex items-center justify-center text-xl shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
        </div>

        <!-- Card 2: Purchases (ទិញចូលស្តុក) -->
        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-5 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center justify-between card-3d stagger-card">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">ទិញចូលខែនេះ (Purchases)</p>
                <h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-1 count-up" data-target="{{ $monthPurchases }}" data-prefix="$" data-decimals="2">0.00</h3>
                <p class="text-[11px] font-medium text-gray-400 dark:text-gray-400 mt-1">
                    <i class="fa-solid fa-truck-ramp-box mr-1"></i> ថ្ងៃនេះទិញ ${{ number_format($todayPurchases, 2) }} ({{ $todayPurchasesCount }})
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-500 text-white flex items-center justify-center text-xl shadow-lg shadow-indigo-500/30">
                <i class="fa-solid fa-cart-flatbed"></i>
            </div>
        </div>

        <!-- Card 3: Monthly Expenses (ការចំណាយ) -->
        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-5 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center justify-between card-3d stagger-card">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">ចំណាយខែនេះ (Expenses)</p>
                <h3 class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1 count-up" data-target="{{ $monthExpenses }}" data-prefix="$" data-decimals="2">0.00</h3>
                <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 mt-1">
                    <i class="fa-solid fa-wallet mr-1"></i> ចំណាយថ្ងៃនេះ ${{ number_format($todayExpenses, 2) }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-rose-500 to-pink-500 text-white flex items-center justify-center text-xl shadow-lg shadow-rose-500/30">
                <i class="fa-solid fa-arrow-down-wide-short"></i>
            </div>
        </div>

        <!-- Card 4: Active Repairs (កំពុងជួសជុល) -->
        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-5 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center justify-between card-3d stagger-card">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">កំពុងជួសជុល (Repairs)</p>
                <h3 class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1"><span class="count-up" data-target="{{ $activeRepairsCount }}" data-decimals="0">0</span> គ្រឿង</h3>
                <p class="text-[11px] font-medium text-emerald-600 dark:text-emerald-400 mt-1">
                    <i class="fa-solid fa-circle-check mr-1"></i> រួចរាល់ {{ $completedRepairs }} គ្រឿង
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-500 text-white flex items-center justify-center text-xl shadow-lg shadow-amber-500/30">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
        </div>

    </div>

    <!-- Row 2: Secondary Quick Overview (Stock, Customers, Staff) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 relative z-10">
        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-4 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center gap-3.5 card-3d stagger-card">
            <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400">ជិតអស់ពីស្តុក</p>
                <h4 class="text-base font-bold text-rose-600 dark:text-rose-400"><span class="count-up" data-target="{{ $lowStockCount }}" data-decimals="0">0</span> មុខ</h4>
            </div>
        </div>

        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-4 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center gap-3.5 card-3d stagger-card">
            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-cubes"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400">មុខទំនិញសរុប</p>
                <h4 class="text-base font-bold text-gray-800 dark:text-gray-100"><span class="count-up" data-target="{{ $totalProducts }}" data-decimals="0">0</span> មុខ</h4>
            </div>
        </div>

        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-4 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center gap-3.5 card-3d stagger-card">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 dark:bg-cyan-950/40 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400">អតិថិជនសរុប</p>
                <h4 class="text-base font-bold text-gray-800 dark:text-gray-100"><span class="count-up" data-target="{{ $totalCustomers }}" data-decimals="0">0</span> នាក់</h4>
            </div>
        </div>

        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-4 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center gap-3.5 card-3d stagger-card">
            <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/40 text-teal-600 dark:text-teal-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400">បុគ្គលិកសកម្ម</p>
                <h4 class="text-base font-bold text-gray-800 dark:text-gray-100"><span class="count-up" data-target="{{ $totalStaff }}" data-decimals="0">0</span> នាក់</h4>
            </div>
        </div>
    </div>

    <!-- Charts Section: 7 Days Sales vs Purchases vs Expenses -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 relative z-10">
        
        <!-- Chart 1: 7 Days Multi-Bar Chart -->
        <div class="lg:col-span-2 bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-6 rounded-3xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex flex-col justify-between card-3d stagger-card">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100">ស្ថិតិប្រៀបធៀបចំណូល-ចំណាយ (៧ ថ្ងៃចុងក្រោយ)</h3>
                    <p class="text-xs text-gray-400">ការលក់ចេញ vs ការទិញចូលស្តុក vs ការចំណាយទូទៅ</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-semibold">
                    <span class="flex items-center gap-1 text-blue-600"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> លក់</span>
                    <span class="flex items-center gap-1 text-indigo-600"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> ទិញចូល</span>
                    <span class="flex items-center gap-1 text-rose-500"><span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span> ចំណាយ</span>
                </div>
            </div>
            <div class="h-64 sm:h-72 w-full">
                <canvas id="salesExpenseChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Repairs Status Breakdown -->
        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-6 rounded-3xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex flex-col justify-between card-3d stagger-card">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-100">ស្ថានភាពសេវាកម្មជួសជុល</h3>
                <p class="text-xs text-gray-400">បែងចែកតាមដំណាក់កាលការងារ</p>
            </div>
            <div class="h-56 w-full flex items-center justify-center relative">
                <canvas id="repairsPieChart"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs pt-4 border-t border-gray-100 dark:border-slate-700">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <span class="text-gray-600 dark:text-gray-300">Pending: <b>{{ $pendingRepairs }}</b></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    <span class="text-gray-600 dark:text-gray-300">Progress: <b>{{ $inProgressRepairs }}</b></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-gray-600 dark:text-gray-300">Completed: <b>{{ $completedRepairs }}</b></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                    <span class="text-gray-600 dark:text-gray-300">Delivered: <b>{{ $deliveredRepairs }}</b></span>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Data & Shortcuts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 relative z-10">

        <!-- Left 2 Cols: Recent Sales & Recent Purchases Tabs -->
        <div class="lg:col-span-2 space-y-6 stagger-card" x-data="{ tab: 'sales' }">
            <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl rounded-3xl border border-gray-100 dark:border-slate-700/80 shadow-sm overflow-hidden card-3d">
                <!-- Tabs Header -->
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <button @click="tab = 'sales'" :class="tab === 'sales' ? 'bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="px-3.5 py-1.5 rounded-xl text-xs transition">
                            <i class="fa-solid fa-receipt mr-1"></i> ការលក់ថ្មីៗ (Sales)
                        </button>
                        <button @click="tab = 'purchases'" :class="tab === 'purchases' ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200'" class="px-3.5 py-1.5 rounded-xl text-xs transition">
                            <i class="fa-solid fa-truck-ramp-box mr-1"></i> ទិញចូលថ្មីៗ (Purchases)
                        </button>
                    </div>
                    <a :href="tab === 'sales' ? '{{ route('sales.index') }}' : '{{ route('purchases.index') }}'" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                        មើលទាំងអស់ →
                    </a>
                </div>

                <!-- Recent Sales Tab Table -->
                <div x-show="tab === 'sales'" class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-gray-50/60 dark:bg-slate-800/50 text-gray-400 uppercase text-[11px] font-bold border-b border-gray-100 dark:border-slate-700">
                                <th class="py-3 px-6">Invoice</th>
                                <th class="py-3 px-6">អតិថិជន</th>
                                <th class="py-3 px-6">សរុប ($)</th>
                                <th class="py-3 px-6 text-right">ស្ថានភាព</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-200">
                            @forelse($recentSales as $sale)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/50 transition">
                                <td class="py-3.5 px-6 font-mono font-bold text-blue-600 dark:text-blue-400">
                                    <a href="{{ route('sales.show', $sale->sale_id) }}" class="hover:underline">#{{ $sale->invoice_no }}</a>
                                </td>
                                <td class="py-3.5 px-6 font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $sale->customer->name ?? $sale->customer->customer_name ?? 'Walk-in Customer' }}
                                </td>
                                <td class="py-3.5 px-6 font-bold text-emerald-600 dark:text-emerald-400">
                                    ${{ number_format($sale->total_amount, 2) }}
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ strtoupper($sale->payment_status) === 'PAID' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300' }}">
                                        {{ strtoupper($sale->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-400">មិនទាន់មានការលក់នៅឡើយ</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Recent Purchases Tab Table -->
                <div x-show="tab === 'purchases'" x-cloak class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-gray-50/60 dark:bg-slate-800/50 text-gray-400 uppercase text-[11px] font-bold border-b border-gray-100 dark:border-slate-700">
                                <th class="py-3 px-6">PO Number</th>
                                <th class="py-3 px-6">អ្នកផ្គត់ផ្គង់</th>
                                <th class="py-3 px-6">សរុប ($)</th>
                                <th class="py-3 px-6 text-right">ស្ថានភាព</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-200">
                            @forelse($recentPurchases as $po)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/50 transition">
                                <td class="py-3.5 px-6 font-mono font-bold text-indigo-600 dark:text-indigo-400">
                                    <a href="{{ route('purchases.show', $po->purchase_id) }}" class="hover:underline">#{{ $po->purchase_no }}</a>
                                </td>
                                <td class="py-3.5 px-6 font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $po->supplier->supplier_name ?? 'N/A' }}
                                </td>
                                <td class="py-3.5 px-6 font-bold text-indigo-600 dark:text-indigo-400">
                                    ${{ number_format($po->total_amount, 2) }}
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ strtoupper($po->payment_status) === 'PAID' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300' }}">
                                        {{ strtoupper($po->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-400">មិនទាន់មានការទិញចូលនៅឡើយ</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- Right Col: Shortcuts & Low Stock -->
        <div class="space-y-6">
            <!-- Quick Actions Panel -->
            <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl rounded-3xl border border-gray-100 dark:border-slate-700/80 shadow-sm p-5 space-y-3 card-3d stagger-card">
                <h3 class="font-bold text-gray-800 dark:text-gray-100 text-sm">ផ្លូវកាត់រហ័ស (Quick Actions)</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('pos.index') }}" class="p-3.5 bg-gray-50/80 dark:bg-slate-700/50 hover:bg-blue-50 dark:hover:bg-blue-950/40 hover:border-blue-200 border border-gray-100 dark:border-slate-700 rounded-2xl flex flex-col items-center justify-center text-center transition group">
                        <i class="fa-solid fa-cash-register text-2xl text-blue-600 dark:text-blue-400 mb-1.5 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">ផ្ទាំងលក់ POS</span>
                    </a>
                    <a href="{{ route('purchases.create') }}" class="p-3.5 bg-gray-50/80 dark:bg-slate-700/50 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 hover:border-indigo-200 border border-gray-100 dark:border-slate-700 rounded-2xl flex flex-col items-center justify-center text-center transition group">
                        <i class="fa-solid fa-truck-ramp-box text-2xl text-indigo-600 dark:text-indigo-400 mb-1.5 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">ទិញចូលស្តុក</span>
                    </a>
                    <a href="{{ route('repairs.create') }}" class="p-3.5 bg-gray-50/80 dark:bg-slate-700/50 hover:bg-amber-50 dark:hover:bg-amber-950/40 hover:border-amber-200 border border-gray-100 dark:border-slate-700 rounded-2xl flex flex-col items-center justify-center text-center transition group">
                        <i class="fa-solid fa-screwdriver-wrench text-2xl text-amber-600 dark:text-amber-400 mb-1.5 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">ទទួលជួសជុល</span>
                    </a>
                    <a href="{{ route('expenses.index') }}" class="p-3.5 bg-gray-50/80 dark:bg-slate-700/50 hover:bg-rose-50 dark:hover:bg-rose-950/40 hover:border-rose-200 border border-gray-100 dark:border-slate-700 rounded-2xl flex flex-col items-center justify-center text-center transition group">
                        <i class="fa-solid fa-wallet text-2xl text-rose-600 dark:text-rose-400 mb-1.5 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">កត់ត្រាចំណាយ</span>
                    </a>
                </div>
            </div>

            <!-- Low Stock Mini List -->
            @if($lowStockProducts->count() > 0)
            <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl rounded-3xl border border-rose-100 dark:border-rose-900/50 shadow-sm p-5 card-3d stagger-card">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-triangle-exclamation"></i> ទំនិញជិតអស់ពីស្តុក
                    </h4>
                    <span class="text-[11px] font-bold text-rose-500 bg-rose-50 dark:bg-rose-950/50 px-2 py-0.5 rounded-full">{{ $lowStockProducts->count() }} មុខ</span>
                </div>
                <div class="space-y-2.5">
                    @foreach($lowStockProducts->take(4) as $item)
                    <div class="flex items-center justify-between text-xs pb-2 border-b border-gray-50 dark:border-slate-700 last:border-0 last:pb-0">
                        <span class="font-medium text-gray-700 dark:text-gray-300 truncate max-w-[170px]">{{ $item->product_name }}</span>
                        <span class="font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/50 px-2 py-0.5 rounded-md">សល់ {{ $item->stock_quantity }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

    </div>

</div>

<!-- Animation Styles & Script Engine -->
<style>
    /* 🪄 3D Interactive Card Tilt Effect */
    .card-3d {
        transition: transform 0.3s cubic-bezier(0.25, 1, 0.5, 1), box-shadow 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        will-change: transform;
    }
    .card-3d:hover {
        transform: translateY(-5px) scale(1.01);
        box-shadow: 0 15px 30px -10px rgba(37, 99, 235, 0.15);
    }

    /* 🌌 Background Pulse */
    @keyframes pulseSlow {
        0%, 100% { opacity: 0.4; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.1); }
    }
    .animate-pulse-slow {
        animation: pulseSlow 8s ease-in-out infinite;
    }

    /* Card Stagger Entrance */
    @keyframes cardStagger {
        0% { opacity: 0; transform: translateY(15px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .stagger-card {
        animation: cardStagger 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
    }

    .stagger-card:nth-child(1) { animation-delay: 0.05s; }
    .stagger-card:nth-child(2) { animation-delay: 0.1s; }
    .stagger-card:nth-child(3) { animation-delay: 0.15s; }
    .stagger-card:nth-child(4) { animation-delay: 0.2s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 🔢 Count-up Animation Effect
    const countElements = document.querySelectorAll('.count-up');
    countElements.forEach(el => {
        const target = parseFloat(el.getAttribute('data-target')) || 0;
        const prefix = el.getAttribute('data-prefix') || '';
        const decimals = parseInt(el.getAttribute('data-decimals')) || 0;
        const duration = 1200;
        const steps = 40;
        const stepTime = duration / steps;
        let currentStep = 0;

        const timer = setInterval(() => {
            currentStep++;
            const progress = currentStep / steps;
            const easeProgress = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const currentVal = target * easeProgress;

            el.textContent = prefix + currentVal.toLocaleString('en-US', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });

            if (currentStep >= steps) {
                clearInterval(timer);
                el.textContent = prefix + target.toLocaleString('en-US', {
                    minimumFractionDigits: decimals,
                    maximumFractionDigits: decimals
                });
            }
        }, stepTime);
    });

    // 📊 Sales vs Purchases vs Expenses Multi-Bar Chart
    const ctxSales = document.getElementById('salesExpenseChart').getContext('2d');
    new Chart(ctxSales, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'ការលក់ ($)',
                    data: {!! json_encode($chartSales) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.85)',
                    borderRadius: 6,
                    barPercentage: 0.7,
                },
                {
                    label: 'ទិញចូល ($)',
                    data: {!! json_encode($chartPurchases) !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.85)',
                    borderRadius: 6,
                    barPercentage: 0.7,
                },
                {
                    label: 'ការចំណាយ ($)',
                    data: {!! json_encode($chartExpenses) !!},
                    backgroundColor: 'rgba(244, 63, 94, 0.85)',
                    borderRadius: 6,
                    barPercentage: 0.7,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1500,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: document.documentElement.classList.contains('dark') ? 'rgba(51, 65, 85, 0.4)' : 'rgba(243, 244, 246, 1)' },
                    ticks: { color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b' }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b' }
                }
            }
        }
    });

    // 🍩 Repairs Doughnut Chart with Smooth Draw Animation
    const ctxRepairs = document.getElementById('repairsPieChart').getContext('2d');
    new Chart(ctxRepairs, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'In Progress', 'Completed', 'Delivered'],
            datasets: [{
                data: [
                    {{ $pendingRepairs }},
                    {{ $inProgressRepairs }},
                    {{ $completedRepairs }},
                    {{ $deliveredRepairs }}
                ],
                backgroundColor: ['#fbbf24', '#3b82f6', '#10b981', '#6366f1'],
                borderWidth: 2,
                borderColor: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 1500
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endsection