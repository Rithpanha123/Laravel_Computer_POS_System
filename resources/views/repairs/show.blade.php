@extends('layouts.app')

@section('title', 'Repair Ticket Details - POS System')
@section('page_heading', 'Repair Ticket Review')

@section('content')
<!-- Custom CSS for specialized animations, floating particles, and print controls -->
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
    @keyframes floating {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-8px) rotate(1deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }
    .floating-particle {
        animation: floating 6s ease-in-out infinite;
    }
    .card-3d-hover {
        transition: transform 0.3s cubic-bezier(0.2, 0, 0, 1), box-shadow 0.3s ease;
    }
    .card-3d-hover:hover {
        transform: translateY(-3px) rotateX(0.5deg) rotateY(-0.5deg);
        box-shadow: 0 20px 40px -15px rgba(59, 130, 246, 0.15);
    }
    .glow-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glow-card:hover {
        box-shadow: 0 0 25px -5px rgba(59, 130, 246, 0.2);
    }
    .magnetic-btn {
        transition: transform 0.2s cubic-bezier(0.2, 0, 0, 1), box-shadow 0.2s ease;
    }
    .magnetic-btn:hover {
        transform: translateY(-2px);
    }
</style>

<div class="max-w-4xl mx-auto space-y-6 p-4 sm:p-6 rounded-3xl animated-gradient-bg relative overflow-hidden">

    <!-- Floating Background Decorative Particles -->
    <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-3xl pointer-events-none floating-particle"></div>
    <div class="absolute top-1/2 -right-10 w-72 h-72 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl pointer-events-none floating-particle" style="animation-delay: 3s;"></div>

    <!-- Top Action Bar (Print & Back) -->
    <div class="flex items-center justify-between no-print relative z-10">
        <a href="{{ route('repairs.index') }}" class="magnetic-btn px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition shadow-sm inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ទៅបញ្ជី
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('repairs.edit', $repair->repair_id) }}" class="magnetic-btn px-4 py-2 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/50 rounded-xl text-xs font-bold transition border border-blue-100 dark:border-blue-900 inline-flex items-center">
                <i class="fa-solid fa-pen mr-1.5"></i> កែប្រែស្ថានភាព
            </a>
            <button onclick="window.print()" class="magnetic-btn px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center">
                <i class="fa-solid fa-print mr-1.5"></i> បោះពុម្ពប័ណ្ណទទួល (Print Ticket)
            </button>
        </div>
    </div>

    <!-- Printable Repair Receipt Card -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 sm:p-10 space-y-6 print-container relative z-10">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-6 border-b border-gray-100 dark:border-slate-700 gap-4">
            <div>
                <h1 class="text-2xl font-black text-gray-800 dark:text-white tracking-tight">COMPUTER STORE & REPAIR</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ប័ណ្ណទទួលជួសជុលឧបករណ៍អេឡិចត្រូនិច (Repair Job Ticket)</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="inline-block font-mono font-black text-sm text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/50 px-3 py-1 rounded-xl border border-blue-100 dark:border-blue-900">
                    #{{ $repair->repair_no }}
                </span>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">ថ្ងៃទទួល៖ {{ optional($repair->received_at)->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <!-- Customer & Device Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-gray-50/70 dark:bg-slate-900/50 p-5 rounded-2xl border border-gray-100 dark:border-slate-700">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 mb-1">ព័ត៌មានអតិថិជន (Customer)</p>
                <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $repair->customer->name ?? $repair->customer->customer_name ?? 'N/A' }}</p>
                <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5"><i class="fa-solid fa-phone mr-1.5 text-gray-400"></i>{{ $repair->customer->phone ?? 'គ្មានលេខទូរស័ព្ទ' }}</p>
                @if($repair->customer->address ?? false)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5"><i class="fa-solid fa-location-dot mr-1.5 text-gray-400"></i>{{ $repair->customer->address }}</p>
                @endif
            </div>

            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400 mb-1">ជាងទទួលបន្ទុក (Technician)</p>
                <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $repair->technician->full_name ?? 'មិនទាន់ចាត់តាំង' }}</p>
                <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5"><i class="fa-solid fa-phone mr-1.5 text-gray-400"></i>{{ $repair->technician->phone ?? '-' }}</p>
                <div class="mt-2">
                    @if(strtoupper($repair->status) === 'COMPLETED')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900">Ready for Pickup</span>
                    @elseif(strtoupper($repair->status) === 'IN_PROGRESS')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 dark:bg-blue-950/60 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-900">In Progress</span>
                    @elseif(strtoupper($repair->status) === 'DELIVERED')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 dark:bg-indigo-950/60 text-indigo-800 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-900">Delivered</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-900">Pending</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Device Info & Problem Details -->
        <div class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-gray-100 dark:border-slate-700 text-xs">
                <div>
                    <span class="text-gray-400">ឧបករណ៍ / Model:</span>
                    <p class="font-bold text-gray-800 dark:text-white text-sm mt-0.5">{{ $repair->device_name }}</p>
                </div>
                <div>
                    <span class="text-gray-400">Serial Number (S/N):</span>
                    <p class="font-mono font-semibold text-gray-700 dark:text-gray-300 text-sm mt-0.5">{{ $repair->serial_number ?: 'N/A' }}</p>
                </div>
            </div>

            <div class="text-xs">
                <span class="font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider text-[11px]">ការរៀបរាប់បញ្ហា (Problem Description):</span>
                <div class="mt-1 p-3 bg-gray-50 dark:bg-slate-900/50 rounded-xl border border-gray-100 dark:border-slate-700 text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">
                    {{ $repair->problem_description }}
                </div>
            </div>

            @if($repair->diagnosis)
            <div class="text-xs">
                <span class="font-bold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider text-[11px]">ដំណោះស្រាយ / កំណត់ត្រាជួសជុល (Diagnosis & Fix):</span>
                <div class="mt-1 p-3 bg-emerald-50/50 dark:bg-emerald-950/30 rounded-xl border border-emerald-100 dark:border-emerald-900 text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">
                    {{ $repair->diagnosis }}
                </div>
            </div>
            @endif
        </div>

        <!-- Financial Summary -->
        <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-slate-700">
            <div class="w-full sm:w-72 space-y-2 text-xs">
                <div class="flex justify-between text-gray-600 dark:text-gray-400">
                    <span>តម្លៃប៉ាន់ស្មាន (Est. Cost):</span>
                    <span class="font-bold text-gray-800 dark:text-gray-200">${{ number_format($repair->estimated_cost, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm font-black text-gray-800 dark:text-white pt-2 border-t border-gray-100 dark:border-slate-700">
                    <span>តម្លៃជាក់ស្តែង (Final Cost):</span>
                    <span class="text-emerald-600 dark:text-emerald-400 text-base">
                        ${{ number_format($repair->final_cost > 0 ? $repair->final_cost : $repair->estimated_cost, 2) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="grid grid-cols-2 gap-8 pt-10 text-center text-xs text-gray-500 dark:text-gray-400">
            <div>
                <p class="mb-14">ហត្ថលេខាអតិថិជន</p>
                <div class="border-b border-gray-300 dark:border-slate-600 w-36 mx-auto"></div>
            </div>
            <div>
                <p class="mb-14">ហត្ថលេខាអ្នកទទួល / ជាង</p>
                <div class="border-b border-gray-300 dark:border-slate-600 w-36 mx-auto"></div>
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
        background: white !important;
        color: black !important;
    }
}
</style>
@endsection