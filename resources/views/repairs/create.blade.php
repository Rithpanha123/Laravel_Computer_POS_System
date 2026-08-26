@extends('layouts.app')

@section('title', 'Create Repair Job - POS System')
@section('page_heading', 'New Repair Order')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">ទទួលឧបករណ៍ជួសជុលថ្មី</h2>
            <p class="text-xs sm:text-sm text-gray-500">កត់ត្រាព័ត៌មានឧបករណ៍ រោគសញ្ញាខូច និងតម្លៃប៉ាន់ស្មាន</p>
        </div>
        <a href="{{ route('repairs.index') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm inline-flex items-center">
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

    <form action="{{ route('repairs.store') }}" method="POST" class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        <!-- Customer & Staff Selection -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Customer Field -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                        អតិថិជន <span class="text-rose-500">*</span>
                    </label>
                    <span class="text-[11px] text-gray-400">វាយស្វែងរកឈ្មោះ/លេខ</span>
                </div>
                <select id="customer_select" name="customer_id" required>
                    <option value="">-- ស្វែងរក ឬជ្រើសរើសអតិថិជន --</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->customer_id }}" {{ old('customer_id') == $c->customer_id ? 'selected' : '' }}>
                            {{ $c->customer_name }} ({{ $c->phone }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Staff Field -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">
                        បុគ្គលិកទទួលបន្ទុក (Staff In Charge)
                    </label>
                    <span class="text-[11px] text-gray-400">វាយស្វែងរកឈ្មោះបុគ្គលិក</span>
                </div>
                <select id="staff_select" name="staff_id">
                    <option value="">-- ជ្រើសរើសបុគ្គលិក (Staff) --</option>
                    @foreach($technicians as $staff)
                        <option value="{{ $staff->staff_id ?? $staff->user_id }}" {{ old('staff_id') == ($staff->staff_id ?? $staff->user_id) ? 'selected' : '' }}>
                            {{ $staff->full_name ?? $staff->username }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Device Info -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ឈ្មោះឧបករណ៍ <span class="text-rose-500">*</span></label>
                <input type="text" name="device_name" value="{{ old('device_name') }}" placeholder="ឧ. Dell Laptop, MacBook Pro" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ម៉ូដែល (Model)</label>
                <input type="text" name="device_model" value="{{ old('device_model') }}" placeholder="ឧ. Latitude 7490, A2338" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">លេខ Serial Number</label>
                <input type="text" name="serial_number" value="{{ old('serial_number') }}" placeholder="S/N" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
        </div>

        <!-- Problem & Accessories -->
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">រោគសញ្ញា / បញ្ហាឧបករណ៍ <span class="text-rose-500">*</span></label>
                <textarea name="problem_description" rows="3" required placeholder="ពិពណ៌នាពីបញ្ហា (បើកមិនចេញ, បែកអេក្រង់, គាំង...)" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('problem_description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">គ្រឿងភ្ជាប់មកជាមួយ (Accessories)</label>
                <input type="text" name="accessories_included" value="{{ old('accessories_included') }}" placeholder="ឧ. ដុំសាក (Charger), កាតាប (Bag), Mouse..." class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
        </div>

        <!-- Financial Estimates -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 p-5 rounded-2xl border border-gray-100">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">តម្លៃប៉ាន់ស្មាន ($)</label>
                <input type="number" step="0.01" min="0" name="estimated_cost" value="{{ old('estimated_cost', 0) }}" class="w-full px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ប្រាក់កក់មុន ($)</label>
                <input type="number" step="0.01" min="0" name="deposit_amount" value="{{ old('deposit_amount', 0) }}" class="w-full px-3.5 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-emerald-600 focus:ring-2 focus:ring-indigo-500 outline-none">
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
            <a href="{{ route('repairs.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition">បោះបង់</a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-indigo-500/25 transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-floppy-disk"></i> បង្កើតប័ណ្ណទទួលជួសជុល
            </button>
        </div>
    </form>

</div>

<!-- Tom Select CSS/JS CDN -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<style>
    .ts-control {
        background-color: #f9fafb !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 0.75rem !important;
        padding: 0.625rem 0.875rem !important;
        font-size: 0.75rem !important;
        box-shadow: none !important;
    }
    .ts-control.focus {
        background-color: #ffffff !important;
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
    }
    .ts-dropdown {
        border-radius: 0.75rem !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        font-size: 0.75rem !important;
        z-index: 50 !important;
    }
    /* កំណត់ឱ្យបង្ហាញកម្ពស់ប្រហែល 5 ជួរ និងមាន Scroll */
    .ts-dropdown .ts-dropdown-content {
        max-height: 180px !important;
        overflow-y: auto !important;
    }
    .ts-dropdown .option {
        padding: 8px 12px !important;
    }
    .ts-dropdown .active {
        background-color: #eef2ff !important;
        color: #4f46e5 !important;
        font-weight: 600;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Customer Select
        if (document.getElementById('customer_select')) {
            new TomSelect('#customer_select', {
                create: false,
                sortField: { field: "text", direction: "asc" },
                maxOptions: 50,
                placeholder: "-- វាយស្វែងរក ឬជ្រើសរើសអតិថិជន --",
                allowEmptyOption: true,
            });
        }

        // Staff Select
        if (document.getElementById('staff_select')) {
            new TomSelect('#staff_select', {
                create: false,
                sortField: { field: "text", direction: "asc" },
                maxOptions: 50,
                placeholder: "-- វាយស្វែងរក ឬជ្រើសរើសបុគ្គលិក --",
                allowEmptyOption: true,
            });
        }
    });
</script>
@endsection