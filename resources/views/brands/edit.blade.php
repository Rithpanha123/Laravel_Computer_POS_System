@extends('layouts.app')

@section('title', 'Edit Brand - POS System')
@section('page_heading', 'Edit Brand')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">កែប្រែម៉ាកយីហោ</h2>
            <p class="text-xs text-gray-500">កែសម្រួលព័ត៌មានម៉ាកយីហោ #{{ $brand->brand_id }}</p>
        </div>
        <a href="{{ route('brands.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">
            <i class="fa-solid fa-arrow-left mr-1"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <form action="{{ route('brands.update', $brand->brand_id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ឈ្មោះម៉ាក (Brand Name) *</label>
                <input type="text" name="brand_name" value="{{ old('brand_name', $brand->brand_name) }}" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                @error('brand_name') <span class="text-rose-500 text-[10px] mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">ការពិពណ៌នា</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 text-xs border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description', $brand->description) }}</textarea>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" {{ $brand->is_active ? 'checked' : '' }}>
                <label for="is_active" class="text-xs font-semibold text-gray-700">បើកដំណើរការ (Active Status)</label>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-100">
                <a href="{{ route('brands.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">បោះបង់</a>
                <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-semibold shadow-sm shadow-amber-500/20 transition">កែប្រែទិន្នន័យ</button>
            </div>
        </form>
    </div>
</div>
@endsection