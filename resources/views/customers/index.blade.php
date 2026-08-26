@extends('layouts.app')

@section('title', 'Customer List - POS System')
@section('page_heading', 'Customer Directory')

@section('content')
<!-- Animated Ambient Gradient Background & Floating Particles -->
<div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none opacity-40 dark:opacity-25">
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
    <div class="absolute top-20 -right-20 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-40 left-20 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
</div>

<div class="space-y-6 animate-fade-in relative">

    <!-- Header & Action Button with Light/Dark Mode Support & 3D Vibe -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md p-6 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-xl shadow-blue-500/5 stagger-item" style="animation-delay: 50ms;">
        <div>
            <h2 class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">បញ្ជីអតិថិជន (Customers)</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">គ្រប់គ្រងព័ត៌មានអតិថិជន តាមដានប្រវត្តិទិញទំនិញ និងសេវាជួសជុល (សរុប៖ <span id="totalCustomerCount" class="font-bold text-blue-600 dark:text-blue-400 count-up" data-target="{{ $totalCustomers }}">{{ $totalCustomers }}</span> នាក់)</p>
        </div>
        <a href="{{ route('customers.create') }}" class="magnetic-btn inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-2xl shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all duration-200">
            <i class="fa-solid fa-user-plus mr-2"></i> ចុះឈ្មោះអតិថិជនថ្មី
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl p-4 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-lg shadow-gray-100/50 dark:shadow-none stagger-item" style="animation-delay: 100ms;">
        <form id="customerFilterForm" method="GET" action="{{ route('customers.index') }}" class="flex gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 dark:text-gray-500 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="ស្វែងរកតាមឈ្មោះ លេខកូដ លេខទូរស័ព្ទ អ៊ីមែល..." 
                    class="w-full pl-9 pr-3 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border border-gray-200/80 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:bg-white dark:focus:bg-slate-800 transition"
                >
            </div>
            <button type="submit" class="magnetic-btn px-5 py-2.5 bg-slate-900 dark:bg-blue-600 hover:bg-slate-800 dark:hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-md transition-all duration-200 hover:-translate-y-0.5">
                ស្វែងរក
            </button>
            @if(request()->hasAny(['search', 'per_page']))
                <a href="{{ route('customers.index') }}" class="magnetic-btn p-2.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl text-xs flex items-center justify-center transition" title="កំណត់ឡើងវិញ">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Customers Table Section with 3D Hover & Stagger Entry -->
    <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl rounded-2xl border border-gray-100 dark:border-slate-800 shadow-xl shadow-gray-100/60 dark:shadow-none overflow-hidden stagger-item hover-3d transition-all duration-300" style="animation-delay: 150ms;">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        <th class="py-3.5 px-6">អតិថិជន</th>
                        <th class="py-3.5 px-6">លេខទូរស័ព្ទ</th>
                        <th class="py-3.5 px-6">អ៊ីមែល</th>
                        <th class="py-3.5 px-6">អាសយដ្ឋាន</th>
                        <th class="py-3.5 px-6 text-center">ប្រវត្តិទិញ/ជួសជុល</th>
                        <th class="py-3.5 px-6 text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-gray-300">
                    @forelse($customers as $index => $customer)
                    <tr class="stagger-row hover:bg-blue-50/30 dark:hover:bg-slate-800/60 transition-all duration-200" style="animation-delay: {{ 150 + ($index * 40) }}ms">
                        <td class="py-3.5 px-6">
                            <span class="font-bold text-gray-800 dark:text-gray-100">{{ $customer->customer_name }}</span>
                            <span class="block font-mono text-[11px] text-blue-600 dark:text-blue-400 font-semibold">#{{ $customer->customer_code ?? 'CUST-' . $customer->customer_id }}</span>
                        </td>
                        <td class="py-3.5 px-6 font-semibold text-gray-700 dark:text-gray-300">
                            {{ $customer->phone }}
                        </td>
                        <td class="py-3.5 px-6 text-gray-500 dark:text-gray-400">
                            {{ $customer->email ?: '-' }}
                        </td>
                        <td class="py-3.5 px-6 text-gray-500 dark:text-gray-400 max-w-xs truncate">
                            {{ $customer->address ?: '-' }}
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            <span class="inline-block px-2.5 py-1 rounded-lg text-xs bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 font-semibold mr-1 shadow-sm">
                                <span class="count-up" data-target="{{ $customer->sales_count ?? 0 }}">{{ $customer->sales_count ?? 0 }}</span> Orders
                            </span>
                            <span class="inline-block px-2.5 py-1 rounded-lg text-xs bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 font-semibold shadow-sm">
                                <span class="count-up" data-target="{{ $customer->repairs_count ?? 0 }}">{{ $customer->repairs_count ?? 0 }}</span> Repairs
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-right space-x-1">
                            <a href="{{ route('customers.edit', $customer->customer_id) }}" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-800 rounded-xl transition inline-flex items-center transform hover:scale-110" title="កែប្រែ">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('customers.destroy', $customer->customer_id) }}" method="POST" class="inline-block" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបអតិថិជន «{{ $customer->customer_name }}» មែនទេ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-slate-800 rounded-xl transition inline-flex items-center transform hover:scale-110" title="លុប">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 dark:text-gray-500">
                            <i class="fa-solid fa-users text-3xl text-gray-300 dark:text-slate-700 mb-2 animate-bounce"></i>
                            <p class="text-sm font-medium">មិនទាន់មានទិន្នន័យអតិថិជននៅឡើយ</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500 dark:text-gray-400">
            <div>
                បង្ហាញពី <span class="font-bold text-gray-700 dark:text-gray-300">{{ $customers->firstItem() ?? 0 }}</span> ដល់ <span class="font-bold text-gray-700 dark:text-gray-300">{{ $customers->lastItem() ?? 0 }}</span> នៃទិន្នន័យសរុប <span class="font-bold text-gray-700 dark:text-gray-300">{{ $customers->total() }}</span> នាក់
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-1.5 whitespace-nowrap bg-white dark:bg-slate-800 px-3 py-1 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">បង្ហាញ៖</span>
                    <select 
                        name="per_page" 
                        form="customerFilterForm" 
                        onchange="document.getElementById('customerFilterForm').submit()" 
                        class="bg-transparent border-none text-xs font-bold text-gray-800 dark:text-gray-200 outline-none cursor-pointer focus:ring-0 py-0.5 pr-6 pl-1"
                    >
                        <option value="5" class="dark:bg-slate-800" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" class="dark:bg-slate-800" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" class="dark:bg-slate-800" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="100" class="dark:bg-slate-800" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-gray-500 dark:text-gray-400 font-medium">ជួរ</span>
                </div>

                <div class="dark:text-gray-300">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Styles and Animations -->
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes blob {
    0%, 100% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
}

.animate-fade-in {
    animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.stagger-item {
    opacity: 0;
    animation: fadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.stagger-row {
    opacity: 0;
    animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}

.animate-blob {
    animation: blob 8s infinite ease-in-out;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

/* 3D Card Hover Depth Effect */
.hover-3d {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-3d:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 40px -15px rgba(59, 130, 246, 0.12);
}
</style>

<!-- Count-Up & Magnetic Button Script -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Count-Up Animation
    const counters = document.querySelectorAll('.count-up');
    counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        const duration = 1000;
        const increment = target / (duration / 16);
        
        let current = 0;
        const updateCount = () => {
            current += increment;
            if (current < target) {
                counter.innerText = Math.ceil(current);
                requestAnimationFrame(updateCount);
            } else {
                counter.innerText = target;
            }
        };
        
        if(target > 0) {
            updateCount();
        } else {
            counter.innerText = target;
        }
    });

    // Magnetic Interactive Buttons
    const magneticBtns = document.querySelectorAll('.magnetic-btn');
    magneticBtns.forEach(btn => {
        btn.addEventListener('mousemove', (e) => {
            const rect = btn.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            btn.style.transform = `translate(${x * 0.15}px, ${y * 0.15}px)`;
        });
        btn.addEventListener('mouseleave', () => {
            btn.style.transform = 'translate(0px, 0px)';
        });
    });
});
</script>
@endsection