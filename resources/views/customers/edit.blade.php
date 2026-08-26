@extends('layouts.app')

@section('title', 'Edit Customer - POS System')
@section('page_heading', 'Edit Customer Profile')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">កែប្រែព័ត៌មានអតិថិជន៖ {{ $customer->customer_name }}</h2>
            <p class="text-xs sm:text-sm text-gray-500">កូដសម្គាល់៖ <span class="font-mono font-bold text-blue-600">#{{ $customer->customer_code ?? 'CUST-' . $customer->customer_id }}</span></p>
        </div>
        <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl">
            <div class="flex items-center gap-2 text-rose-700 font-bold text-xs mb-1">
                <i class="fa-solid fa-triangle-exclamation"></i> សូមពិនិត្យមើលកំហុសខាងក្រោម៖
            </div>
            <ul class="list-disc list-inside text-xs text-rose-600 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.update', $customer->customer_id) }}" method="POST" class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                ឈ្មោះអតិថិជន (Customer Name) <span class="text-rose-500">*</span>
            </label>
            <input 
                type="text" 
                name="customer_name" 
                value="{{ old('customer_name', $customer->customer_name) }}" 
                required 
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 outline-none transition"
            >
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    លេខទូរស័ព្ទ (Phone Number) <span class="text-rose-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="phone" 
                    value="{{ old('phone', $customer->phone) }}" 
                    required 
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 outline-none transition"
                >
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                    អ៊ីមែល (Email)
                </label>
                <input 
                    type="email" 
                    name="email" 
                    value="{{ old('email', $customer->email) }}" 
                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 outline-none transition"
                >
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                អាសយដ្ឋាន (Address)
            </label>
            <textarea 
                name="address" 
                rows="3" 
                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs sm:text-sm focus:ring-2 focus:ring-blue-500 outline-none transition"
            >{{ old('address', $customer->address) }}</textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
            <button 
                type="button" 
                onclick="if(confirm('តើអ្នកពិតជាចង់លុបអតិថិជននេះចេញពីប្រព័ន្ធមែនទេ?')) { document.getElementById('delete-customer-form').submit(); }"
                class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-xl transition"
            >
                <i class="fa-solid fa-trash-can mr-1.5"></i> លុបអតិថិជន
            </button>

            <div class="flex items-center space-x-3">
                <a href="{{ route('customers.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition">
                    បោះបង់
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/25 transition">
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
@endsection