@extends('layouts.app')

@section('title', 'Repair Services - POS System')
@section('page_heading', 'Repair Management')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">សេវាកម្មជួសជុល (Repair Jobs)</h2>
            <p class="text-xs sm:text-sm text-gray-500">តាមដានដំណើរការជួសជុលកុំព្យូទ័រ ឧបករណ៍អេឡិចត្រូនិក និងការប្រគល់ជូនភ្ញៀវ</p>
        </div>
        <a href="{{ route('repairs.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm shadow-indigo-500/20 transition">
            <i class="fa-solid fa-screwdriver-wrench mr-2"></i> ទទួលជួសជុលថ្មី
        </a>
    </div>

    <!-- Metrics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">រង់ចាំពិនិត្យ</p>
                <h4 class="text-2xl font-black text-amber-500 mt-1">{{ $pendingCount }}</h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">កំពុងជួសជុល</p>
                <h4 class="text-2xl font-black text-blue-600 mt-1">{{ $inProgressCount }}</h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-gears"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">ជួសជុលរួចរាល់</p>
                <h4 class="text-2xl font-black text-emerald-600 mt-1">{{ $completedCount }}</h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400">បានប្រគល់ជូន</p>
                <h4 class="text-2xl font-black text-purple-600 mt-1">{{ $deliveredCount }}</h4>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-handshake"></i>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
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
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <div>
                <select name="status" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
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
                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">
                    ស្វែងរក
                </button>
                @if(request()->hasAny(['search', 'status', 'from_date', 'per_page']))
                    <a href="{{ route('repairs.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl text-xs flex items-center justify-center transition" title="កំណត់ឡើងវិញ">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Repairs Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">កូដជួសជុល</th>
                        <th class="py-3.5 px-6">ឧបករណ៍ / ម៉ូដែល</th>
                        <th class="py-3.5 px-6">អតិថិជន</th>
                        <th class="py-3.5 px-6">ជាងទទួលបន្ទុក</th>
                        <th class="py-3.5 px-6">តម្លៃ / កក់</th>
                        <th class="py-3.5 px-6">ស្ថានភាព</th>
                        <th class="py-3.5 px-6 text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($repairs as $repair)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3.5 px-6">
                            <span class="font-mono font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100">
                                #{{ $repair->repair_code }}
                            </span>
                            <span class="block text-[11px] text-gray-400 mt-1">{{ optional($repair->received_at)->format('d M Y') }}</span>
                        </td>

                        <td class="py-3.5 px-6">
                            <p class="font-bold text-gray-800">{{ $repair->device_name }}</p>
                            <p class="text-[11px] text-gray-400 truncate max-w-xs">{{ $repair->problem_description }}</p>
                        </td>

                        <td class="py-3.5 px-6">
                            <p class="font-semibold text-gray-800">{{ $repair->customer->customer_name ?? 'N/A' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $repair->customer->phone ?? '-' }}</p>
                        </td>

                        <td class="py-3.5 px-6">
                            <span class="text-xs text-gray-600 font-medium">{{ $repair->technician->full_name ?? 'មិនទាន់ចាត់តាំង' }}</span>
                        </td>

                        <td class="py-3.5 px-6">
                            <p class="font-bold text-gray-800">${{ number_format($repair->final_cost ?: $repair->estimated_cost, 2) }}</p>
                            <p class="text-[11px] text-emerald-600">កក់: ${{ number_format($repair->deposit_amount, 2) }}</p>
                        </td>

                        <td class="py-3.5 px-6">
                            @if(strtoupper($repair->status) === 'COMPLETED')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Completed</span>
                            @elseif(strtoupper($repair->status) === 'IN_PROGRESS')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">In Progress</span>
                            @elseif(strtoupper($repair->status) === 'DELIVERED')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-100">Delivered</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100">Pending</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-6 text-right space-x-1">
                            <a href="{{ route('repairs.edit', $repair->repair_id) }}" class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition inline-flex items-center" title="កែប្រែ / Update Status">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('repairs.show', $repair->repair_id) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition inline-flex items-center" title="មើលប័ណ្ណទទួល / Print">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-screwdriver-wrench text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm font-medium">មិនទាន់មានទិន្នន័យសេវាជួសជុលនៅឡើយ</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <div>
                បង្ហាញពី <span class="font-bold text-gray-700">{{ $repairs->firstItem() ?? 0 }}</span> ដល់ <span class="font-bold text-gray-700">{{ $repairs->lastItem() ?? 0 }}</span> នៃទិន្នន័យសរុប <span class="font-bold text-gray-700">{{ $repairs->total() }}</span> ជួរ
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-1.5 whitespace-nowrap bg-white px-3 py-1 rounded-xl border border-gray-200 shadow-sm">
                    <span class="text-gray-500 font-medium">បង្ហាញ៖</span>
                    <select 
                        name="per_page" 
                        form="filterForm" 
                        onchange="document.getElementById('filterForm').submit()" 
                        class="bg-transparent border-none text-xs font-bold text-gray-800 outline-none cursor-pointer focus:ring-0 py-0.5 pr-6 pl-1"
                    >
                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 5) == 25 ? 'selected' : '' }}>25</option>
                        <option value="100" {{ request('per_page', 5) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-gray-500 font-medium">ជួរ</span>
                </div>

                <div>
                    {{ $repairs->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection