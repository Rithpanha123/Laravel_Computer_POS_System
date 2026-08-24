<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Computer POS System</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 min-h-screen flex items-center justify-center p-4 selection:bg-blue-500 selection:text-white">

    <div class="w-full max-w-md">
        <!-- Logo & Branding -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white text-2xl shadow-xl shadow-blue-500/20 mb-3 ring-4 ring-white/10">
                <i class="fa-solid fa-laptop-code"></i>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white">Computer POS System</h1>
            <p class="text-slate-400 text-sm mt-1">Sign in to your dashboard to manage sales</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/95 backdrop-blur-md rounded-3xl p-8 shadow-2xl border border-white/20">
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Username or Email -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                        Username or Email
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 text-sm">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <input 
                            type="text" 
                            name="login" 
                            value="{{ old('login') }}" 
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border @error('login') border-rose-500 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition duration-150" 
                            placeholder="admin or user@example.com" 
                            required 
                            autofocus
                        >
                    </div>
                    @error('login')
                        <p class="text-rose-500 text-xs font-medium mt-1.5 flex items-center">
                            <i class="fa-solid fa-circle-exclamation mr-1.5"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Password
                        </label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 text-sm">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input 
                            type="password" 
                            name="password" 
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border @error('password') border-rose-500 bg-rose-50/30 @else border-slate-200 @enderror rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition duration-150" 
                            placeholder="••••••••" 
                            required
                        >
                    </div>
                    @error('password')
                        <p class="text-rose-500 text-xs font-medium mt-1.5 flex items-center">
                            <i class="fa-solid fa-circle-exclamation mr-1.5"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center space-x-2.5 cursor-pointer select-none">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 focus:ring-2"
                        >
                        <span class="text-xs font-medium text-slate-600">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full flex items-center justify-center py-3.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-500/25 transition duration-150 transform active:scale-[0.99] focus:outline-none focus:ring-4 focus:ring-blue-200"
                    >
                        <span>Sign In to System</span>
                        <i class="fa-solid fa-arrow-right-to-bracket ml-2 text-sm"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Note -->
        <p class="text-center text-xs text-slate-500 mt-8">
            &copy; 2026 Computer POS System. All rights reserved.
        </p>
    </div>

</body>
</html>