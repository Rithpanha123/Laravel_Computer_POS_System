@extends('layouts.app')

@section('title', 'Edit Customer - POS System')
@section('page_heading', 'Edit Customer Profile')

@section('content')
<!-- Animated Ambient Gradient Background -->
<div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none opacity-40 dark:opacity-25">
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
    <div class="absolute top-20 -right-20 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-40 left-20 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
</div>

<div class="max-w-2xl mx-auto space-y-6 animate-fade-in relative">

    <!-- Top Heading with Stagger Animation -->
    <div class="flex items-center justify-between stagger-item" style="animation-delay: 50ms;">
        <div>
            <h2 class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">កែប្រែព័ត៌មានអតិថិជន៖ {{ $customer->customer_name }}</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">កូដសម្គាល់៖ <span class="font-mono font-bold text-blue-600 dark:text-blue-400">#<span class="count-up" data-target="{{ $customer->customer_id }}">0</span></span></p>
        </div>
        <a href="{{ route('customers.index') }}" class="magnetic-btn px-4 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition shadow-sm inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <!-- Error Alert Banner -->
    @if ($errors->any())
        <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/50 rounded-2xl stagger-item shadow-lg shadow-rose-500/5" style="animation-delay: 100ms;">
            <div class="flex items-center gap-2 text-rose-700 dark:text-rose-400 font-bold text-xs mb-1">
                <i class="fa-solid fa-triangle-exclamation"></i> សូមពិនិត្យមើលកំហុសខាងក្រោម៖
            </div>
            <ul class="list-disc list-inside text-xs text-rose-600 dark:text-rose-300 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 3D Glowing Form Card -->
    <form action="{{ route('customers.update', $customer->customer_id) }}" method="POST" class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl rounded-3xl border border-gray-100 dark:border-slate-800 shadow-2xl shadow-blue-500/5 dark:shadow-none p-6 sm:p-8 space-y-5 stagger-item hover-3d transition-all duration-300" style="animation-delay: 150ms;">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                ឈ្មោះអតិថិជន (Customer Name) <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                name="customer_name" 
                value="{{ old('customer_name', $customer->customer_name) }}" 
                required 
                class="w-full px-4 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border border-gray-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 outline-none transition"
            >
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                    លេខទូរស័ព្ទ (Phone Number) <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="phone" 
                    value="{{ old('phone', $customer->phone) }}" 
                    required 
                    class="w-full px-4 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border border-gray-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 outline-none transition"
                >
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                    អ៊ីមែល (Email)
                </label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email', $customer->email) }}" 
                    class="w-full px-4 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border border-gray-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 outline-none transition"
                >
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                អាសយដ្ឋាន (Address)
            </label>
            <textarea 
                name="address" 
                rows="3" 
                class="w-full px-4 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border border-gray-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 outline-none transition"
            >{{ old('address', $customer->address) }}</textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 dark:border-slate-800 flex items-center justify-between">
            <button 
                type="button" 
                onclick="if(confirm('តើអ្នកពិតជាចង់លុបអតិថិជននេះចេញពីប្រព័ន្ធមែនទេ?')) { document.getElementById('delete-customer-form').submit(); }"
                class="magnetic-btn px-4 py-2.5 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/50 text-rose-600 dark:text-rose-400 text-xs font-semibold rounded-xl transition flex items-center"
            >
                <i class="fa-solid fa-trash-can mr-1.5"></i> លុបអតិថិជន
            </button>

            <div class="flex items-center space-x-3">
                <a href="{{ route('customers.index') }}" class="magnetic-btn px-5 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                    បោះបង់
                </a>
                <button type="submit" class="magnetic-btn px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-blue-500/25 transition transform hover:-translate-y-0.5">
                    ធ្វើបច្ចុប្បន្នភាពទិន្នន័យ
                </button>
            </div>
        </div>
    </form>

    <form id="delete-customer-form" action="{{ route('customers.destroy', $customer->customer_id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

</div>

<!-- Custom Styles for Animations, Glowing Effects, & 3D Parallax -->
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

.animate-blob {
    animation: blob 8s infinite ease-in-out;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

/* 3D Card Hover Effect */
.hover-3d {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-3d:hover {
    transform: translateY(-3px) scale(1.005);
    box-shadow: 0 20px 40px -15px rgba(59, 130, 246, 0.15);
}
</style>

<!-- Floating Particles & Count-Up Script -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Count-up handler
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
        
        if (target > 0) {
            updateCount();
        } else {
            counter.innerText = target;
        }
    });

    // Simple Magnetic Button Effect
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