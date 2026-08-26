@extends('layouts.app')

@section('title', 'Staff Management - POS System')
@section('page_heading', 'Staff & Technicians')

@section('content')
<!-- Custom CSS for specialized animations, floating particles, and interactions -->
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

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 relative z-10">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Staff & Technicians Directory</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Manage store employees, repair technicians, and cashier staff.</p>
        </div>
        <a href="{{ route('staff.create') }}" class="magnetic-btn inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25 transition">
            <i class="fa-solid fa-user-plus mr-2"></i> Add New Staff
        </a>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 relative z-10">
        
        <!-- Total Staff Card -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-400">Total Staff</p>
                <p class="text-2xl font-black text-gray-800 dark:text-white mt-1">{{ $totalStaff }} Employees</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center text-xl border border-blue-100 dark:border-blue-900 shadow-inner">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        <!-- Active Staff Card -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-400">Active Staff</p>
                <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $activeStaff }} Active</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center text-xl border border-emerald-100 dark:border-emerald-900 shadow-inner">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>

    </div>

    <!-- Search & Filter -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm relative z-10">
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
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition"
                >
            </div>
            <div>
                <select name="status" onchange="this.form.submit()" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="">All Employment Statuses</option>
                    <option value="ACTIVE" {{ request('status') == 'ACTIVE' ? 'selected' : '' }}>Active</option>
                    <option value="INACTIVE" {{ request('status') == 'INACTIVE' ? 'selected' : '' }}>Inactive</option>
                    <option value="RESIGNED" {{ request('status') == 'RESIGNED' ? 'selected' : '' }}>Resigned</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Staff Table -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden relative z-10">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">Staff Profile</th>
                        <th class="py-3.5 px-6">Position</th>
                        <th class="py-3.5 px-6">Contact</th>
                        <th class="py-3.5 px-6">Salary</th>
                        <th class="py-3.5 px-6">Hire Date</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                    @forelse($staffMembers as $member)
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/50 transition">
                        <td class="py-3.5 px-6 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-950/50 border border-gray-100 dark:border-slate-700 overflow-hidden flex items-center justify-center flex-shrink-0">
                                @if($member->photo)
                                    <img src="{{ asset('storage/' . $member->photo) }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-user text-blue-600 dark:text-blue-400"></i>
                                @endif
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 dark:text-white">{{ $member->full_name }}</p>
                                <span class="font-mono text-[10px] text-gray-400">#{{ $member->staff_code }}</span>
                            </div>
                        </td>
                        
                        <!-- Position Column -->
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-900">
                                <i class="fa-solid fa-briefcase mr-1.5 text-[10px]"></i>
                                {{ $member->position->position_name ?? 'General Staff' }}
                            </span>
                        </td>

                        <td class="py-3.5 px-6">
                            <p class="font-medium text-gray-800 dark:text-gray-200">{{ $member->phone }}</p>
                            <p class="text-[11px] text-gray-400">{{ $member->email ?? 'No email' }}</p>
                        </td>
                        <td class="py-3.5 px-6 font-bold text-gray-800 dark:text-gray-200">
                            ${{ number_format($member->salary, 2) }}
                        </td>
                        <td class="py-3.5 px-6 text-gray-500 dark:text-gray-400">
                            {{ optional($member->hire_date)->format('d M Y') ?? 'N/A' }}
                        </td>
                        <td class="py-3.5 px-6">
                            @if(strtoupper($member->employment_status) === 'ACTIVE')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-slate-600">{{ $member->employment_status }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-6 text-right space-x-1">
                            <a href="{{ route('staff.edit', $member->staff_id) }}" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-xl transition inline-flex items-center" title="Edit Staff">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-users text-3xl text-gray-300 dark:text-slate-600 mb-2"></i>
                            <p class="text-sm font-medium">No staff members found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staffMembers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50">
                {{ $staffMembers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection