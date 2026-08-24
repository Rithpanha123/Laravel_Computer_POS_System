@extends('layouts.app')

@section('title', 'Repair Ticket Details - POS System')
@section('page_heading', 'Repair Ticket Review')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Action Bar (Print & Back) -->
    <div class="flex items-center justify-between no-print">
        <a href="{{ route('repairs.index') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ទៅបញ្ជី
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('repairs.edit', $repair->repair_id) }}" class="px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-xl text-xs font-bold transition">
                <i class="fa-solid fa-pen mr-1.5"></i> កែប្រែស្ថានភាព
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                <i class="fa-solid fa-print mr-1.5"></i> បោះពុម្ពប័ណ្ណទទួល (Print Ticket)
            </button>
        </div>
    </div>

    <!-- Printable Repair Receipt Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-10 space-y-6 print-container">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-gray-100 gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-800 tracking-tight">COMPUTER STORE & REPAIR</h1>
                <p class="text-xs text-gray-500 mt-0.5">ប័ណ្ណទទួលជួសជុលឧបករណ៍អេឡិចត្រូនិច (Repair Job Ticket)</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="inline-block font-mono font-black text-sm text-blue-600 bg-blue-50 px-3 py-1 rounded-xl border border-blue-100">
                    #{{ $repair->repair_no }}
                </span>
                <p class="text-xs text-gray-400 mt-1">ថ្ងៃទទួល៖ {{ optional($repair->received_at)->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <!-- Customer & Device Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-gray-50/70 p-5 rounded-2xl border border-gray-100">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">ព័ត៌មានអតិថិជន (Customer)</p>
                <p class="text-sm font-bold text-gray-800">{{ $repair->customer->name ?? $repair->customer->customer_name ?? 'N/A' }}</p>
                <p class="text-xs text-gray-600 mt-0.5"><i class="fa-solid fa-phone mr-1.5 text-gray-400"></i>{{ $repair->customer->phone ?? 'គ្មានលេខទូរស័ព្ទ' }}</p>
                @if($repair->customer->address ?? false)
                    <p class="text-xs text-gray-500 mt-0.5"><i class="fa-solid fa-location-dot mr-1.5 text-gray-400"></i>{{ $repair->customer->address }}</p>
                @endif
            </div>

            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">ជាងទទួលបន្ទុក (Technician)</p>
                <p class="text-sm font-bold text-gray-800">{{ $repair->technician->full_name ?? 'មិនទាន់ចាត់តាំង' }}</p>
                <p class="text-xs text-gray-600 mt-0.5"><i class="fa-solid fa-phone mr-1.5 text-gray-400"></i>{{ $repair->technician->phone ?? '-' }}</p>
                <div class="mt-2">
                    @if(strtoupper($repair->status) === 'COMPLETED')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Ready for Pickup</span>
                    @elseif(strtoupper($repair->status) === 'IN_PROGRESS')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">In Progress</span>
                    @elseif(strtoupper($repair->status) === 'DELIVERED')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800">Delivered</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">Pending</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Device Info & Problem Details -->
        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-gray-100 text-xs">
                <div>
                    <span class="text-gray-400">ឧបករណ៍ / Model:</span>
                    <p class="font-bold text-gray-800 text-sm mt-0.5">{{ $repair->device_name }}</p>
                </div>
                <div>
                    <span class="text-gray-400">Serial Number (S/N):</span>
                    <p class="font-mono font-semibold text-gray-700 text-sm mt-0.5">{{ $repair->serial_number ?: 'N/A' }}</p>
                </div>
            </div>

            <div class="text-xs">
                <span class="font-bold text-gray-700 uppercase tracking-wider text-[11px]">ការរៀបរាប់បញ្ហា (Problem Description):</span>
                <div class="mt-1 p-3 bg-gray-50 rounded-xl border border-gray-100 text-gray-700 whitespace-pre-line leading-relaxed">
                    {{ $repair->problem_description }}
                </div>
            </div>

            @if($repair->diagnosis)
            <div class="text-xs">
                <span class="font-bold text-emerald-700 uppercase tracking-wider text-[11px]">ដំណោះស្រាយ / កំណត់ត្រាជួសជុល (Diagnosis & Fix):</span>
                <div class="mt-1 p-3 bg-emerald-50/50 rounded-xl border border-emerald-100 text-gray-700 whitespace-pre-line leading-relaxed">
                    {{ $repair->diagnosis }}
                </div>
            </div>
            @endif
        </div>

        <!-- Financial Summary -->
        <div class="flex justify-end pt-4 border-t border-gray-100">
            <div class="w-full sm:w-72 space-y-2 text-xs">
                <div class="flex justify-between text-gray-600">
                    <span>តម្លៃប៉ាន់ស្មាន (Est. Cost):</span>
                    <span class="font-bold">${{ number_format($repair->estimated_cost, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm font-black text-gray-800 pt-2 border-t border-gray-100">
                    <span>តម្លៃជាក់ស្តែង (Final Cost):</span>
                    <span class="text-emerald-600 text-base">
                        ${{ number_format($repair->final_cost > 0 ? $repair->final_cost : $repair->estimated_cost, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="grid grid-cols-2 gap-8 pt-10 text-center text-xs text-gray-500">
            <div>
                <p class="mb-14">ហត្ថលេខាអតិថិជន</p>
                <div class="border-b border-gray-300 w-36 mx-auto"></div>
            </div>
            <div>
                <p class="mb-14">ហត្ថលេខាអ្នកទទួល / ជាង</p>
                <div class="border-b border-gray-300 w-36 mx-auto"></div>
            </div>
        </div>

    </div>

</div>

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
    }
}
</style>
@endsection