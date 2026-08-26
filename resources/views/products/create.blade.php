@extends('layouts.app')

@section('title', 'Add New Product - POS System')
@section('page_heading', 'Add New Product')

@section('content')
<!-- Animated Ambient Gradient Background & Floating Particles -->
<div class="absolute inset-0 -z-10 overflow-hidden pointer-events-none opacity-40 dark:opacity-25">
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
    <div class="absolute top-20 -right-20 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-40 left-20 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
</div>

<div class="max-w-5xl mx-auto space-y-6 animate-fade-in relative">

    <!-- Header & Back Button -->
    <div class="flex items-center justify-between stagger-item" style="animation-delay: 50ms;">
        <div>
            <h2 class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">បង្កើតទំនិញថ្មី (Create Product)</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">បញ្ចូលព័ត៌មានទំនិញ តម្លៃដើម តម្លៃលក់ និងចំនួនស្តុកដំបូង។</p>
        </div>
        <a href="{{ route('products.index') }}" class="magnetic-btn inline-flex items-center px-4 py-2.5 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <!-- Form Container with 3D Hover Effect -->
    <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl rounded-3xl border border-gray-100 dark:border-slate-800 shadow-2xl shadow-blue-500/5 dark:shadow-none p-6 sm:p-8 stagger-item hover-3d transition-all duration-300" style="animation-delay: 100ms;">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: General Info -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 pb-2 border-b border-gray-100 dark:border-slate-800 flex items-center">
                    <i class="fa-solid fa-box mr-2 text-blue-500"></i> ព័ត៌មានទូទៅ (General Information)
                </h3>
                
                <div class="space-y-4">
                    <!-- Product Name -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                            ឈ្មោះទំនិញ (Product Name) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="product_name" 
                            value="{{ old('product_name') }}" 
                            placeholder="ឧ. ASUS ROG Strix G16 (2024)" 
                            class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('product_name') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            required
                        >
                        @error('product_name')
                            <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category & Brand with Tom Select -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                    ប្រភេទទំនិញ (Category) <span class="text-rose-500">*</span>
                                </label>
                                <span class="text-[11px] text-gray-400 dark:text-gray-500">វាយស្វែងរកប្រភេទ</span>
                            </div>
                            <select 
                                id="category_select" 
                                name="category_id" 
                                required
                            >
                                <option value="">-- ជ្រើសរើសប្រភេទ (Category) --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->cate_id }}" {{ old('category_id') == $category->cate_id ? 'selected' : '' }}>
                                        {{ $category->cate_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                    ម៉ាកយីហោ (Brand)
                                </label>
                                <span class="text-[11px] text-gray-400 dark:text-gray-500">វាយស្វែងរកម៉ាក</span>
                            </div>
                            <select 
                                id="brand_select" 
                                name="brand_id"
                            >
                                <option value="">-- ជ្រើសរើសម៉ាក (Optional) --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->brand_id }}" {{ old('brand_id') == $brand->brand_id ? 'selected' : '' }}>
                                        {{ $brand->brand_name ?? $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- SKU & Barcode -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">SKU (កូដទំនិញ)</label>
                            <input 
                                type="text" 
                                name="sku" 
                                value="{{ old('sku') }}" 
                                placeholder="ឧ. SKU-100234" 
                                class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('sku') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            >
                            @error('sku')
                                <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">បាកូដ (Barcode)</label>
                            <input 
                                type="text" 
                                name="barcode" 
                                value="{{ old('barcode') }}" 
                                placeholder="ឧ. 885912345678" 
                                class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('barcode') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            >
                            @error('barcode')
                                <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">ការពិពណ៌នា / លក្ខណៈបច្ចេកទេស</label>
                        <textarea 
                            name="description" 
                            rows="3" 
                            placeholder="បញ្ជាក់ពី Specs ឬព័ត៌មានលម្អិតនៃទំនិញ..." 
                            class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                        >{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Pricing & Inventory -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 pb-2 border-b border-gray-100 dark:border-slate-800 flex items-center">
                    <i class="fa-solid fa-tags mr-2 text-emerald-500"></i> តម្លៃ និងស្តុក (Pricing & Stock)
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Cost Price -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                            តម្លៃដើម ($) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="cost_price" 
                            value="{{ old('cost_price', '0.00') }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('cost_price') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm font-bold text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            required
                        >
                        @error('cost_price')
                            <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Selling Price -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                            តម្លៃលក់ចេញ ($) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="selling_price" 
                            value="{{ old('selling_price', '0.00') }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('selling_price') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm font-bold text-emerald-600 dark:text-emerald-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            required
                        >
                        @error('selling_price')
                            <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Quantity -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                            ចំនួនស្តុកដំបូង <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="stock_quantity" 
                            value="{{ old('stock_quantity', 0) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('stock_quantity') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm font-bold text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            required
                        >
                        @error('stock_quantity')
                            <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reorder Level -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">កម្រិតប្រកាសអាសន្នស្តុក (Reorder)</label>
                        <input 
                            type="number" 
                            name="reorder_level" 
                            value="{{ old('reorder_level', 5) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                        >
                    </div>
                </div>
            </div>

            <!-- Section 3: Media & Settings -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 pb-2 border-b border-gray-100 dark:border-slate-800 flex items-center">
                    <i class="fa-solid fa-image mr-2 text-indigo-500"></i> រូបភាព និងការកំណត់ (Media & Settings)
                </h3>

                <div class="space-y-4">
                    <!-- Photo Upload with Live Preview -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">រូបភាពទំនិញ (Product Photo)</label>
                        <div class="flex items-center gap-4">
                            <div id="image_preview_box" class="w-14 h-14 rounded-2xl bg-gray-50 dark:bg-slate-800 border border-dashed border-gray-300 dark:border-slate-700 flex items-center justify-center text-gray-400 dark:text-gray-500 overflow-hidden">
                                <i class="fa-regular fa-image text-xl" id="preview_icon"></i>
                                <img id="preview_img" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                            </div>
                            <input 
                                type="file" 
                                name="photo" 
                                id="photo_input"
                                accept="image/*" 
                                class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 dark:file:bg-blue-950/50 file:text-blue-700 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/50 file:cursor-pointer"
                            >
                        </div>
                        @error('photo')
                            <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Checkboxes -->
                    <div class="flex flex-wrap items-center gap-6 pt-2">
                        <!-- Is Serialized -->
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="is_serialized" 
                                value="1" 
                                {{ old('is_serialized') ? 'checked' : '' }} 
                                class="w-4 h-4 text-blue-600 rounded border-gray-300 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 focus:ring-blue-500"
                            >
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">តាមដានតាមលេខ Serial Number (Laptop, GPU, Device...)</span>
                        </label>

                        <!-- Is Active -->
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                value="1" 
                                {{ old('is_active', true) ? 'checked' : '' }} 
                                class="w-4 h-4 text-blue-600 rounded border-gray-300 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 focus:ring-blue-500"
                            >
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">ដាក់លក់ភ្លាមៗ (Active)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Actions -->
            <div class="pt-4 border-t border-gray-100 dark:border-slate-800 flex items-center justify-end space-x-3">
                <a href="{{ route('products.index') }}" class="magnetic-btn px-5 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                    បោះបង់
                </a>
                <button type="submit" class="magnetic-btn px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-blue-500/25 transition duration-150 transform hover:-translate-y-0.5 inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-floppy-disk"></i> រក្សាទុកទំនិញ
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Tom Select CSS & JS CDN -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

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

/* 3D Card Hover Depth Effect */
.hover-3d {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-3d:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 40px -15px rgba(59, 130, 246, 0.12);
}

/* Tom Select Light/Dark Customization */
.ts-control {
    background-color: rgba(249, 250, 251, 0.75) !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 0.75rem !important;
    padding: 0.625rem 0.875rem !important;
    font-size: 0.875rem !important;
    box-shadow: none !important;
}
.dark .ts-control {
    background-color: rgba(30, 41, 59, 0.75) !important;
    border-color: #334155 !important;
    color: #f8fafc !important;
}
.ts-control.focus {
    background-color: #ffffff !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
}
.dark .ts-control.focus {
    background-color: #1e293b !important;
    border-color: #60a5fa !important;
}
.ts-dropdown {
    border-radius: 0.75rem !important;
    border: 1px solid #e5e7eb !important;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    font-size: 0.875rem !important;
    z-index: 50 !important;
}
.dark .ts-dropdown {
    background-color: #0f172a !important;
    border-color: #334155 !important;
    color: #f8fafc !important;
}
.ts-dropdown .ts-dropdown-content {
    max-height: 180px !important;
    overflow-y: auto !important;
}
.ts-dropdown .option {
    padding: 8px 12px !important;
}
.dark .ts-dropdown .option {
    color: #cbd5e1 !important;
}
.ts-dropdown .active {
    background-color: #eff6ff !important;
    color: #2563eb !important;
    font-weight: 600;
}
.dark .ts-dropdown .active {
    background-color: #1e3a8a !important;
    color: #93c5fd !important;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tom Select for Category
        new TomSelect('#category_select', {
            create: false,
            sortField: { field: "text", direction: "asc" },
            maxOptions: 50,
            placeholder: "-- វាយស្វែងរក ឬជ្រើសរើសប្រភេទ --",
            allowEmptyOption: true,
        });

        // Tom Select for Brand
        new TomSelect('#brand_select', {
            create: false,
            sortField: { field: "text", direction: "asc" },
            maxOptions: 50,
            placeholder: "-- វាយស្វែងរក ឬជ្រើសរើសម៉ាក --",
            allowEmptyOption: true,
        });

        // Live Image Preview Handler
        const photoInput = document.getElementById('photo_input');
        const previewImg = document.getElementById('preview_img');
        const previewIcon = document.getElementById('preview_icon');

        photoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewImg.classList.remove('hidden');
                    previewIcon.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                previewImg.src = '#';
                previewImg.classList.add('hidden');
                previewIcon.classList.remove('hidden');
            }
        });

        // Magnetic Effect Script
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