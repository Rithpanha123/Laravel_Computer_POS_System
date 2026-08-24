@extends('layouts.app')

@section('title', 'Staff Management - POS System')
@section('page_heading', 'Staff & Technicians')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Staff & Technicians Directory</h2>
            <p class="text-xs sm:text-sm text-gray-500">Manage store employees, repair technicians, and cashier staff.</p>
        </div>
        <a href="{{ route('staff.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition">
            <i class="fa-solid fa-user-plus mr-2"></i> Add New Staff
        </a>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Staff</p>
                <p class="text-2xl font-black text-gray-800 mt-1">{{ $totalStaff }} Employees</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Active Staff</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ $activeStaff }} Active</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('staff.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by code, full name, phone number..." 
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:ring-2 focus:ring-blue-500 outline-none"
                >
            </div>
            <div>
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">All Employment Statuses</option>
                    <option value="ACTIVE" {{ request('status') == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                    <option value="INACTIVE" {{ request('status') == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                    <option value="RESIGNED" {{ request('status') == 'RESIGNED' ? 'selected' : '' }}>Resigned</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Staff Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">Staff Profile</th>
                        <th class="py-3.5 px-6">Position</th>
                        <th class="py-3.5 px-6">Contact</th>
                        <th class="py-3.5 px-6">Salary</th>
                        <th class="py-3.5 px-6">Hire Date</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($staffMembers as $member)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3.5 px-6 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-50 border border-gray-100 overflow-hidden flex items-center justify-center flex-shrink-0">
                                @if($member->photo)
                                    <img src="{{ asset('storage/' . $member->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-user text-blue-600"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $member->full_name }}</p>
                                <span class="font-mono text-[10px] text-gray-400">#{{ $member->staff_code }}</span>
                            </div>
                        </td>
                        
                        <!-- Position Column -->
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                <i class="fa-solid fa-briefcase mr-1.5 text-[10px]"></i>
                                {{ $member->position->position_name ?? $member->position->{'position_name, 50'} ?? 'General Staff' }}
                            </span>
                        </td>

                        <td class="py-3.5 px-6">
                            <p class="font-medium text-gray-800">{{ $member->phone }}</p>
                            <p class="text-[11px] text-gray-400">{{ $member->email ?? 'No email' }}</p>
                        </td>
                        <td class="py-3.5 px-6 font-bold text-gray-800">
                            ${{ number_format($member->salary, 2) }}
                        </td>
                        <td class="py-3.5 px-6 text-gray-500">
                            {{ optional($member->hire_date)->format('d M Y') ?? 'N/A' }}
                        </td>
                        <td class="py-3.5 px-6">
                            @if(strtoupper($member->employment_status) === 'ACTIVE')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-gray-100 text-gray-600 border border-gray-200">{{ $member->employment_status }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-right space-x-1">
                            <a href="{{ route('staff.edit', $member->staff_id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition inline-flex items-center" title="Edit Staff">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-users text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm font-medium">No staff members found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staffMembers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $staffMembers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection