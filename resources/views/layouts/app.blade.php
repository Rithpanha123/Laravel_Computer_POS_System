<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Computer POS System')</title>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    
    <!-- Font Awesome Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
    @font-face {
        font-family: 'Kh Battambang';
        src: url('{{ asset('fonts/Kh-Battambang.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
        font-display: swap;
    }

    * { font-family: 'Kh Battambang', 'Inter', sans-serif; }
    body { background-color: #f8fafc; }
    ::-webkit-scrollbar { height: 8px; width: 8px; }
    ::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 9999px; }

    /* 🌊 Wave & Page Transition Animation */
    @keyframes waveTransition {
        0% { opacity: 0; transform: translateY(15px) scale(0.99); filter: blur(4px); }
        50% { filter: blur(1px); }
        100% { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
    }

    /* 🌀 Morphing Navbar Effect */
    @keyframes morphNav {
        0% { opacity: 0; transform: translateY(-10px); backdrop-filter: blur(0px); }
        100% { opacity: 1; transform: translateY(0); backdrop-filter: blur(12px); }
    }

    /* ✨ Glowing Effects */
    .glow-effect {
        transition: all 0.3s ease;
    }
    .glow-effect:hover {
        box-shadow: 0 0 20px rgba(37, 99, 235, 0.25);
    }

    .glow-alert {
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.15);
    }

    .animate-wave-page {
        animation: waveTransition 0.5s cubic-bezier(0.25, 1, 0.5, 1) forwards;
    }

    .animate-morph-nav {
        animation: morphNav 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @media (prefers-reduced-motion: reduce) {
        * { animation: none !important; transition: none !important; }
    }
</style>
</head>
<body class="bg-gray-50 dark:bg-slate-900 text-gray-800 dark:text-gray-100 font-sans antialiased" x-data="{ sidebarOpen: true }">

    <div class="flex h-screen overflow-hidden">
        <!-- 1. Sidebar with Glow & Slide Transition -->
        <div class="transition-all duration-300 ease-in-out border-r border-gray-100 dark:border-slate-800" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0 md:w-20'">
            @include('layouts.partials.sidebar')
        </div>

        <!-- 2. Main Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- 🌀 Morphing Navbar -->
            <div class="animate-morph-nav bg-white/80 dark:bg-slate-800/80 sticky top-0 z-20 border-b border-gray-100 dark:border-slate-700/50">
                @include('layouts.partials.navbar')
            </div>

            <!-- 🌊 Wave Page Content Transition -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 animate-wave-page">
                <!-- Session Alerts with ✨ Glowing Effects -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50/90 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm font-medium rounded-2xl flex items-center shadow-sm glow-alert animate-wave-page">
                        <i class="fa-solid fa-circle-check text-emerald-500 mr-3 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-50/90 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm font-medium rounded-2xl flex items-center shadow-sm glow-effect animate-wave-page">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 mr-3 text-lg"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    
<!-- Global SweetAlert2 Flash Notifications -->
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '<span class="text-xl font-bold text-gray-800">ជោគជ័យ!</span>',
        text: "{{ session('success') }}",
        confirmButtonText: 'យល់ព្រម',
        confirmButtonColor: '#2563eb',
        width: '28rem',
        padding: '1.75rem',
        timer: 2500,
        timerProgressBar: true,
        customClass: {
            popup: 'rounded-3xl shadow-2xl border border-gray-100',
            confirmButton: 'rounded-xl px-6 py-2.5 text-sm font-bold shadow-lg shadow-blue-500/25'
        }
    });
</script>
@endif

<!-- JavaScript សម្រាប់គ្រប់គ្រង Dark/Light Mode យ៉ាងរលូន -->
<script>
    function toggleDarkMode() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }
</script>

</body>
</html>