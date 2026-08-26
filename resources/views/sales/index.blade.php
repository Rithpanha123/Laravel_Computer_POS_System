@extends('layouts.app')

@section('title', 'Sales Invoices - POS System')
@section('page_heading', 'Sales & Invoices')

@section('content')
<div class="space-y-6 animate-wave-page relative overflow-hidden pb-10">

    <!-- 💫 Ambient Background Glowing Orbs -->
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-500/10 dark:bg-blue-600/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow"></div>
    <div class="absolute top-1/3 -right-24 w-96 h-96 bg-emerald-500/10 dark:bg-emerald-600/10 rounded-full blur-3xl pointer-events-none animate-pulse-slow" style="animation-delay: 1.5s;"></div>

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 relative z-10">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Sales Transactions</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Track all completed POS orders, invoice payments, and outstanding balances.</p>
        </div>
        <a href="{{ route('pos.index') ?? url('/pos') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-tr from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25 transition btn-pop">
            <i class="fa-solid fa-cash-register mr-2"></i> Open POS Terminal
        </a>
    </div>

    <!-- Summary Metrics with 🪄 3D Cards & 🔢 Count-up -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 relative z-10">
        <!-- Metric 1 -->
        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-5 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center justify-between card-3d stagger-card">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Sales</p>
                <p class="text-2xl font-black text-gray-800 dark:text-gray-100 mt-1 count-up" data-target="{{ $totalRevenue }}" data-prefix="$" data-decimals="2">0.00</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-tr from-blue-500 to-indigo-500 text-white rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <!-- Metric 2 -->
        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-5 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center justify-between card-3d stagger-card">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Paid Amount</p>
                <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1 count-up" data-target="{{ $totalPaid }}" data-prefix="$" data-decimals="2">0.00</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-tr from-emerald-500 to-teal-500 text-white rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-emerald-500/30">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Metric 3 -->
        <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-5 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm flex items-center justify-between card-3d stagger-card">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Due Amount</p>
                <p class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1 count-up" data-target="{{ $totalDue }}" data-prefix="$" data-decimals="2">0.00</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-tr from-rose-500 to-pink-500 text-white rounded-2xl flex items-center justify-center text-xl shadow-lg shadow-rose-500/30">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl p-4 rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm relative z-10 stagger-card">
        <form id="filterForm" method="GET" action="{{ route('sales.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <!-- Hidden per_page input -->
            <input type="hidden" name="per_page" id="formPerPageInput" value="{{ request('per_page', 10) }}">

            <!-- Search Text -->
            <div class="md:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search invoice number, customer..." 
                    class="w-full pl-9 pr-3 py-2 bg-gray-50/80 dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-slate-900 transition"
                >
            </div>

            <!-- Payment Status -->
            <div>
                <select name="payment_status" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2 bg-gray-50/80 dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-slate-900 transition">
                    <option value="">All Payment Status</option>
                    <option value="PAID" {{ strtoupper(request('payment_status')) === 'PAID' ? 'selected' : '' }}>Paid</option>
                    <option value="PARTIAL" {{ strtoupper(request('payment_status')) === 'PARTIAL' ? 'selected' : '' }}>Partial</option>
                    <option value="UNPAID" {{ strtoupper(request('payment_status')) === 'UNPAID' ? 'selected' : '' }}>Unpaid</option>
                </select>
            </div>

            <!-- Date Range Filter -->
            <div>
                <input 
                    type="date" 
                    name="from_date" 
                    value="{{ request('from_date') }}" 
                    class="w-full px-3 py-2 bg-gray-50/80 dark:bg-slate-900/80 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-slate-900 transition"
                    title="From Date"
                >
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full py-2 bg-slate-900 dark:bg-slate-700 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'payment_status', 'from_date', 'per_page']))
                    <a href="{{ route('sales.index') }}" class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-xl text-xs flex items-center justify-center transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Sales Table -->
    <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur-xl rounded-2xl border border-gray-100 dark:border-slate-700/80 shadow-sm overflow-hidden relative z-10 stagger-card">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">Invoice No</th>
                        <th class="py-3.5 px-6">Date</th>
                        <th class="py-3.5 px-6">Customer</th>
                        <th class="py-3.5 px-6">Cashier / Staff</th>
                        <th class="py-3.5 px-6">Total / Paid</th>
                        <th class="py-3.5 px-6">Payment Status</th>
                        <th class="py-3.5 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-200">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-slate-700/50 transition">
                        <!-- Invoice -->
                        <td class="py-3.5 px-6">
                            <span class="font-mono font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/50 px-2.5 py-1 rounded-lg border border-blue-100 dark:border-blue-900/50">
                                #{{ $sale->invoice_no }}
                            </span>
                            <span class="block text-[11px] text-gray-400 mt-1">{{ $sale->items->count() }} items</span>
                        </td>

                        <!-- Date -->
                        <td class="py-3.5 px-6">
                            <p class="font-medium text-gray-800 dark:text-gray-100">{{ optional($sale->sale_date)->format('d M Y') ?? 'N/A' }}</p>
                            <p class="text-[11px] text-gray-400">{{ optional($sale->sale_date)->format('h:i A') }}</p>
                        </td>

                        <!-- Customer -->
                        <td class="py-3.5 px-6">
                            <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $sale->customer->customer_name ?? $sale->customer->name ?? 'Walk-in Customer' }}</p>
                            @if(optional($sale->customer)->phone)
                                <p class="text-[11px] text-gray-400">{{ $sale->customer->phone }}</p>
                            @endif
                        </td>

                        <!-- Cashier -->
                        <td class="py-3.5 px-6">
                            <span class="text-xs text-gray-600 dark:text-gray-300 font-medium">{{ $sale->user->full_name ?? $sale->user->username ?? 'System' }}</span>
                        </td>

                        <!-- Financials -->
                        <td class="py-3.5 px-6">
                            <p class="font-bold text-gray-800 dark:text-gray-100">${{ number_format($sale->total_amount, 2) }}</p>
                            <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">Paid: ${{ number_format($sale->paid_amount, 2) }}</p>
                            @if($sale->due_amount > 0)
                                <p class="text-[11px] text-rose-500 font-medium">Due: ${{ number_format($sale->due_amount, 2) }}</p>
                            @endif
                        </td>

                        <!-- Payment Status -->
                        <td class="py-3.5 px-6">
                            @if(strtoupper($sale->payment_status) === 'PAID')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                </span>
                            @elseif(strtoupper($sale->payment_status) === 'PARTIAL')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300 border border-amber-100 dark:border-amber-900">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Partial
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 dark:bg-rose-950/50 dark:text-rose-300 border border-rose-100 dark:border-rose-900">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Unpaid
                                </span>
                            @endif
                        </td>

                        <!-- Action -->
                        <td class="py-3.5 px-6 text-right">
                            <a href="{{ route('sales.show', $sale->sale_id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-xl transition inline-flex items-center" title="View Invoice">
                                <i class="fa-regular fa-eye text-base"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-receipt text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm font-medium">No sales transactions found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500 dark:text-gray-400">
            <div>
                បង្ហាញពី <span class="font-bold text-gray-700 dark:text-gray-200">{{ $sales->firstItem() ?? 0 }}</span> ដល់ <span class="font-bold text-gray-700 dark:text-gray-200">{{ $sales->lastItem() ?? 0 }}</span> នៃទិន្នន័យសរុប <span class="font-bold text-gray-700 dark:text-gray-200">{{ $sales->total() }}</span> ជួរ
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Dropdown Selector -->
                <div class="flex items-center gap-1.5 whitespace-nowrap bg-white dark:bg-slate-800 px-3 py-1 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
                    <span class="text-gray-500 font-medium">បង្ហាញ៖</span>
                    <select 
                        id="perPageSelectDropdown" 
                        onchange="changePerPage(this.value)" 
                        class="bg-transparent border-none text-xs font-bold text-gray-800 dark:text-gray-200 outline-none cursor-pointer focus:ring-0 py-0.5 pr-6 pl-1"
                    >
                        <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-gray-500 font-medium">ជួរ</span>
                </div>

                <!-- Page Links -->
                <div>
                    {{ $sales->links() }}
                </div>
            </div>
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
        transform: translateY(-4px) scale(1.01);
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
function changePerPage(value) {
    document.getElementById('formPerPageInput').value = value;
    document.getElementById('filterForm').submit();
}

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
});
</script>
@endsection