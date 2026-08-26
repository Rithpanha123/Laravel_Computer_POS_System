@extends('layouts.app')

@section('title', 'Purchase Orders - POS System')
@section('page_heading', 'Purchases & Stock In')

@section('content')
<!-- Custom CSS for specialized animations and animated gradient background -->
<style>
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animated-gradient-bg {
        background: linear-gradient(-45deg, rgba(59, 130, 246, 0.06), rgba(99, 102, 241, 0.06), rgba(139, 92, 246, 0.06), rgba(236, 72, 153, 0.06));
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
    }
    .dark .animated-gradient-bg {
        background: linear-gradient(-45deg, rgba(59, 130, 246, 0.03), rgba(99, 102, 241, 0.03), rgba(139, 92, 246, 0.03), rgba(236, 72, 153, 0.03));
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
    }
    .glow-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glow-card:hover {
        transform: translateY(-4px) scale(1.01);
        box-shadow: 0 20px 30px -10px rgba(59, 130, 246, 0.15);
    }
    .magnetic-btn {
        transition: transform 0.2s cubic-bezier(0.2, 0, 0, 1), box-shadow 0.2s ease;
    }
    .magnetic-btn:hover {
        transform: translateY(-2px);
    }
</style>

<div class="space-y-6 p-4 sm:p-6 rounded-3xl animated-gradient-bg">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Purchases & Stock Intake</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Track supplier purchase orders, stock arrivals, and supplier balances.</p>
        </div>
        <a href="{{ route('purchases.create') }}" class="magnetic-btn inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25 transition">
            <i class="fa-solid fa-plus mr-2"></i> New Purchase Order
        </a>
    </div>

    <!-- Summary Metrics with Glowing & Hover Effects -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <!-- Total Purchases Card -->
        <div class="glow-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-full group-hover:scale-150 transition duration-500 pointer-events-none opacity-50"></div>
            <div class="relative z-10">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-400">Total Purchases</p>
                <p class="text-2xl font-black text-gray-800 dark:text-white mt-1">${{ number_format($totalPurchases, 2) }}</p>
            </div>
            <div class="relative z-10 w-12 h-12 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
        </div>

        <!-- Total Paid Card -->
        <div class="glow-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-full group-hover:scale-150 transition duration-500 pointer-events-none opacity-50"></div>
            <div class="relative z-10">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-400">Total Paid</p>
                <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">${{ number_format($totalPaid, 2) }}</p>
            </div>
            <div class="relative z-10 w-12 h-12 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Total Due Card -->
        <div class="glow-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between relative overflow-hidden group">
            <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-rose-50 dark:bg-rose-900/20 rounded-full group-hover:scale-150 transition duration-500 pointer-events-none opacity-50"></div>
            <div class="relative z-10">
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-400">Total Due</p>
                <p class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1">${{ number_format($totalDue, 2) }}</p>
            </div>
            <div class="relative z-10 w-12 h-12 bg-rose-50 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm">
        <form id="filterForm" method="GET" action="{{ route('purchases.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
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
                    placeholder="Search PO number, supplier..." 
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-slate-900 transition"
                >
            </div>

            <!-- Payment Status -->
            <div>
                <select name="payment_status" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-slate-900 transition">
                    <option value="">All Status</option>
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
                    class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-slate-900 transition"
                    title="From Date"
                >
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="magnetic-btn w-full py-2 bg-slate-900 hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition shadow-md">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'payment_status', 'from_date', 'to_date', 'per_page']))
                    <a href="{{ route('purchases.index') }}" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-xl text-xs flex items-center justify-center transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Purchases Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">PO Number</th>
                        <th class="py-3.5 px-6">Date</th>
                        <th class="py-3.5 px-6">Supplier</th>
                        <th class="py-3.5 px-6">Purchased By</th>
                        <th class="py-3.5 px-6">Total / Paid</th>
                        <th class="py-3.5 px-6">Payment Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                    @forelse($purchases as $purchase)
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/50 transition duration-150">
                        <td class="py-3.5 px-6">
                            <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 px-2.5 py-1 rounded-lg border border-indigo-100 dark:border-indigo-900">
                                #{{ $purchase->purchase_no }}
                            </span>
                            <span class="block text-[11px] text-gray-400 mt-1">{{ $purchase->items->count() }} items</span>
                        </td>
                        <td class="py-3.5 px-6">
                            <p class="font-medium text-gray-800 dark:text-gray-200">{{ optional($purchase->purchase_date)->format('d M Y') }}</p>
                        </td>
                        <td class="py-3.5 px-6">
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $purchase->supplier->supplier_name ?? $purchase->supplier->name ?? 'N/A' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $purchase->supplier->phone ?? '' }}</p>
                        </td>
                        <td class="py-3.5 px-6">
                            <span class="text-xs text-gray-600 dark:text-gray-400 font-medium">{{ $purchase->user->full_name ?? $purchase->user->username ?? 'Staff' }}</span>
                        </td>
                        <td class="py-3.5 px-6">
                            <p class="font-bold text-gray-800 dark:text-gray-200">${{ number_format($purchase->total_amount, 2) }}</p>
                            <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">Paid: ${{ number_format($purchase->paid_amount, 2) }}</p>
                            @if($purchase->due_amount > 0)
                                <p class="text-[11px] text-rose-500 dark:text-rose-400 font-medium">Due: ${{ number_format($purchase->due_amount, 2) }}</p>
                            @endif
                        </td>
                        <td class="py-3.5 px-6">
                            @if(strtoupper($purchase->payment_status) === 'PAID')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900">Paid</span>
                            @elseif(strtoupper($purchase->payment_status) === 'PARTIAL')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-100 dark:border-amber-900">Partial</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-100 dark:border-rose-900">Unpaid</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-right space-x-1">
                            <a href="{{ route('purchases.edit', $purchase->purchase_id) }}" class="p-2 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/30 rounded-xl transition inline-flex items-center" title="Edit Purchase">
                                <i class="fa-regular fa-pen-to-square text-base"></i>
                            </a>
                            <a href="{{ route('purchases.show', $purchase->purchase_id) }}" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-xl transition inline-flex items-center" title="View Purchase Details">
                                <i class="fa-regular fa-eye text-base"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-truck-ramp-box text-3xl text-gray-300 dark:text-slate-600 mb-2 animate-bounce"></i>
                            <p class="text-sm font-medium">No purchase records found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer with Per Page Selector & Counter -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500 dark:text-gray-400">
            <div>
                បង្ហាញពី <span class="font-bold text-gray-700 dark:text-gray-200">{{ $purchases->firstItem() ?? 0 }}</span> ដល់ <span class="font-bold text-gray-700 dark:text-gray-200">{{ $purchases->lastItem() ?? 0 }}</span> នៃទិន្នន័យសរុប <span class="font-bold text-gray-700 dark:text-gray-200">{{ $purchases->total() }}</span> ជួរ
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Dropdown Selector -->
                <div class="flex items-center gap-1.5 whitespace-nowrap bg-white dark:bg-slate-800 px-3 py-1 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">បង្ហាញ៖</span>
                    <select 
                        id="perPageSelectDropdown" 
                        onchange="changePerPage(this.value)" 
                        class="bg-transparent border-none text-xs font-bold text-gray-800 dark:text-white outline-none cursor-pointer focus:ring-0 py-0.5 pr-6 pl-1"
                    >
                        <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-gray-500 dark:text-gray-400 font-medium">ជួរ</span>
                </div>

                <!-- Page Links -->
                <div>
                    {{ $purchases->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

<!-- JavaScript to handle Per Page selection -->
<script>
function changePerPage(value) {
    document.getElementById('formPerPageInput').value = value;
    document.getElementById('filterForm').submit();
}
</script>
@endsection