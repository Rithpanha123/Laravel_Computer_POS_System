@extends('layouts.app')

@section('title', 'PO #' . $purchase->purchase_no . ' - POS System')
@section('page_heading', 'Purchase Order Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Action Bar (No Print) -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('purchases.index') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ទៅបញ្ជីទិញចូល
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('purchases.create') }}" class="px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-xl text-xs font-bold transition inline-flex items-center">
                <i class="fa-solid fa-plus mr-1.5"></i> ទិញចូលថ្មី
            </a>
            <!-- Export PDF -->
    <a href="{{ route('purchases.pdf', $purchase->purchase_id) }}" class="px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl text-xs font-bold transition inline-flex items-center">
        <i class="fa-solid fa-file-pdf mr-1.5"></i> Export PDF
    </a>

    <!-- Export Excel -->
    <a href="{{ route('purchases.excel', $purchase->purchase_id) }}" class="px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-xl text-xs font-bold transition inline-flex items-center">
        <i class="fa-solid fa-file-excel mr-1.5"></i> Export Excel
    </a>

    <button onclick="window.print()" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center">
        <i class="fa-solid fa-print mr-1.5"></i> Print
    </button>
        </div>
    </div>

    <!-- Printable Purchase Order Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-10 space-y-8 print-container">
        
        <!-- Header & Store Info -->
        <div class="flex flex-col sm:flex-row justify-between items-start pb-6 border-b border-gray-100 gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-800 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-truck-ramp-box text-indigo-600"></i> COMPUTER STORE & POS
                </h1>
                <p class="text-xs text-gray-500 mt-1">ប័ណ្ណបញ្ជាទិញ & នាំចូលស្តុក (Purchase Order / Goods Receipt)</p>
                <p class="text-xs text-gray-400 mt-0.5">អាសយដ្ឋាន៖ រាជធានីភ្នំពេញ | ទូរស័ព្ទ៖ 012 345 678</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="inline-block font-mono font-black text-sm text-indigo-600 bg-indigo-50 px-3 py-1 rounded-xl border border-indigo-100">
                    #{{ $purchase->purchase_no }}
                </span>
                <p class="text-xs text-gray-500 mt-2">កាលបរិច្ឆេទបញ្ជាទិញ៖ <span class="font-medium text-gray-800">{{ optional($purchase->purchase_date)->format('d M Y, h:i A') }}</span></p>
                <p class="text-xs text-gray-500 mt-0.5">អ្នកទិញចូល (Purchased By)៖ <span class="font-medium text-gray-800">{{ $purchase->user->full_name ?? $purchase->user->username ?? 'Staff' }}</span></p>
            </div>
        </div>

        <!-- Supplier & Payment Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-gray-50/70 p-5 rounded-2xl border border-gray-100">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">អ្នកផ្គត់ផ្គង់ (Supplier Info)</p>
                <p class="text-sm font-bold text-gray-800">{{ $purchase->supplier->supplier_name ?? $purchase->supplier->name ?? 'N/A' }}</p>
                <p class="text-xs text-gray-600 mt-0.5"><i class="fa-solid fa-phone mr-1.5 text-gray-400"></i>{{ $purchase->supplier->phone ?? 'គ្មានលេខទូរស័ព្ទ' }}</p>
                @if($purchase->supplier->email ?? false)
                    <p class="text-xs text-gray-500 mt-0.5"><i class="fa-solid fa-envelope mr-1.5 text-gray-400"></i>{{ $purchase->supplier->email }}</p>
                @endif
                @if($purchase->supplier->address ?? false)
                    <p class="text-xs text-gray-500 mt-0.5"><i class="fa-solid fa-location-dot mr-1.5 text-gray-400"></i>{{ $purchase->supplier->address }}</p>
                @endif
            </div>

            <div class="sm:text-right">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">ស្ថានភាពទូទាត់ប្រាក់ (Payment Status)</p>
                <div class="mt-1">
                    @if(strtoupper($purchase->payment_status) === 'PAID')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> បានទូទាត់គ្រប់ចំនួន (Paid)
                        </span>
                    @elseif(strtoupper($purchase->payment_status) === 'PARTIAL')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> នៅខ្វះខ្លះ (Partial)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span> មិនទាន់ទូទាត់ (Unpaid)
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-2">វិធីសាស្ត្រទូទាត់៖ <span class="font-bold text-gray-700 uppercase">{{ $purchase->payment_method ?? 'Bank / Cash' }}</span></p>
            </div>
        </div>

        <!-- Purchased Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">មុខទំនិញ / ការពិពណ៌នា</th>
                        <th class="py-3 px-4 text-center">ចំនួនទិញចូល (Qty)</th>
                        <th class="py-3 px-4 text-right">ថ្លៃដើមរាយ ($)</th>
                        <th class="py-3 px-4 text-right">សរុប ($)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($purchase->items as $index => $item)
                    <tr>
                        <td class="py-3.5 px-4 text-gray-400 font-mono">{{ $index + 1 }}</td>
                        <td class="py-3.5 px-4">
                            <p class="font-bold text-gray-800">{{ $item->product->product_name ?? 'Product #' . $item->product_id }}</p>
                            @if(optional($item->product)->product_code)
                                <span class="font-mono text-[10px] text-gray-400">SKU/Code: {{ $item->product->product_code }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center font-bold text-gray-800">{{ $item->quantity }}</td>
                        <td class="py-3.5 px-4 text-right font-medium text-gray-600">${{ number_format($item->unit_cost ?? $item->cost_price ?? $item->unit_price, 2) }}</td>
                        <td class="py-3.5 px-4 text-right font-bold text-gray-800">${{ number_format($item->subtotal ?? ($item->quantity * ($item->unit_cost ?? $item->cost_price ?? $item->unit_price)), 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-gray-400">គ្មានទិន្នន័យទំនិញក្នុងប័ណ្ណទិញចូលនេះឡើយ</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary & Balance Calculation -->
        <div class="flex justify-end pt-4 border-t border-gray-100">
            <div class="w-full sm:w-80 space-y-2.5 text-xs">
                <div class="flex justify-between text-gray-600">
                    <span>សរុបបឋម (Subtotal):</span>
                    <span class="font-semibold">${{ number_format($purchase->subtotal ?? $purchase->total_amount, 2) }}</span>
                </div>
                @if(($purchase->discount_amount ?? 0) > 0)
                <div class="flex justify-between text-rose-500">
                    <span>បញ្ចុះតម្លៃពីអ្នកផ្គត់ផ្គង់:</span>
                    <span class="font-semibold">-${{ number_format($purchase->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm font-black text-gray-800 pt-2 border-t border-gray-100">
                    <span>ទឹកប្រាក់សរុបត្រូវបង់ (Total Cost):</span>
                    <span class="text-indigo-600 text-base font-black">${{ number_format($purchase->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-700 pt-1">
                    <span>បានទូទាត់រួច (Paid Amount):</span>
                    <span class="font-bold text-emerald-600">${{ number_format($purchase->paid_amount, 2) }}</span>
                </div>
                @if($purchase->due_amount > 0)
                <div class="flex justify-between text-rose-600 font-bold pt-1 bg-rose-50 p-2 rounded-xl">
                    <span>នៅជំពាក់អ្នកផ្គត់ផ្គង់ (Due Balance):</span>
                    <span>${{ number_format($purchase->due_amount, 2) }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Notes -->
        @if($purchase->notes)
        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-xs text-gray-600">
            <span class="font-bold text-gray-700 block mb-1">កំណត់សម្គាល់ (Notes):</span>
            {{ $purchase->notes }}
        </div>
        @endif

        <!-- Footer Signatures -->
        <div class="grid grid-cols-2 gap-8 pt-10 text-center text-xs text-gray-500">
            <div>
                <p class="mb-14 font-medium">អ្នកប្រគល់ទំនិញ / អ្នកផ្គត់ផ្គង់ (Supplier)</p>
                <div class="border-b border-gray-300 w-36 mx-auto"></div>
            </div>
            <div>
                <p class="mb-14 font-medium">អ្នកទទួលទំនិញចូលស្តុក (Store Keeper)</p>
                <div class="border-b border-gray-300 w-36 mx-auto"></div>
            </div>
        </div>

    </div>

</div>

<!-- Print Styles -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .no-print {
        display: none !important;
    }
    .print-container, .print-container * {
        visibility: visible;
    }
    .print-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }
}
</style>
@endsection