@extends('layouts.app')

@section('title', 'Repair Services - POS System')
@section('page_heading', 'Repair Management')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Device Repair & Services</h2>
            <p class="text-xs sm:text-sm text-gray-500">Track device intake, technician diagnosis, spare parts, and job statuses.</p>
        </div>
        <a href="{{ route('repairs.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition">
            <i class="fa-solid fa-plus mr-2"></i> Receive New Device
        </a>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">In Progress / Pending</p>
                <p class="text-2xl font-black text-amber-600 mt-1">{{ $pendingCount }} Jobs</p>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Ready for Pickup</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ $completedCount }} Devices</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Delivered</p>
                <p class="text-2xl font-black text-indigo-600 mt-1">{{ $deliveredCount }} Devices</p>
            </div>
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-box-tissue"></i>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('repairs.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search repair ticket #, customer name, device or serial..." 
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:ring-2 focus:ring-blue-500 outline-none"
                >
            </div>

            <div>
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">All Statuses</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="IN_PROGRESS" {{ request('status') == 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option>
                    <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                    <option value="DELIVERED" {{ request('status') == 'DELIVERED' ? 'selected' : '' }}>Delivered</option>
                    <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Repairs Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">Ticket No</th>
                        <th class="py-3.5 px-6">Customer</th>
                        <th class="py-3.5 px-6">Device / Issue</th>
                        <th class="py-3.5 px-6">Technician</th>
                        <th class="py-3.5 px-6">Est. / Final Cost</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($repairs as $repair)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3.5 px-6">
                            <span class="font-mono font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">
                                #{{ $repair->repair_no }}
                            </span>
                            <span class="block text-[11px] text-gray-400 mt-1">{{ optional($repair->received_at)->format('d M Y') }}</span>
                        </td>
                        <td class="py-3.5 px-6">
                            <p class="font-semibold text-gray-800">{{ $repair->customer->name ?? 'N/A' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $repair->customer->phone ?? '' }}</p>
                        </td>
                        <td class="py-3.5 px-6 max-w-xs">
                            <p class="font-bold text-gray-800">{{ $repair->device_name }}</p>
                            <p class="text-[11px] text-gray-500 line-clamp-1">{{ $repair->problem_description }}</p>
                        </td>
                        <td class="py-3.5 px-6">
                            <span class="text-xs text-gray-600">{{ $repair->technician->full_name ?? $repair->technician->name ?? 'Unassigned' }}</span>
                        </td>
                        <td class="py-3.5 px-6">
                            <p class="font-bold text-gray-800">${{ number_format($repair->final_cost > 0 ? $repair->final_cost : $repair->estimated_cost, 2) }}</p>
                            <p class="text-[10px] text-gray-400">{{ $repair->final_cost > 0 ? 'Final' : 'Estimated' }}</p>
                        </td>
                        <td class="py-3.5 px-6">
                            @if(strtoupper($repair->status) === 'COMPLETED')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">Ready Pickup</span>
                            @elseif(strtoupper($repair->status) === 'IN_PROGRESS')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">In Progress</span>
                            @elseif(strtoupper($repair->status) === 'DELIVERED')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">Delivered</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100">Pending</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-right space-x-1">
                            <a href="{{ route('repairs.edit', $repair->repair_id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition inline-flex items-center" title="Edit Repair Status">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('repairs.show', $repair->repair_id) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition inline-flex items-center" title="View Ticket">
                                <i class="fa-regular fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-screwdriver-wrench text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm font-medium">No repair jobs found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($repairs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $repairs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection