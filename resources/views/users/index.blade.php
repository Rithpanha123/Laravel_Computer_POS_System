@extends('layouts.app')

@section('title', 'User List - POS System')
@section('page_heading', 'Users & Staff')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">User Management</h2>
            <p class="text-xs sm:text-sm text-gray-500">Manage all staff accounts, permissions, and roles.</p>
        </div>
        <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition duration-150">
            <i class="fa-solid fa-user-plus mr-2"></i> Add New User
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
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
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                >
            </div>

            <!-- Role Filter -->
            <div>
                <select name="role_id" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
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
                <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'role_id', 'status']))
                    <a href="{{ route('users.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl text-xs flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">User</th>
                        <th class="py-3.5 px-6">Role</th>
                        <th class="py-3.5 px-6">Contact Info</th>
                        <th class="py-3.5 px-6">Gender</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/60 transition">
                        <!-- User / Avatar -->
                        <td class="py-3.5 px-6">
                            <div class="flex items-center space-x-3">
                                @if($user->profile_picture && file_exists(public_path('storage/' . $user->profile_picture)))
                <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                            alt="{{ $user->username }}" 
                             class="w-10 h-10 rounded-xl object-cover border border-gray-200">
                                @else
                                     <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-slate-800 to-slate-700 text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                         {{ strtoupper(substr($user->full_name ?? $user->username, 0, 1)) }}
                                     </div>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-800">{{ $user->full_name ?? $user->username }}</p>
                                    <p class="text-[11px] text-gray-400">@<span>{{ $user->username }}</span></p>
                                </div>
                            </div>
                        </td>

                        <!-- Role -->
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $user->role->role_name ?? 'N/A' }}
                            </span>
                        </td>

                        <!-- Contact -->
                        <td class="py-3.5 px-6">
                            <p class="font-medium text-gray-800">{{ $user->email }}</p>
                            <p class="text-[11px] text-gray-400">{{ $user->phone ?? 'No phone number' }}</p>
                        </td>

                        <!-- Gender -->
                        <td class="py-3.5 px-6 text-gray-600">
                            {{ $user->gender->gender_name ?? '-' }}
                        </td>

                        <!-- Status -->
                        <td class="py-3.5 px-6">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Inactive
                                </span>
                            @endif
                        </td>

                        <!-- Actions Column -->
                    <td class="py-3.5 px-6 text-right space-x-1">
                         <!-- Edit Button -->
                        <a href="{{ route('users.edit', $user->user_id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition inline-flex items-center" title="Edit User">
                         <i class="fa-regular fa-pen-to-square"></i>
                         </a>

                         <!-- SweetAlert2 Delete Form -->
                    <form action="{{ route('users.destroy', $user->user_id) }}" method="POST" class="inline delete-form">
                     @csrf
                     @method('DELETE')
                    <button 
                         type="button" 
                         onclick="confirmDelete(this, '{{ $user->username }}')" 
                         class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition inline-flex items-center" 
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
                            <i class="fa-solid fa-users text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm font-medium">No users found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>

<!-- SweetAlert2 CDN (ប្រសិនបើមិនទាន់មានក្នុង layout) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmDelete(button, userName) {
    Swal.fire({
        title: '<span class="text-2xl font-black text-gray-800">តើអ្នកពិតជាចង់លុបមែនទេ?</span>',
        html: `
            <div class="mt-2 text-sm text-gray-500 leading-relaxed">
                អ្នកកំពុងស្នើសុំលុបគណនីឈ្មោះ <strong class="text-rose-600 font-bold">"${userName}"</strong>។<br>
                <span class="text-xs text-amber-600 font-medium">⚠️ សកម្មភាពនេះមិនអាចត្រឡប់ក្រោយវិញបានទេ!</span>
            </div>
        `,
        icon: 'warning',
        iconColor: '#e11d48',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-trash-can mr-2"></i> បាទ/ចាស, លុបឥឡូវនេះ',
        cancelButtonText: 'ថយក្រោយវិញ',
        confirmButtonColor: '#e11d48', // ពណ៌ក្រហម Rose-600
        cancelButtonColor: '#64748b',  // ពណ៌ប្រផេះ Slate-500
        reverseButtons: true,          // ដាក់ប៊ូតុង Cancel នៅឆ្វេង និង Delete នៅស្តាំ
        focusCancel: true,             // Focus លើ Cancel ដើម្បីកុំឱ្យច្រឡំដៃចុច Enter
        width: '32rem',                // កំណត់ទទឹងផ្ទាំងឱ្យធំ (512px)
        padding: '2rem',               // Padding ធំទូលាយ
        backdrop: `rgba(15, 23, 42, 0.65)`, // ផ្ទៃក្រោយងងឹត Focus លើ Box
        showClass: {
            popup: 'swal2-show animate__animated animate__zoomIn animate__faster'
        },
        hideClass: {
            popup: 'swal2-hide animate__animated animate__zoomOut animate__faster'
        },
        customClass: {
            popup: 'rounded-3xl shadow-2xl border border-gray-100',
            confirmButton: 'rounded-xl px-6 py-3 text-sm font-bold shadow-lg shadow-rose-500/30',
            cancelButton: 'rounded-xl px-6 py-3 text-sm font-semibold hover:bg-slate-600 transition'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // បើចុចយល់ព្រម វានឹង Submit form លុបទៅ Controller
            button.closest('form').submit();
        }
    });
}
</script>
@endsection