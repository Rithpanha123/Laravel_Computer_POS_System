@extends('layouts.app')

@section('title', 'Add Supplier - POS System')
@section('page_heading', 'New Supplier')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">បន្ថែមអ្នកផ្គត់ផ្គង់ថ្មី</h2>
            <p class="text-xs sm:text-sm text-gray-500">កត់ត្រាព័ត៌មានក្រុមហ៊ុនផ្គត់ផ្គង់ទំនិញចូលស្តុក</p>
        </div>
        <a href="{{ route('suppliers.index') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl">
            <ul class="list-disc list-inside text-xs text-rose-600 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-5">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    ឈ្មោះក្រុមហ៊ុន / ហាង <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="supplier_name" value="{{ old('supplier_name') }}" placeholder="ឧ. Dell Official Cambodia" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    អ្នកទំនាក់ទំនង (Contact Person)
                </label>
                <input type="text" name="contact_person" value="{{ old('contact_person') }}" placeholder="ឧ. លោក សុខា" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    លេខទូរស័ព្ទ <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="012 345 678" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    អ៊ីមែល (Email)
                </label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="supplier@mail.com" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                រូបភាព Logo / ហាង
            </label>
            <input type="file" name="photo" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                អាសយដ្ឋាន (Address)
            </label>
            <textarea name="address" rows="3" placeholder="ទីតាំងក្រុមហ៊ុន..." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 outline-none">{{ old('address') }}</textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
            <a href="{{ route('suppliers.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition">បោះបង់</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/25 transition">
                រក្សាទុកទិន្នន័យ
            </button>
        </div>
    </form>

</div>
@endsection