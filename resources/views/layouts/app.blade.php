<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Computer POS System')</title>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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

    @media (prefers-reduced-motion: reduce) {
        * { animation: none !important; transition: none !important; }
    }
</style>
</head>
<body class="bg-gray-50/50 font-sans text-gray-800 antialiased" x-data="{ sidebarOpen: true }">

    <div class="flex h-screen overflow-hidden">
        <!-- 1. Include Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- 2. Main Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Include Navbar -->
            @include('layouts.partials.navbar')

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <!-- Session Alerts -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium rounded-2xl flex items-center shadow-sm">
                        <i class="fa-solid fa-circle-check text-emerald-500 mr-3 text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium rounded-2xl flex items-center shadow-sm">
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
        confirmButtonColor: '#2563eb', // Blue-600
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
</body>
</html>