@extends('layouts.app')

@section('title', 'Edit Product - POS System')
@section('page_heading', 'Edit Product')

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
            <h2 class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-400 dark:to-indigo-400">Edit Product: {{ $product->product_name }}</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Update item pricing, category, barcode, and inventory levels.</p>
        </div>
        <a href="{{ route('products.index') }}" class="magnetic-btn inline-flex items-center px-4 py-2.5 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <!-- Form Card with 3D Hover Depth -->
    <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl rounded-3xl border border-gray-100 dark:border-slate-800 shadow-2xl shadow-blue-500/5 dark:shadow-none p-6 sm:p-8 stagger-item hover-3d transition-all duration-300" style="animation-delay: 100ms;">
        <form action="{{ route('products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: General Info -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 pb-2 border-b border-gray-100 dark:border-slate-800 flex items-center">
                    <i class="fa-solid fa-box mr-2 text-blue-500"></i> General Information
                </h3>
                
                <div class="space-y-4">
                    <!-- Product Name -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                            Product Name <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="product_name" 
                            value="{{ old('product_name', $product->product_name) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('product_name') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            required
                        >
                        @error('product_name')
                            <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category & Brand -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                                Category <span class="text-rose-500">*</span>
                            </label>
                            <select 
                                name="category_id" 
                                class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('category_id') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition cursor-pointer"
                                required
                            >
                                <option value="" class="dark:bg-slate-800">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->cate_id }}" class="dark:bg-slate-800" {{ old('category_id', $product->category_id) == $category->cate_id ? 'selected' : '' }}>
                                        {{ $category->cate_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                                Brand
                            </label>
                            <select 
                                name="brand_id" 
                                class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('brand_id') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition cursor-pointer"
                            >
                                <option value="" class="dark:bg-slate-800">Select Brand (Optional)</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->brand_id }}" class="dark:bg-slate-800" {{ old('brand_id', $product->brand_id) == $brand->brand_id ? 'selected' : '' }}>
                                        {{ $brand->brand_name }}
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
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">SKU (Item Code)</label>
                            <input 
                                type="text" 
                                name="sku" 
                                value="{{ old('sku', $product->sku) }}" 
                                class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('sku') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            >
                            @error('sku')
                                <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Barcode</label>
                            <input 
                                type="text" 
                                name="barcode" 
                                value="{{ old('barcode', $product->barcode) }}" 
                                class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('barcode') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            >
                            @error('barcode')
                                <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Description</label>
                        <textarea 
                            name="description" 
                            rows="3" 
                            class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                        >{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Pricing & Inventory -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 pb-2 border-b border-gray-100 dark:border-slate-800 flex items-center">
                    <i class="fa-solid fa-tags mr-2 text-emerald-500"></i> Pricing & Stock
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Cost Price -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                            Cost Price ($) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="cost_price" 
                            value="{{ old('cost_price', $product->cost_price) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('cost_price') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            required
                        >
                        @error('cost_price')
                            <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Selling Price -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                            Selling Price ($) <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="selling_price" 
                            value="{{ old('selling_price', $product->selling_price) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('selling_price') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            required
                        >
                        @error('selling_price')
                            <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Quantity -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                            Stock Quantity <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="stock_quantity" 
                            value="{{ old('stock_quantity', $product->stock_quantity) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border @error('stock_quantity') border-rose-500 bg-rose-50/20 dark:bg-rose-950/20 @else border-gray-200 dark:border-slate-700 @enderror rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                            required
                        >
                        @error('stock_quantity')
                            <p class="text-rose-500 dark:text-rose-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reorder Level -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">Reorder Alert Level</label>
                        <input 
                            type="number" 
                            name="reorder_level" 
                            value="{{ old('reorder_level', $product->reorder_level) }}" 
                            class="w-full px-3.5 py-2.5 bg-gray-50/75 dark:bg-slate-800/75 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition"
                        >
                    </div>
                </div>
            </div>

            <!-- Section 3: Media & Settings -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4 pb-2 border-b border-gray-100 dark:border-slate-800 flex items-center">
                    <i class="fa-solid fa-image mr-2 text-indigo-500"></i> Media & Settings
                </h3>

                <div class="space-y-4">
                    <!-- Photo Display & Upload -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">Product Photo</label>
                        <div class="flex items-center space-x-4">
                            @if($product->photo)
                                <img src="{{ asset('storage/' . $product->photo) }}" class="w-14 h-14 rounded-2xl object-cover border border-gray-200 dark:border-slate-700 shadow-sm">
                            @else
                                <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-xl border border-slate-200 dark:border-slate-700">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                            @endif
                            <input 
                                type="file" 
                                name="photo" 
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
                                {{ old('is_serialized', $product->is_serialized) ? 'checked' : '' }} 
                                class="w-4 h-4 text-blue-600 rounded border-gray-300 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 focus:ring-blue-500"
                            >
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Track by Serial Number</span>
                        </label>

                        <!-- Is Active -->
                        <label class="flex items-center space-x-2.5 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                value="1" 
                                {{ old('is_active', $product->is_active) ? 'checked' : '' }} 
                                class="w-4 h-4 text-blue-600 rounded border-gray-300 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 focus:ring-blue-500"
                            >
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Available for Sale (Active)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Actions -->
            <div class="pt-4 border-t border-gray-100 dark:border-slate-800 flex items-center justify-end space-x-3">
                <a href="{{ route('products.index') }}" class="magnetic-btn px-5 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                    Cancel
                </a>
                <button type="submit" class="magnetic-btn px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-semibold rounded-xl shadow-lg shadow-blue-500/25 transition transform hover:-translate-y-0.5">
                    Update Product
                </button>
            </div>
        </form>
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

<!-- Magnetic Interactive Script -->
<script>
document.addEventListener("DOMContentLoaded", () => {
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