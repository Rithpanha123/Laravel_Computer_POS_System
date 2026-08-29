@extends('layouts.app')

@section('title', 'Edit Product - POS System')
@section('page_heading', 'Edit Product')

@section('content')
<!-- Load TomSelect CSS ផ្ទាល់ក្នុងទំព័រ -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-wrapper {
        width: 100% !important;
        position: relative !important;
    }
    .ts-control {
        border-radius: 0.75rem !important;
        border: 1px solid #d1d5db !important;
        background-color: #ffffff !important;
        font-size: 0.875rem !important;
        padding: 0.55rem 0.875rem !important;
        min-height: 42px !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
    }
    .ts-control input {
        font-size: 0.875rem !important;
    }
    .ts-dropdown {
        background-color: #ffffff !important;
        border-radius: 0.75rem !important;
        font-size: 0.875rem !important;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2) !important;
        z-index: 99999 !important;
        margin-top: 4px !important;
    }
    .ts-dropdown .ts-dropdown-content {
        max-height: 180px !important;
        background-color: #ffffff !important;
    }
    .ts-dropdown .option {
        padding: 0.55rem 0.875rem !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #334155 !important;
    }
    .ts-dropdown .option:last-child {
        border-bottom: none !important;
    }
    .ts-dropdown .option.active, .ts-dropdown .option:hover {
        background-color: #eff6ff !important;
        color: #2563eb !important;
        font-weight: 600 !important;
    }
</style>

<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header & Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">កែប្រែទំនិញ៖ {{ $product->product_name ?? $product->name }}</h2>
            <p class="text-xs sm:text-sm text-gray-500">កែប្រែព័ត៌មានទំនិញ តម្លៃ ប្រភេទ បារកូដ និងចំនួនស្តុក។</p>
        </div>
        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form action="{{ route('products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

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
                            value="{{ old('product_name', $product->product_name ?? $product->name) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('product_name') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            placeholder="ឧ. Laptop ASUS ROG Strix G16..."
                            required
                        >
                        @error('product_name')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category & Brand -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                ប្រភេទ (Category) <span class="text-rose-500">*</span>
                            </label>
                            <select 
                                id="categorySelect"
                                name="category_id" 
                                placeholder="ជ្រើសរើសប្រភេទ..."
                                autocomplete="off"
                                required
                            >
                                <option value="">ជ្រើសរើសប្រភេទទំនិញ</option>
                                @foreach($categories as $category)
                                    @php
                                        $catId = $category->category_id ?? $category->cate_id ?? $category->id;
                                        $catName = $category->category_name ?? $category->cate_name ?? $category->name;
                                    @endphp
                                    <option value="{{ $catId }}" {{ old('category_id', $product->category_id) == $catId ? 'selected' : '' }}>
                                        {{ $catName }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                ម៉ាក/យីហោ (Brand)
                            </label>
                            <select 
                                id="brandSelect"
                                name="brand_id" 
                                placeholder="ជ្រើសរើស Brand..."
                                autocomplete="off"
                            >
                                <option value="">ជ្រើសរើស Brand (ស្រេចចិត្ត)</option>
                                @foreach($brands as $brand)
                                    @php
                                        $brandId = $brand->brand_id ?? $brand->id;
                                        $brandName = $brand->brand_name ?? $brand->name;
                                    @endphp
                                    <option value="{{ $brandId }}" {{ old('brand_id', $product->brand_id) == $brandId ? 'selected' : '' }}>
                                        {{ $brandName }}
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
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">SKU (Item Code)</label>
                            <input 
                                type="text" 
                                name="sku" 
                                value="{{ old('sku', $product->sku) }}" 
                                placeholder="ឧ. SKU-000001"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border @error('sku') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            >
                            @error('sku')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Barcode</label>
                            <input 
                                type="text" 
                                name="barcode" 
                                value="{{ old('barcode', $product->barcode) }}" 
                                placeholder="ស្កេន ឬបញ្ចូលលេខ Barcode..."
                                class="w-full px-3.5 py-2.5 bg-gray-50 border @error('barcode') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            >
                            @error('barcode')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ការពិពណ៌នា (Description)</label>
                        <textarea 
                            name="description" 
                            rows="3" 
                            placeholder="ព័ត៌មានលម្អិតបន្ថែមពីទំនិញ..."
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        >{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Pricing & Inventory -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 pb-2 border-b border-gray-100 flex items-center">
                    <i class="fa-solid fa-tags mr-2 text-emerald-500"></i> តម្លៃ & ស្តុក (Pricing & Stock)
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
                            min="0"
                            name="cost_price" 
                            value="{{ old('cost_price', $product->cost_price) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('cost_price') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition font-mono"
                            required
                        >
                        @error('cost_price')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Selling Price -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            តម្លៃលក់ ($) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            min="0"
                            name="selling_price" 
                            value="{{ old('selling_price', $product->selling_price ?? $product->unit_price ?? $product->price) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('selling_price') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition font-mono font-bold text-blue-600"
                            required
                        >
                        @error('selling_price')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Quantity -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            ចំនួនក្នុងស្តុក <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            min="0"
                            name="stock_quantity" 
                            value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('stock_quantity') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition font-mono font-bold"
                            required
                        >
                        @error('stock_quantity')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reorder Level -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">កម្រិតប្រកាសអាសន្នស្តុក</label>
                        <input 
                            type="number" 
                            min="0"
                            name="reorder_level" 
                            value="{{ old('reorder_level', $product->reorder_level ?? 5) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('reorder_level') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition font-mono"
                        >
                    </div>
                </div>
            </div>

            <!-- Section 3: Media & Settings -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 pb-2 border-b border-gray-100 flex items-center">
                    <i class="fa-solid fa-image mr-2 text-indigo-500"></i> រូបភាព & ការកំណត់ (Media & Settings)
                </h3>

                <div class="space-y-4">
                    <!-- Photo Display & Upload -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-2">រូបភាពទំនិញ (Product Image)</label>
                        <div class="flex items-center space-x-4">
                            @php
                                $imgSrc = $product->photo ?? $product->image_url ?? $product->image;
                            @endphp
                            @if($imgSrc)
                                <img src="{{ asset('storage/' . $imgSrc) }}" class="w-14 h-14 rounded-2xl object-cover border border-gray-200 shadow-sm">
                            @else
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                            @endif
                            <input 
                                type="file" 
                                name="photo" 
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
                                {{ old('is_serialized', $product->is_serialized) ? 'checked' : '' }} 
                                class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                            >
                            <span class="text-xs font-semibold text-gray-700">តាមដានតាម Serial Number (Track Serial)</span>
                        </label>

                        <!-- Is Active -->
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                value="1" 
                                {{ old('is_active', $product->is_active) ? 'checked' : '' }} 
                                class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                            >
                            <span class="text-xs font-semibold text-gray-700">ដាក់លក់ (Active / Available for Sale)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Actions -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition">
                    បោះបង់
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/25 transition">
                    ធ្វើបច្ចុប្បន្នភាពទំនិញ
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Tom Select JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('categorySelect')) {
        new TomSelect('#categorySelect', {
            create: false,
            maxOptions: 5,
            placeholder: 'ជ្រើសរើសប្រភេទ...',
            dropdownParent: 'body'
        });
    }

    if (document.getElementById('brandSelect')) {
        new TomSelect('#brandSelect', {
            create: false,
            maxOptions: 5,
            placeholder: 'ជ្រើសរើស Brand...',
            dropdownParent: 'body'
        });
    }
});
</script>
@endsection