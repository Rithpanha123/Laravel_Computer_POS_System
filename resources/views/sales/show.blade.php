@extends('layouts.app')

@section('title', 'Invoice Details - POS System')
@section('page_heading', 'Invoice #' . ($sale->invoice_no ?? $sale->sale_id))

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Action Navigation -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('sales.index') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold text-gray-600 hover:text-gray-900 transition">
            <i class="fa-solid fa-arrow-left"></i> ត្រឡប់ទៅបញ្ជីវិក្កយបត្រ
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('pos.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs sm:text-sm font-semibold rounded-xl transition">
                <i class="fa-solid fa-cash-register mr-1.5"></i> លក់បន្ត (POS)
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition flex items-center gap-1.5">
                <i class="fa-solid fa-print"></i> បោះពុម្ព (Print Receipt)
            </button>
        </div>
    </div>

    <!-- Printable Invoice Receipt Card -->
    <div id="printableReceipt" class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-10 text-gray-800">
        
        <!-- Header -->
        <div class="text-center pb-6 border-b border-dashed border-gray-200 space-y-1">
            <h2 class="text-xl sm:text-2xl font-extrabold uppercase tracking-wide text-gray-900">ហាងកុំព្យូទ័រ និងគ្រឿងបន្លាស់</h2>
            <p class="text-xs text-gray-500">Computer & Accessories Store</p>
            <p class="text-xs text-gray-400 font-sans">ទូរស័ព្ទ៖ 012 345 678 / 098 765 432</p>
        </div>

        <!-- Meta Information -->
        <div class="grid grid-cols-2 gap-4 py-6 text-xs sm:text-sm border-b border-dashed border-gray-200">
            <div class="space-y-1.5">
                <div>
                    <span class="text-gray-400">លេខវិក្កយបត្រ៖</span>
                    <span class="font-mono font-bold text-blue-600 ml-1">#{{ $sale->invoice_no ?? $sale->sale_id }}</span>
                </div>
                <div>
                    <span class="text-gray-400">កាលបរិច្ឆេទ៖</span>
                    <span class="font-medium ml-1">{{ optional($sale->sale_date)->format('d-M-Y h:i A') }}</span>
                </div>
                <div>
                    <span class="text-gray-400">អ្នកគិតលុយ៖</span>
                    <span class="font-medium ml-1">{{ $sale->user->name ?? $sale->user->full_name ?? 'Cashier' }}</span>
                </div>
            </div>

            <div class="space-y-1.5 text-right sm:text-left">
                <div>
                    <span class="text-gray-400">អតិថិជន៖</span>
                    <span class="font-bold text-gray-900 ml-1">{{ $sale->customer->customer_name ?? $sale->customer->name ?? 'អតិថិជនទូទៅ' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">ទូរស័ព្ទ៖</span>
                    <span class="font-medium ml-1">{{ $sale->customer->phone ?? 'គ្មាន' }}</span>
                </div>
                <div>
                    <span class="text-gray-400">វិធីសាស្ត្រទូទាត់៖</span>
                    <span class="font-bold text-emerald-600 uppercase ml-1">{{ $sale->payment_method ?? 'Cash' }}</span>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="py-6">
            <table class="w-full text-left text-xs sm:text-sm border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-gray-400 uppercase text-[11px] font-bold">
                        <th class="py-2.5">#</th>
                        <th class="py-2.5">មុខទំនិញ</th>
                        <th class="py-2.5 text-center">ចំនួន</th>
                        <th class="py-2.5 text-right">តម្លៃរាយ</th>
                        <th class="py-2.5 text-right">សរុប</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @if(!empty($sale->items) && is_array($sale->items))
                        @foreach($sale->items as $index => $item)
                        <tr>
                            <td class="py-3 text-gray-400">{{ $index + 1 }}</td>
                            <td class="py-3 font-semibold text-gray-800">{{ $item['product_name'] ?? 'N/A' }}</td>
                            <td class="py-3 text-center font-bold">{{ $item['quantity'] ?? 1 }}</td>
                            <td class="py-3 text-right font-mono">${{ number_format($item['unit_price'] ?? 0, 2) }}</td>
                            <td class="py-3 text-right font-mono font-bold text-gray-900">${{ number_format($item['subtotal'] ?? 0, 2) }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="py-4 text-center text-gray-400">មិនមានទិន្នន័យទំនិញ</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Summary Calculation -->
        <div class="pt-4 border-t border-dashed border-gray-200 space-y-2 text-xs sm:text-sm">
            <div class="flex justify-between text-gray-500">
                <span>សរុបដើម (Subtotal):</span>
                <span class="font-mono font-bold text-gray-800">${{ number_format($sale->subtotal ?? $sale->total_amount, 2) }}</span>
            </div>

            @if(($sale->discount_amount ?? 0) > 0)
            <div class="flex justify-between text-rose-500">
                <span>បញ្ចុះតម្លៃ (Discount):</span>
                <span class="font-mono font-bold">-${{ number_format($sale->discount_amount, 2) }}</span>
            </div>
            @endif

            @if(($sale->tax_amount ?? 0) > 0)
            <div class="flex justify-between text-gray-500">
                <span>ពន្ធ (Tax):</span>
                <span class="font-mono font-bold text-gray-800">+${{ number_format($sale->tax_amount, 2) }}</span>
            </div>
            @endif

            <div class="flex justify-between items-center text-base sm:text-lg font-extrabold text-gray-900 pt-2 border-t border-gray-200">
                <span>ទឹកប្រាក់សរុប (Grand Total):</span>
                <span class="text-blue-600 font-mono">${{ number_format($sale->total_amount, 2) }}</span>
            </div>

            <div class="flex justify-between text-gray-600 pt-1 text-xs">
                <span>ប្រាក់បានបង់ (Paid Amount):</span>
                <span class="font-mono font-bold text-emerald-600">${{ number_format($sale->paid_amount ?? $sale->total_amount, 2) }}</span>
            </div>

            @if(($sale->due_amount ?? 0) > 0)
            <div class="flex justify-between text-rose-600 text-xs">
                <span>ប្រាក់នៅខ្វះ (Due Balance):</span>
                <span class="font-mono font-bold">${{ number_format($sale->due_amount, 2) }}</span>
            </div>
            @endif
        </div>

        <!-- Footer Notes & Barcode/QR -->
        <div class="mt-8 pt-6 border-t border-dashed border-gray-200 text-center space-y-1 text-xs text-gray-400">
            <p class="font-semibold text-gray-600">អរគុណចំពោះការគាំទ្ររបស់លោកអ្នក!</p>
            <p>ទំនិញដែលបានទិញរួចមិនអាចប្តូរជាសាច់ប្រាក់វិញបានទេ</p>
            <p class="text-[10px] text-gray-300 mt-2 font-mono">Printed on {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

    </div>

</div>

<!-- Print Styles -->
<style>
@media print {
    body * {
        visibility: hidden;
    }
    #printableReceipt, #printableReceipt * {
        visibility: visible;
    }
    #printableReceipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        max-width: 80mm;
        margin: 0 auto;
        padding: 10px;
        box-shadow: none !important;
        border: none !important;
    }
    .no-print {
        display: none !important;
    }
}
</style>
@endsection