@extends('layouts.app')

@section('title', 'Add New Product - POS System')
@section('page_heading', 'Add New Product')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header & Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">បង្កើតទំនិញថ្មី (Create Product)</h2>
            <p class="text-xs sm:text-sm text-gray-500">បញ្ចូលព័ត៌មានទំនិញ តម្លៃដើម តម្លៃលក់ និងចំនួនស្តុកដំបូង។</p>
        </div>
        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: General Info -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 pb-2 border-b border-gray-100 flex items-center">
                    <i class="fa-solid fa-box mr-2 text-blue-500"></i> ព័ត៌មានទូទៅ (General Information)
                </h3>
                
                <div class="space-y-4">
                    <!-- Product Name -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            ឈ្មោះទំនិញ (Product Name) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="product_name" 
                            value="{{ old('product_name') }}" 
                            placeholder="ឧ. ASUS ROG Strix G16 (2024)" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('product_name') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            required
                        >
                        @error('product_name')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category & Brand with Tom Select -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    ប្រភេទទំនិញ (Category) <span class="text-rose-500">*</span>
                                </label>
                                <span class="text-[11px] text-gray-400">វាយស្វែងរកប្រភេទ</span>
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
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                                    ម៉ាកយីហោ (Brand)
                                </label>
                                <span class="text-[11px] text-gray-400">វាយស្វែងរកម៉ាក</span>
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
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- SKU & Barcode -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">SKU (កូដទំនិញ)</label>
                            <input 
                                type="text" 
                                name="sku" 
                                value="{{ old('sku') }}" 
                                placeholder="ឧ. SKU-100234" 
                                class="w-full px-3.5 py-2.5 bg-gray-50 border @error('sku') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            >
                            @error('sku')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">បាកូដ (Barcode)</label>
                            <input 
                                type="text" 
                                name="barcode" 
                                value="{{ old('barcode') }}" 
                                placeholder="ឧ. 885912345678" 
                                class="w-full px-3.5 py-2.5 bg-gray-50 border @error('barcode') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            >
                            @error('barcode')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ការពិពណ៌នា / លក្ខណៈបច្ចេកទេស</label>
                        <textarea 
                            name="description" 
                            rows="3" 
                            placeholder="បញ្ជាក់ពី Specs ឬព័ត៌មានលម្អិតនៃទំនិញ..." 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        >{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Pricing & Inventory -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 pb-2 border-b border-gray-100 flex items-center">
                    <i class="fa-solid fa-tags mr-2 text-emerald-500"></i> តម្លៃ និងស្តុក (Pricing & Stock)
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Cost Price -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            តម្លៃដើម ($) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="cost_price" 
                            value="{{ old('cost_price', '0.00') }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('cost_price') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            required
                        >
                        @error('cost_price')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Selling Price -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            តម្លៃលក់ចេញ ($) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="selling_price" 
                            value="{{ old('selling_price', '0.00') }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('selling_price') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm font-bold text-emerald-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            required
                        >
                        @error('selling_price')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Quantity -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            ចំនួនស្តុកដំបូង <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="stock_quantity" 
                            value="{{ old('stock_quantity', 0) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('stock_quantity') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            required
                        >
                        @error('stock_quantity')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reorder Level -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">កម្រិតប្រកាសអាសន្នស្តុក (Reorder)</label>
                        <input 
                            type="number" 
                            name="reorder_level" 
                            value="{{ old('reorder_level', 5) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('reorder_level') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        >
                    </div>
                </div>
            </div>

            <!-- Section 3: Media & Settings -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 pb-2 border-b border-gray-100 flex items-center">
                    <i class="fa-solid fa-image mr-2 text-indigo-500"></i> រូបភាព និងការកំណត់ (Media & Settings)
                </h3>

                <div class="space-y-4">
                    <!-- Photo Upload with Live Preview -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">រូបភាពទំនិញ (Product Photo)</label>
                        <div class="flex items-center gap-4">
                            <div id="image_preview_box" class="w-14 h-14 rounded-2xl bg-gray-50 border border-dashed border-gray-300 flex items-center justify-center text-gray-400 overflow-hidden">
                                <i class="fa-regular fa-image text-xl" id="preview_icon"></i>
                                <img id="preview_img" src="#" alt="Preview" class="w-full h-full object-cover hidden">
                            </div>
                            <input 
                                type="file" 
                                name="photo" 
                                id="photo_input"
                                accept="image/*" 
                                class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer"
                            >
                        </div>
                        @error('photo')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
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
                                class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                            >
                            <span class="text-xs font-semibold text-gray-700">តាមដានតាមលេខ Serial Number (Laptop, GPU, Device...)</span>
                        </label>

                        <!-- Is Active -->
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                value="1" 
                                {{ old('is_active', true) ? 'checked' : '' }} 
                                class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                            >
                            <span class="text-xs font-semibold text-gray-700">ដាក់លក់ភ្លាមៗ (Active)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Actions -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition">
                    បោះបង់
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-blue-500/25 transition duration-150 transform active:scale-95 inline-flex items-center gap-1.5">
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
    .ts-control {
        background-color: #f9fafb !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 0.75rem !important;
        padding: 0.625rem 0.875rem !important;
        font-size: 0.875rem !important;
        box-shadow: none !important;
    }
    .ts-control.focus {
        background-color: #ffffff !important;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
    }
    .ts-dropdown {
        border-radius: 0.75rem !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        font-size: 0.875rem !important;
        z-index: 50 !important;
    }
    /* បង្ហាញកម្ពស់ប្រហែល 5 ជួរ និងមាន Scroll */
    .ts-dropdown .ts-dropdown-content {
        max-height: 180px !important;
        overflow-y: auto !important;
    }
    .ts-dropdown .option {
        padding: 8px 12px !important;
    }
    .ts-dropdown .active {
        background-color: #eff6ff !important;
        color: #2563eb !important;
        font-weight: 600;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tom Select សម្រាប់ Category
        new TomSelect('#category_select', {
            create: false,
            sortField: { field: "text", direction: "asc" },
            maxOptions: 50,
            placeholder: "-- វាយស្វែងរក ឬជ្រើសរើសប្រភេទ --",
            allowEmptyOption: true,
        });

        // Tom Select សម្រាប់ Brand
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
    });
</script>
@endsection