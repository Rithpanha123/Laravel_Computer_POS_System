@extends('layouts.app')

@section('title', 'Product List - POS System')
@section('page_heading', 'Inventory & Products')

@section('content')
<!-- Animated Ambient Gradient Background -->
<div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none opacity-40 dark:opacity-25">
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
    <div class="absolute top-20 -right-20 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-40 left-20 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
</div>

<div class="space-y-6 animate-fade-in relative">

    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md p-6 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-xl shadow-blue-500/5 stagger-item" style="animation-delay: 50ms;">
        <div>
            <h2 class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">ការគ្រប់គ្រងទំនិញក្នុងស្តុក (Product Management)</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">តាមដានចំនួនស្តុក តម្លៃដើម តម្លៃលក់ និងប្រភេទទំនិញ។ (សរុប៖ <span class="font-bold text-blue-600 dark:text-blue-400 count-up" data-target="{{ $products->total() }}">0</span> មុខ)</p>
        </div>
        <a href="{{ route('products.create') }}" class="magnetic-btn inline-flex items-center justify-center px-5 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-2xl shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all duration-200">
            <i class="fa-solid fa-plus mr-2"></i> បន្ថែមទំនិញថ្មី
        </a>
    </div>

    <!-- Search, Filter & Controls Form -->
    <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl p-4 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-lg shadow-gray-100/50 dark:shadow-none stagger-item" style="animation-delay: 100ms;">
        <form id="filterForm" method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <!-- Hidden per_page input -->
            <input type="hidden" name="per_page" id="formPerPageInput" value="{{ request('per_page', 10) }}">

            <!-- Search Text -->
            <div class="md:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 dark:text-gray-500 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="ស្វែងរកតាមឈ្មោះ, SKU, ឬ Barcode..." 
                    class="w-full pl-9 pr-3 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-blue-400 transition"
                >
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category_id" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-blue-400 transition cursor-pointer">
                    <option value="" class="dark:bg-slate-800">គ្រប់ប្រភេទទាំងអស់ (All Categories)</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->cate_id }}" class="dark:bg-slate-800" {{ request('category_id') == $category->cate_id ? 'selected' : '' }}>
                            {{ $category->cate_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Brand Filter -->
            <div>
                <select name="brand_id" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:focus:ring-blue-400 transition cursor-pointer">
                    <option value="" class="dark:bg-slate-800">គ្រប់ម៉ាកទាំងអស់ (All Brands)</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->brand_id }}" class="dark:bg-slate-800" {{ request('brand_id') == $brand->brand_id ? 'selected' : '' }}>
                            {{ $brand->brand_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="magnetic-btn w-full py-2.5 bg-slate-900 dark:bg-blue-600 hover:bg-slate-800 dark:hover:bg-blue-500 text-white text-xs font-semibold rounded-xl shadow-md transition">
                    ស្វែងរក
                </button>
                @if(request()->hasAny(['search', 'category_id', 'brand_id', 'per_page']))
                    <a href="{{ route('products.index') }}" class="magnetic-btn p-2.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-800 rounded-xl text-xs flex items-center justify-center transition" title="កំណត់ឡើងវិញ">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table Card with 3D Hover & Stagger Animation -->
    <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl rounded-2xl border border-gray-100 dark:border-slate-800 shadow-xl shadow-gray-100/60 dark:shadow-none overflow-hidden stagger-item hover-3d transition-all duration-300" style="animation-delay: 150ms;">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                        <th class="py-3.5 px-6">ទំនិញ (Product)</th>
                        <th class="py-3.5 px-6">ប្រភេទ / ម៉ាក</th>
                        <th class="py-3.5 px-6">តម្លៃដើម / លក់ចេញ</th>
                        <th class="py-3.5 px-6">ស្តុក (Stock)</th>
                        <th class="py-3.5 px-6">ស្ថានភាព</th>
                        <th class="py-3.5 px-6 text-right">សកម្មភាព (Actions)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800 text-gray-700 dark:text-gray-300">
                    @forelse($products as $index => $product)
                    <tr class="stagger-row hover:bg-blue-50/30 dark:hover:bg-slate-800/60 transition-all duration-200" style="animation-delay: {{ 150 + ($index * 30) }}ms">
                        <!-- Product Info & Photo -->
                        <td class="py-3.5 px-6">
                            <div class="flex items-center space-x-3">
                                @if($product->photo)
                                    <img src="{{ asset('storage/' . $product->photo) }}" class="w-10 h-10 rounded-xl object-cover border border-gray-200 dark:border-slate-700 shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-base border border-slate-200 dark:border-slate-700">
                                        <i class="fa-solid fa-box"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-800 dark:text-gray-100">{{ $product->product_name }}</p>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500">SKU: <span class="font-mono text-gray-600 dark:text-gray-400 font-semibold">{{ $product->sku ?? $product->product_code ?? 'N/A' }}</span></p>
                                </div>
                            </div>
                        </td>

                        <!-- Category / Brand -->
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-900/50">
                                {{ $product->category->cate_name ?? 'ទូទៅ' }}
                            </span>
                            @if($product->brand)
                                <span class="block text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">{{ $product->brand->brand_name }}</span>
                            @endif
                        </td>

                        <!-- Cost / Selling Price -->
                        <td class="py-3.5 px-6">
                            <p class="font-bold text-emerald-600 dark:text-emerald-400">${{ number_format($product->selling_price, 2) }}</p>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500">ដើម: ${{ number_format($product->cost_price, 2) }}</p>
                        </td>

                        <!-- Stock Level -->
                        <td class="py-3.5 px-6">
                            @if($product->stock_quantity <= ($product->reorder_level ?? 5))
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/50 px-2.5 py-1 rounded-lg border border-amber-200 dark:border-amber-900/50">
                                    <i class="fa-solid fa-triangle-exclamation"></i> <span class="count-up" data-target="{{ $product->stock_quantity }}">{{ $product->stock_quantity }}</span> ក្នុងស្តុក
                                </span>
                            @else
                                <span class="font-semibold text-gray-800 dark:text-gray-200"><span class="count-up" data-target="{{ $product->stock_quantity }}">{{ $product->stock_quantity }}</span> ក្នុងស្តុក</span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="py-3.5 px-6">
                            @if($product->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> កំពុងលក់
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> ផ្អាកលក់
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-6 text-right space-x-1">
                            <a href="{{ route('products.edit', $product->product_id) }}" class="p-2 text-gray-400 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-800 rounded-lg transition inline-flex items-center transform hover:scale-110" title="កែសម្រួល">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->product_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="button" 
                                    onclick="confirmDeleteProduct(this, '{{ $product->product_name }}')" 
                                    class="p-2 text-gray-400 dark:text-gray-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-slate-800 rounded-lg transition inline-flex items-center transform hover:scale-110" 
                                    title="លុបចេញ"
                                >
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 dark:text-gray-500">
                            <i class="fa-solid fa-boxes-stacked text-3xl text-gray-300 dark:text-slate-700 mb-2 animate-bounce"></i>
                            <p class="text-sm font-medium">មិនមានទិន្នន័យទំនិញត្រូវបានរកឃើញទេ។</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Styled Pagination Footer with Per Page Dropdown -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500 dark:text-gray-400">
            <div>
                បង្ហាញពី <span class="font-bold text-gray-800 dark:text-gray-200">{{ $products->firstItem() ?? 0 }}</span> ដល់ <span class="font-bold text-gray-800 dark:text-gray-200">{{ $products->lastItem() ?? 0 }}</span> នៃទំនិញសរុប <span class="font-bold text-gray-800 dark:text-gray-200">{{ $products->total() }}</span> មុខ
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Dropdown Selector -->
                <div class="flex items-center gap-1.5 whitespace-nowrap bg-white dark:bg-slate-800 px-3 py-1 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">បង្ហាញ៖</span>
                    <select 
                        id="perPageSelectDropdown" 
                        onchange="changePerPage(this.value)" 
                        class="bg-transparent border-none text-xs font-bold text-gray-800 dark:text-gray-200 outline-none cursor-pointer focus:ring-0 py-0.5 pr-6 pl-1"
                    >
                        <option value="5" class="dark:bg-slate-800" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" class="dark:bg-slate-800" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" class="dark:bg-slate-800" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="100" class="dark:bg-slate-800" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-gray-500 dark:text-gray-400 font-medium">ជួរ</span>
                </div>

                <!-- Page Links -->
                <div class="dark:text-gray-300">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Styles & Animations -->
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

/* 3D Card Hover Effect */
.hover-3d {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-3d:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 40px -15px rgba(59, 130, 246, 0.12);
}
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function changePerPage(value) {
        document.getElementById('formPerPageInput').value = value;
        document.getElementById('filterForm').submit();
    }

    function confirmDeleteProduct(button, productName) {
        const isDark = document.documentElement.classList.contains('dark');
        
        Swal.fire({
            title: '<span class="text-2xl font-black ' + (isDark ? 'text-gray-100' : 'text-gray-800') + '">តើអ្នកពិតជាចង់លុបមែនទេ?</span>',
            html: `
                <div class="mt-2 text-sm ${isDark ? 'text-gray-300' : 'text-gray-500'} leading-relaxed">
                    អ្នកកំពុងស្នើសុំលុបទំនិញឈ្មោះ <strong class="text-rose-600 dark:text-rose-400 font-bold">"${productName}"</strong> ចេញពីស្តុក។<br>
                    <span class="text-xs text-amber-600 dark:text-amber-400 font-medium">⚠️ ទិន្នន័យដែលលុបហើយមិនអាចយកមកវិញបានទេ!</span>
                </div>
            `,
            icon: 'warning',
            iconColor: '#e11d48',
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-trash-can mr-2"></i> បាទ/ចាស, លុបឥឡូវនេះ',
            cancelButtonText: 'ថយក្រោយវិញ',
            confirmButtonColor: '#e11d48',
            cancelButtonColor: isDark ? '#334155' : '#64748b',
            reverseButtons: true,
            focusCancel: true,
            width: '30rem',
            padding: '1.75rem',
            background: isDark ? '#0f172a' : '#fff',
            color: isDark ? '#f8fafc' : '#1e293b',
            backdrop: `rgba(15, 23, 42, 0.65)`,
            customClass: {
                popup: 'rounded-3xl shadow-2xl border ' + (isDark ? 'border-slate-800' : 'border-gray-100'),
                confirmButton: 'rounded-xl px-5 py-2.5 text-sm font-bold shadow-lg shadow-rose-500/30',
                cancelButton: 'rounded-xl px-5 py-2.5 text-sm font-semibold transition'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // Count-Up Animation & Magnetic Buttons
    document.addEventListener("DOMContentLoaded", () => {
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

        // Magnetic Effect
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