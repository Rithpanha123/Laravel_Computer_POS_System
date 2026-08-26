@extends('layouts.app')

@section('title', 'Sales Invoices - POS System')
@section('page_heading', 'Sales & Invoices')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Sales Transactions</h2>
            <p class="text-xs sm:text-sm text-gray-500">Track all completed POS orders, invoice payments, and outstanding balances.</p>
        </div>
        <a href="{{ route('pos.index') ?? url('/pos') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition">
            <i class="fa-solid fa-cash-register mr-2"></i> Open POS Terminal
        </a>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Sales</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-receipt"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Paid Amount</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">${{ number_format($totalPaid, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Due Amount</p>
                <p class="text-2xl font-bold text-rose-600 mt-1">${{ number_format($totalDue, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
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
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                >
            </div>

            <!-- Payment Status -->
            <div>
                <select name="payment_status" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
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
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                    title="From Date"
                >
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'payment_status', 'from_date', 'per_page']))
                    <a href="{{ route('sales.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl text-xs flex items-center justify-center transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Sales Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">Invoice No</th>
                        <th class="py-3.5 px-6">Date</th>
                        <th class="py-3.5 px-6">Customer</th>
                        <th class="py-3.5 px-6">Cashier / Staff</th>
                        <th class="py-3.5 px-6">Total / Paid</th>
                        <th class="py-3.5 px-6">Payment Status</th>
                        <th class="py-3.5 px-6 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($sales as $sale)
                    <tr class="hover:bg-gray-50/60 transition">
                        <!-- Invoice -->
                        <td class="py-3.5 px-6">
                            <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">
                                #{{ $sale->invoice_no }}
                            </span>
                            <span class="block text-[11px] text-gray-400 mt-1">{{ $sale->items->count() }} items</span>
                        </td>

                        <!-- Date -->
                        <td class="py-3.5 px-6">
                            <p class="font-medium text-gray-800">{{ optional($sale->sale_date)->format('d M Y') ?? 'N/A' }}</p>
                            <p class="text-[11px] text-gray-400">{{ optional($sale->sale_date)->format('h:i A') }}</p>
                        </td>

                        <!-- Customer -->
                        <td class="py-3.5 px-6">
                            <p class="font-semibold text-gray-800">{{ $sale->customer->customer_name ?? $sale->customer->name ?? 'Walk-in Customer' }}</p>
                            @if(optional($sale->customer)->phone)
                                <p class="text-[11px] text-gray-400">{{ $sale->customer->phone }}</p>
                            @endif
                        </td>

                        <!-- Cashier -->
                        <td class="py-3.5 px-6">
                            <span class="text-xs text-gray-600 font-medium">{{ $sale->user->full_name ?? $sale->user->username ?? 'System' }}</span>
                        </td>

                        <!-- Financials -->
                        <td class="py-3.5 px-6">
                            <p class="font-bold text-gray-800">${{ number_format($sale->total_amount, 2) }}</p>
                            <p class="text-[11px] text-emerald-600 font-medium">Paid: ${{ number_format($sale->paid_amount, 2) }}</p>
                            @if($sale->due_amount > 0)
                                <p class="text-[11px] text-rose-500 font-medium">Due: ${{ number_format($sale->due_amount, 2) }}</p>
                            @endif
                        </td>

                        <!-- Payment Status -->
                        <td class="py-3.5 px-6">
                            @if(strtoupper($sale->payment_status) === 'PAID')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Paid
                                </span>
                            @elseif(strtoupper($sale->payment_status) === 'PARTIAL')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Partial
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Unpaid
                                </span>
                            @endif
                        </td>

                        <!-- Action -->
                        <td class="py-3.5 px-6 text-right">
                            <a href="{{ route('sales.show', $sale->sale_id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition inline-flex items-center" title="View Invoice">
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
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <div>
                បង្ហាញពី <span class="font-bold text-gray-700">{{ $sales->firstItem() ?? 0 }}</span> ដល់ <span class="font-bold text-gray-700">{{ $sales->lastItem() ?? 0 }}</span> នៃទិន្នន័យសរុប <span class="font-bold text-gray-700">{{ $sales->total() }}</span> ជួរ
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Dropdown Selector -->
                <div class="flex items-center gap-1.5 whitespace-nowrap bg-white px-3 py-1 rounded-xl border border-gray-200 shadow-sm">
                    <span class="text-gray-500 font-medium">បង្ហាញ៖</span>
                    <select 
                        id="perPageSelectDropdown" 
                        onchange="changePerPage(this.value)" 
                        class="bg-transparent border-none text-xs font-bold text-gray-800 outline-none cursor-pointer focus:ring-0 py-0.5 pr-6 pl-1"
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

<script>
function changePerPage(value) {
    document.getElementById('formPerPageInput').value = value;
    document.getElementById('filterForm').submit();
}
</script>
@endsection