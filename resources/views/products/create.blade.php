@extends('layouts.app')

@section('title', 'Add New Product - POS System')
@section('page_heading', 'Add New Product')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header & Back Button -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Create Product</h2>
            <p class="text-xs sm:text-sm text-gray-500">Enter product information, pricing, and initial stock level.</p>
        </div>
        <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: General Info -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 pb-2 border-b border-gray-100 flex items-center">
                    <i class="fa-solid fa-box mr-2 text-blue-500"></i> General Information
                </h3>
                
                <div class="space-y-4">
                    <!-- Product Name -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Product Name <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="product_name" 
                            value="{{ old('product_name') }}" 
                            placeholder="e.g. ASUS ROG Strix G16 (2024)" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('product_name') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
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
                                Category <span class="text-rose-500">*</span>
                            </label>
                            <select 
                                name="category_id" 
                                class="w-full px-3.5 py-2.5 bg-gray-50 border @error('category_id') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                                required
                            >
                                <option value="">Select Category</option>
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
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                                Brand
                            </label>
                            <select 
                                name="brand_id" 
                                class="w-full px-3.5 py-2.5 bg-gray-50 border @error('brand_id') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            >
                                <option value="">Select Brand (Optional)</option>
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
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">SKU (Item Code)</label>
                            <input 
                                type="text" 
                                name="sku" 
                                value="{{ old('sku') }}" 
                                placeholder="e.g. SKU-100234" 
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
                                value="{{ old('barcode') }}" 
                                placeholder="e.g. 885912345678" 
                                class="w-full px-3.5 py-2.5 bg-gray-50 border @error('barcode') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            >
                            @error('barcode')
                                <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Description</label>
                        <textarea 
                            name="description" 
                            rows="3" 
                            placeholder="Write product specifications or extra details..." 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        >{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Pricing & Inventory -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 pb-2 border-b border-gray-100 flex items-center">
                    <i class="fa-solid fa-tags mr-2 text-emerald-500"></i> Pricing & Stock
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Cost Price -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Cost Price ($) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="cost_price" 
                            value="{{ old('cost_price', '0.00') }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('cost_price') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            required
                        >
                        @error('cost_price')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Selling Price -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Selling Price ($) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="selling_price" 
                            value="{{ old('selling_price', '0.00') }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('selling_price') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            required
                        >
                        @error('selling_price')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Quantity -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Initial Stock <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="stock_quantity" 
                            value="{{ old('stock_quantity', 0) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('stock_quantity') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                            required
                        >
                        @error('stock_quantity')
                            <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reorder Level -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Reorder Alert Level</label>
                        <input 
                            type="number" 
                            name="reorder_level" 
                            value="{{ old('reorder_level', 5) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50 border @error('reorder_level') border-rose-500 bg-rose-50/20 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                        >
                    </div>
                </div>
            </div>

            <!-- Section 3: Media & Settings -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 pb-2 border-b border-gray-100 flex items-center">
                    <i class="fa-solid fa-image mr-2 text-indigo-500"></i> Media & Settings
                </h3>

                <div class="space-y-4">
                    <!-- Photo Upload -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Product Photo</label>
                        <input 
                            type="file" 
                            name="photo" 
                            accept="image/*" 
                            class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 file:cursor-pointer"
                        >
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
                            <span class="text-xs font-semibold text-gray-700">Track by Serial Number (Laptop, GPU, etc.)</span>
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
                            <span class="text-xs font-semibold text-gray-700">Available for Sale (Active)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Actions -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/25 transition">
                    Save Product
                </button>
            </div>
        </form>
    </div>

</div>
@endsection