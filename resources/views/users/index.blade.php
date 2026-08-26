@extends('layouts.app')

@section('title', 'User List - POS System')
@section('page_heading', 'Users & Staff')

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

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 relative z-10">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">User Management</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Manage all staff accounts, permissions, and roles.</p>
        </div>
        <a href="{{ route('users.create') }}" class="magnetic-btn inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25 transition duration-150">
            <i class="fa-solid fa-user-plus mr-2"></i> Add New User
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm relative z-10">
        <form method="GET" action="{{ route('users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
            <!-- Search Input -->
            <div class="md:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Search by name, username, email, phone..." 
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                >
            </div>

            <!-- Role Filter -->
            <div>
                <select name="role_id" class="w-full px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->role_id }}" {{ request('role_id') == $role->role_id ? 'selected' : '' }}>
                            {{ $role->role_name ?? $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="magnetic-btn w-full py-2 bg-slate-900 hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-700 text-white text-xs font-semibold rounded-xl transition shadow-md">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'role_id', 'status']))
                    <a href="{{ route('users.index') }}" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-xl text-xs flex items-center justify-center transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table Card -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden relative z-10">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">User</th>
                        <th class="py-3.5 px-6">Role</th>
                        <th class="py-3.5 px-6">Contact Info</th>
                        <th class="py-3.5 px-6">Gender</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                    @forelse($users as $user)
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/50 transition">
                        <!-- User / Avatar -->
                        <td class="py-3.5 px-6">
                            <div class="flex items-center space-x-3">
                                @if($user->profile_picture && file_exists(public_path('storage/' . $user->profile_picture)))
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->username }}" class="w-10 h-10 rounded-xl object-cover border border-gray-200 dark:border-slate-700">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-slate-800 to-slate-700 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                        {{ strtoupper(substr($user->full_name ?? $user->username, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-800 dark:text-white">{{ $user->full_name ?? $user->username }}</p>
                                    <p class="text-[11px] text-gray-400">@<span>{{ $user->username }}</span></p>
                                </div>
                            </div>
                        </td>

                        <!-- Role -->
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-900">
                                {{ $user->role->role_name ?? 'N/A' }}
                            </span>
                        </td>

                        <!-- Contact -->
                        <td class="py-3.5 px-6">
                            <p class="font-medium text-gray-800 dark:text-gray-200">{{ $user->email }}</p>
                            <p class="text-[11px] text-gray-400">{{ $user->phone ?? 'No phone number' }}</p>
                        </td>

                        <!-- Gender -->
                        <td class="py-3.5 px-6 text-gray-600 dark:text-gray-400">
                            {{ $user->gender->gender_name ?? '-' }}
                        </td>

                        <!-- Status -->
                        <td class="py-3.5 px-6">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-100 dark:border-rose-900">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Inactive
                                </span>
                            @endif
                        </td>

                        <!-- Actions Column -->
                        <td class="py-3.5 px-6 text-right space-x-1">
                            <!-- Edit Button -->
                            <a href="{{ route('users.edit', $user->user_id) }}" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-xl transition inline-flex items-center" title="Edit User">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>

                            <!-- SweetAlert2 Delete Form -->
                            <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="button" 
                                    onclick="confirmDelete(this, '{{ $user->username }}')" 
                                    class="p-2 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-xl transition inline-flex items-center" 
                                    title="Delete User"
                                >
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-users text-3xl text-gray-300 dark:text-slate-600 mb-2"></i>
                            <p class="text-sm font-medium">No users found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(button, userName) {
    Swal.fire({
        title: '<span class="text-2xl font-black text-gray-800 dark:text-white">តើអ្នកពិតជាចង់លុបមែនទេ?</span>',
        html: `
            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                អ្នកកំពុងស្នើសុំលុបគណនីឈ្មោះ <strong class="text-rose-600 dark:text-rose-400 font-bold">"${userName}"</strong>។<br>
                <span class="text-xs text-amber-600 dark:text-amber-400 font-medium">⚠️ សកម្មភាពនេះមិនអាចត្រឡប់ក្រោយវិញបានទេ!</span>
            </div>
        `,
        icon: 'warning',
        iconColor: '#e11d48',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-trash-can mr-2"></i> បាទ/ចាស, លុបឥឡូវនេះ',
        cancelButtonText: 'ថយក្រោយវិញ',
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
        focusCancel: true,
        width: '32rem',
        padding: '2rem',
        backdrop: `rgba(15, 23, 42, 0.65)`,
        customClass: {
            popup: 'rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 dark:bg-slate-800 dark:text-white',
            confirmButton: 'rounded-xl px-6 py-3 text-sm font-bold shadow-lg shadow-rose-500/30',
            cancelButton: 'rounded-xl px-6 py-3 text-sm font-semibold hover:bg-slate-600 transition'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}
</script>
@endsection