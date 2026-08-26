@extends('layouts.app')

@section('title', 'Repair Services - POS System')
@section('page_heading', 'Repair Management')

@section('content')
<!-- Custom CSS for specialized animations, floating particles, and card interactions -->
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

<div class="space-y-6 p-4 sm:p-6 rounded-3xl animated-gradient-bg relative overflow-hidden">

    <!-- Floating Background Decorative Particles -->
    <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-3xl pointer-events-none floating-particle"></div>
    <div class="absolute top-1/2 -right-10 w-72 h-72 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl pointer-events-none floating-particle" style="animation-delay: 3s;"></div>

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 relative z-10">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">សេវាកម្មជួសជុល (Repair Jobs)</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">តាមដានដំណើរការជួសជុលកុំព្យូទ័រ ឧបករណ៍អេឡិចត្រូនិក និងការប្រគល់ជូនភ្ញៀវ</p>
        </div>
        <a href="{{ route('repairs.create') }}" class="magnetic-btn inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-lg shadow-indigo-500/25 transition">
            <i class="fa-solid fa-screwdriver-wrench mr-2"></i> ទទួលជួសជុលថ្មី
        </a>
    </div>

    <!-- Metrics Cards with Glowing & 3D Effects -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 relative z-10">
        
        <!-- Pending -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">រង់ចាំពិនិត្យ</p>
                <h4 class="text-2xl font-black text-amber-500 dark:text-amber-400 mt-1">{{ $pendingCount }}</h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg border border-amber-100 dark:border-amber-900 shadow-inner">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <!-- In Progress -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">កំពុងជួសជុល</p>
                <h4 class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1">{{ $inProgressCount }}</h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg border border-blue-100 dark:border-blue-900 shadow-inner">
                <i class="fa-solid fa-gears"></i>
            </div>
        </div>

        <!-- Completed -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">ជួសជុលរួចរាល់</p>
                <h4 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $completedCount }}</h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg border border-emerald-100 dark:border-emerald-900 shadow-inner">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <!-- Delivered -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-400">បានប្រគល់ជូន</p>
                <h4 class="text-2xl font-black text-purple-600 dark:text-purple-400 mt-1">{{ $deliveredCount }}</h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg border border-purple-100 dark:border-purple-900 shadow-inner">
                <i class="fa-solid fa-handshake"></i>
            </div>
        </div>

    </div>

    <!-- Search & Filter Bar -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm relative z-10">
        <form id="filterForm" method="GET" action="{{ route('repairs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <div class="md:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="ស្វែងរកលេខកូដ ឈ្មោះឧបករណ៍ ភ្ញៀវ លេខទូរស័ព្ទ..." 
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                >
            </div>

            <div>
                <select name="status" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                    <option value="">គ្រប់ស្ថានភាព (All Status)</option>
                    <option value="PENDING" {{ strtoupper(request('status')) === 'PENDING' ? 'selected' : '' }}>Pending (រង់ចាំ)</option>
                    <option value="IN_PROGRESS" {{ strtoupper(request('status')) === 'IN_PROGRESS' ? 'selected' : '' }}>In Progress (កំពុងធ្វើ)</option>
                    <option value="COMPLETED" {{ strtoupper(request('status')) === 'COMPLETED' ? 'selected' : '' }}>Completed (រួចរាល់)</option>
                    <option value="DELIVERED" {{ strtoupper(request('status')) === 'DELIVERED' ? 'selected' : '' }}>Delivered (ប្រគល់ជូន)</option>
                </select>
            </div>

            <div>
                <input 
                    type="date" 
                    name="from_date" 
                    value="{{ request('from_date') }}" 
                    class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                >
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="magnetic-btn w-full py-2 bg-slate-900 hover:bg-slate-800 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl transition shadow-md">
                    ស្វែងរក
                </button>
                @if(request()->hasAny(['search', 'status', 'from_date', 'per_page']))
                    <a href="{{ route('repairs.index') }}" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-xl text-xs flex items-center justify-center transition" title="កំណត់ឡើងវិញ">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Repairs Table -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden relative z-10">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">កូដជួសជុល</th>
                        <th class="py-3.5 px-6">ឧបករណ៍ / ម៉ូដែល</th>
                        <th class="py-3.5 px-6">អតិថិជន</th>
                        <th class="py-3.5 px-6">ជាងទទួលបន្ទុក</th>
                        <th class="py-3.5 px-6">តម្លៃ / កក់</th>
                        <th class="py-3.5 px-6">ស្ថានភាព</th>
                        <th class="py-3.5 px-6 text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                    @forelse($repairs as $repair)
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/50 transition">
                        <td class="py-3.5 px-6">
                            <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50 px-2.5 py-1 rounded-lg border border-indigo-100 dark:border-indigo-900">
                                #{{ $repair->repair_code }}
                            </span>
                            <span class="block text-[11px] text-gray-400 mt-1">{{ optional($repair->received_at)->format('d M Y') }}</span>
                        </td>

                        <td class="py-3.5 px-6">
                            <p class="font-bold text-gray-800 dark:text-white">{{ $repair->device_name }}</p>
                            <p class="text-[11px] text-gray-400 truncate max-w-xs">{{ $repair->problem_description }}</p>
                        </td>

                        <td class="py-3.5 px-6">
                            <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $repair->customer->customer_name ?? 'N/A' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $repair->customer->phone ?? '-' }}</p>
                        </td>

                        <td class="py-3.5 px-6">
                            <span class="text-xs text-gray-600 dark:text-gray-400 font-medium">{{ $repair->technician->full_name ?? 'មិនទាន់ចាត់តាំង' }}</span>
                        </td>

                        <td class="py-3.5 px-6">
                            <p class="font-bold text-gray-800 dark:text-gray-200">${{ number_format($repair->final_cost ?: $repair->estimated_cost, 2) }}</p>
                            <p class="text-[11px] text-emerald-600 dark:text-emerald-400">កក់: ${{ number_format($repair->deposit_amount, 2) }}</p>
                        </td>

                        <td class="py-3.5 px-6">
                            @if(strtoupper($repair->status) === 'COMPLETED')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900">Completed</span>
                            @elseif(strtoupper($repair->status) === 'IN_PROGRESS')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-900">In Progress</span>
                            @elseif(strtoupper($repair->status) === 'DELIVERED')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-50 dark:bg-purple-950/50 text-purple-700 dark:text-purple-300 border border-purple-100 dark:border-purple-900">Delivered</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-100 dark:border-amber-900">Pending</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-6 text-right space-x-1">
                            <a href="{{ route('repairs.edit', $repair->repair_id) }}" class="p-2 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/30 rounded-xl transition inline-flex items-center" title="កែប្រែ / Update Status">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('repairs.show', $repair->repair_id) }}" class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 rounded-xl transition inline-flex items-center" title="មើលប័ណ្ណទទួល / Print">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-screwdriver-wrench text-3xl text-gray-300 dark:text-slate-600 mb-2"></i>
                            <p class="text-sm font-medium">មិនទាន់មានទិន្នន័យសេវាជួសជុលនៅឡើយ</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500 dark:text-gray-400">
            <div>
                បង្ហាញពី <span class="font-bold text-gray-700 dark:text-gray-200">{{ $repairs->firstItem() ?? 0 }}</span> ដល់ <span class="font-bold text-gray-700 dark:text-gray-200">{{ $repairs->lastItem() ?? 0 }}</span> នៃទិន្នន័យសរុប <span class="font-bold text-gray-700 dark:text-gray-200">{{ $repairs->total() }}</span> ជួរ
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-1.5 whitespace-nowrap bg-white dark:bg-slate-800 px-3 py-1 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">បង្ហាញ៖</span>
                    <select 
                        name="per_page" 
                        form="filterForm" 
                        onchange="document.getElementById('filterForm').submit()" 
                        class="bg-transparent border-none text-xs font-bold text-gray-800 dark:text-white outline-none cursor-pointer focus:ring-0 py-0.5 pr-6 pl-1"
                    >
                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 5) == 25 ? 'selected' : '' }}>25</option>
                        <option value="100" {{ request('per_page', 5) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-gray-500 dark:text-gray-400 font-medium">ជួរ</span>
                </div>

                <div>
                    {{ $repairs->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection