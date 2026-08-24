@extends('layouts.app')

@section('title', 'Purchase Orders - POS System')
@section('page_heading', 'Purchases & Stock In')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Purchases & Stock Intake</h2>
            <p class="text-xs sm:text-sm text-gray-500">Track supplier purchase orders, stock arrivals, and supplier balances.</p>
        </div>
        <a href="{{ route('purchases.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition">
            <i class="fa-solid fa-plus mr-2"></i> New Purchase Order
        </a>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Purchases</p>
                <p class="text-2xl font-black text-gray-800 mt-1">${{ number_format($totalPurchases, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Paid</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">${{ number_format($totalPaid, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Due</p>
                <p class="text-2xl font-black text-rose-600 mt-1">${{ number_format($totalDue, 2) }}</p>
            </div>
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">PO Number</th>
                        <th class="py-3.5 px-6">Date</th>
                        <th class="py-3.5 px-6">Supplier</th>
                        <th class="py-3.5 px-6">Purchased By</th>
                        <th class="py-3.5 px-6">Total / Paid</th>
                        <th class="py-3.5 px-6">Payment Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($purchases as $purchase)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3.5 px-6">
                            <span class="font-mono font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100">
                                #{{ $purchase->purchase_no }}
                            </span>
                            <span class="block text-[11px] text-gray-400 mt-1">{{ $purchase->items->count() }} items</span>
                        </td>
                        <td class="py-3.5 px-6">
                            <p class="font-medium text-gray-800">{{ optional($purchase->purchase_date)->format('d M Y') }}</p>
                        </td>
                        <td class="py-3.5 px-6">
                            <p class="font-semibold text-gray-800">{{ $purchase->supplier->supplier_name ?? 'N/A' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $purchase->supplier->phone ?? '' }}</p>
                        </td>
                        <td class="py-3.5 px-6">
                            <span class="text-xs text-gray-600 font-medium">{{ $purchase->user->full_name ?? $purchase->user->username ?? 'Staff' }}</span>
                        </td>
                        <td class="py-3.5 px-6">
                            <p class="font-bold text-gray-800">${{ number_format($purchase->total_amount, 2) }}</p>
                            <p class="text-[11px] text-emerald-600 font-medium">Paid: ${{ number_format($purchase->paid_amount, 2) }}</p>
                            @if($purchase->due_amount > 0)
                                <p class="text-[11px] text-rose-500 font-medium">Due: ${{ number_format($purchase->due_amount, 2) }}</p>
                            @endif
                        </td>
                        <td class="py-3.5 px-6">
                            @if(strtoupper($purchase->payment_status) === 'PAID')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Paid</span>
                            @elseif(strtoupper($purchase->payment_status) === 'PARTIAL')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-100">Partial</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-100">Unpaid</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-right">
                            <a href="{{ route('purchases.edit', $purchase->purchase_id) }}" class="px-4 py-2 bg-amber-50 text-amber-600 hover:bg-amber-100 rounded-xl text-xs font-bold transition inline-flex items-center">
                            <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit PO
                            </a>
                            <a href="{{ route('purchases.show', $purchase->purchase_id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition inline-flex items-center" title="View Purchase Details">
                                <i class="fa-regular fa-eye text-base"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-truck-ramp-box text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm font-medium">No purchase records found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchases->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>

</div>
@endsection