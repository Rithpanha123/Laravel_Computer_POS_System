@extends('layouts.app')

@section('title', 'Edit User - POS System')
@section('page_heading', 'Edit User')

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

<div class="max-w-4xl mx-auto space-y-6 p-4 sm:p-6 rounded-3xl animated-gradient-bg relative overflow-hidden">

    <!-- Floating Background Decorative Particles -->
    <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-3xl pointer-events-none floating-particle"></div>
    <div class="absolute top-1/2 -right-10 w-72 h-72 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl pointer-events-none floating-particle" style="animation-delay: 3s;"></div>

    <!-- Header & Back Button -->
    <div class="flex items-center justify-between relative z-10">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Edit User: {{ $user->full_name ?? $user->username }}</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Update account details, role permissions, or security credentials.</p>
        </div>
        <a href="{{ route('users.index') }}" class="magnetic-btn inline-flex items-center px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <!-- Form Card -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 sm:p-8 relative z-10">
        <form action="{{ route('users.update', $user->user_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Identity Section -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 pb-2 border-b border-gray-100 dark:border-slate-700">
                    1. Basic Information
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            name="full_name" 
                            value="{{ old('full_name', $user->full_name) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border @error('full_name') border-rose-500 bg-rose-50/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required
                        >
                        @error('full_name')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Username <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            name="username" 
                            value="{{ old('username', $user->username) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border @error('username') border-rose-500 bg-rose-50/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required
                        >
                        @error('username')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Email Address <span class="text-red-500">*</span></label>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email', $user->email) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border @error('email') border-rose-500 bg-rose-50/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required
                        >
                        @error('email')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Phone Number</label>
                        <input 
                            type="text" 
                            name="phone" 
                            value="{{ old('phone', $user->phone) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border @error('phone') border-rose-500 bg-rose-50/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        >
                        @error('phone')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Role & Gender Section -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 pb-2 border-b border-gray-100 dark:border-slate-700">
                    2. Role & Classification
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Role -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Role <span class="text-red-500">*</span></label>
                        <select 
                            name="role_id" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border @error('role_id') border-rose-500 bg-rose-50/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required
                        >
                            @foreach($roles as $role)
                                <option value="{{ $role->role_id }}" {{ old('role_id', $user->role_id) == $role->role_id ? 'selected' : '' }}>
                                    {{ $role->role_name ?? $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Gender <span class="text-red-500">*</span></label>
                        <select 
                            name="gender_id" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border @error('gender_id') border-rose-500 bg-rose-50/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                            required
                        >
                            @foreach($genders as $gender)
                                <option value="{{ $gender->gender_id }}" {{ old('gender_id', $user->gender_id) == $gender->gender_id ? 'selected' : '' }}>
                                    {{ $gender->gender_name ?? $gender->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender_id')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Password Section -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1 pb-2 border-b border-gray-100 dark:border-slate-700">
                    3. Security Credentials
                </h3>
                <p class="text-xs text-amber-600 dark:text-amber-400 mb-4 italic">Leave blank if you do not want to change the password.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- New Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">New Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            placeholder="Leave empty to keep current" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border @error('password') border-rose-500 bg-rose-50/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        >
                        @error('password')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">Confirm New Password</label>
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            placeholder="Leave empty to keep current" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        >
                    </div>
                </div>
            </div>

            <!-- Profile Picture & Status -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 pb-2 border-b border-gray-100 dark:border-slate-700">
                    4. Profile & Account Status
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-2">Profile Picture</label>
                        <div class="flex items-center space-x-4">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" class="w-14 h-14 rounded-2xl object-cover border border-gray-200 dark:border-slate-700 shadow-sm">
                            @else
                                <div class="w-14 h-14 rounded-2xl bg-slate-800 text-white flex items-center justify-center font-bold text-base shadow-sm">
                                    {{ strtoupper(substr($user->full_name ?? $user->username, 0, 1)) }}
                                </div>
                            @endif
                            <input 
                                type="file" 
                                name="profile_picture" 
                                accept="image/*" 
                                class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 dark:file:bg-blue-950/50 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 file:cursor-pointer transition"
                            >
                        </div>
                        @error('profile_picture')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center pt-2">
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                value="1" 
                                {{ old('is_active', $user->is_active) ? 'checked' : '' }} 
                                class="w-4 h-4 text-blue-600 rounded border-gray-300 dark:border-slate-700 focus:ring-blue-500"
                            >
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Account Active (Staff can log in)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-end space-x-3">
                <a href="{{ route('users.index') }}" class="magnetic-btn px-5 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                    Cancel
                </a>
                <button type="submit" class="magnetic-btn px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/25 transition">
                    Update User
                </button>
            </div>
        </form>
    </div>

</div>
@endsection