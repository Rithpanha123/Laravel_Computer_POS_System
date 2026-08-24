@extends('layouts.app')

@section('title', 'ផ្ទាំងគ្រប់គ្រងទូទៅ - POS System')
@section('page_heading', 'Dashboard Overview')

@section('content')
<div class="space-y-6">

    <!-- Top Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Today Sales -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">ការលក់ថ្ងៃនេះ (Sales Today)</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1">${{ number_format($todaySales, 2) }}</h3>
                <p class="text-[11px] font-medium {{ $salesGrowth >= 0 ? 'text-emerald-600' : 'text-rose-500' }} mt-1 flex items-center">
                    <i class="fa-solid {{ $salesGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1"></i>
                    {{ $salesGrowth >= 0 ? '+' : '' }}{{ $salesGrowth }}% ធៀបម្សិលមិញ
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
        </div>

        <!-- Card 2: Today Orders -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">វិក្កយបត្រថ្ងៃនេះ (Orders)</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1">{{ $todayOrdersCount }}</h3>
                <p class="text-[11px] font-medium text-emerald-600 mt-1 flex items-center">
                    <i class="fa-solid fa-circle-check mr-1"></i> បានបញ្ចប់ការទូទាត់
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
        </div>

        <!-- Card 3: Low Stock Alert -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">ជិតអស់ពីស្តុក (Low Stock)</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $lowStockCount }} មុខ</h3>
                <p class="text-[11px] font-medium text-amber-600 mt-1 flex items-center">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> ត្រូវបញ្ជាទិញបន្ថែម
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-box-open"></i>
            </div>
        </div>

        <!-- Card 4: Total Inventory Items -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">មុខទំនិញសរុប (Products)</p>
                <h3 class="text-2xl font-black text-indigo-600 mt-1">{{ $totalProducts }}</h3>
                <p class="text-[11px] font-medium text-indigo-600 mt-1 flex items-center">
                    <i class="fa-solid fa-users mr-1"></i> អតិថិជនសរុប៖ {{ $totalCustomers }} នាក់
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-cubes"></i>
            </div>
        </div>
    </div>

    <!-- Main Grid: Recent Sales & Quick Shortcuts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Recent Transactions (2 Columns) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-800 text-sm">វិក្កយបត្រលក់ថ្មីៗ (Recent Sales)</h3>
                    <p class="text-[11px] text-gray-400">ប្រតិបត្តិការលក់ចុងក្រោយបង្អស់</p>
                </div>
                <a href="{{ route('sales.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">មើលទាំងអស់ →</a>
            </div>

            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-gray-50/60 text-gray-400 uppercase text-[11px] font-bold border-b border-gray-100">
                            <th class="py-3 px-6">Invoice</th>
                            <th class="py-3 px-6">អតិថិជន</th>
                            <th class="py-3 px-6">ទំនិញ</th>
                            <th class="py-3 px-6">សរុប ($)</th>
                            <th class="py-3 px-6 text-right">ស្ថានភាព</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($recentSales as $sale)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3.5 px-6 font-mono font-bold text-blue-600">
                                <a href="{{ route('sales.show', $sale->sale_id) }}" class="hover:underline">#{{ $sale->invoice_no }}</a>
                            </td>
                            <td class="py-3.5 px-6 font-semibold text-gray-800">
                                {{ $sale->customer->name ?? 'Walk-in Customer' }}
                            </td>
                            <td class="py-3.5 px-6 text-gray-500 max-w-[180px] truncate" title="{{ $sale->items->pluck('product.product_name')->implode(', ') }}">
                                {{ $sale->items->pluck('product.product_name')->implode(', ') ?: 'N/A' }}
                            </td>
                            <td class="py-3.5 px-6 font-bold text-emerald-600">
                                ${{ number_format($sale->total_amount, 2) }}
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                @if(strtoupper($sale->payment_status) === 'PAID')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Paid</span>
                                @elseif(strtoupper($sale->payment_status) === 'PARTIAL')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100">Partial</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100">Unpaid</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">មិនទាន់មានការលក់នៅឡើយ</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column: Quick Shortcuts & Low Stock Warning -->
        <div class="space-y-6">
            <!-- Quick Actions Panel -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 space-y-3">
                <h3 class="font-bold text-gray-800 text-sm">ផ្លូវកាត់រហ័ស (Quick Actions)</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('pos.index') }}" class="p-3.5 bg-gray-50 hover:bg-blue-50 hover:border-blue-200 border border-gray-100 rounded-xl flex flex-col items-center justify-center text-center transition group">
                        <i class="fa-solid fa-cash-register text-2xl text-blue-600 mb-1.5 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-gray-700">ផ្ទាំងលក់ POS</span>
                    </a>
                    <a href="{{ route('products.create') }}" class="p-3.5 bg-gray-50 hover:bg-emerald-50 hover:border-emerald-200 border border-gray-100 rounded-xl flex flex-col items-center justify-center text-center transition group">
                        <i class="fa-solid fa-box text-2xl text-emerald-600 mb-1.5 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-gray-700">ថែមទំនិញ</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="p-3.5 bg-gray-50 hover:bg-purple-50 hover:border-purple-200 border border-gray-100 rounded-xl flex flex-col items-center justify-center text-center transition group">
                        <i class="fa-solid fa-boxes-stacked text-2xl text-purple-600 mb-1.5 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-gray-700">បញ្ជីស្តុក</span>
                    </a>
                    <a href="{{ route('sales.index') }}" class="p-3.5 bg-gray-50 hover:bg-amber-50 hover:border-amber-200 border border-gray-100 rounded-xl flex flex-col items-center justify-center text-center transition group">
                        <i class="fa-solid fa-receipt text-2xl text-amber-600 mb-1.5 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-gray-700">វិក្កយបត្រ</span>
                    </a>
                </div>
            </div>

            <!-- Low Stock Mini List -->
            @if($lowStockProducts->count() > 0)
            <div class="bg-white rounded-2xl border border-rose-100 shadow-sm p-5">
                <h4 class="text-xs font-bold uppercase tracking-wider text-rose-600 flex items-center gap-1.5 mb-3">
                    <i class="fa-solid fa-triangle-exclamation"></i> ទំនិញជិតអស់ពីស្តុក
                </h4>
                <div class="space-y-2.5">
                    @foreach($lowStockProducts->take(4) as $item)
                    <div class="flex items-center justify-between text-xs pb-2 border-b border-gray-50 last:border-0 last:pb-0">
                        <span class="font-medium text-gray-700 truncate max-w-[170px]">{{ $item->product_name }}</span>
                        <span class="font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md">សល់ {{ $item->stock_quantity }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

    </div>

</div>
@endsection