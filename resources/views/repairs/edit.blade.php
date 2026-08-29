@extends('layouts.app')

@section('title', 'Edit Repair Ticket - POS System')
@section('page_heading', 'Update Repair Progress')

@section('content')
<!-- TomSelect CSS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    .ts-wrapper {
        width: 100% !important;
        position: relative !important;
    }
    .ts-control {
        border-radius: 0.75rem !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #f8fafc !important;
        font-size: 0.75rem !important;
        padding: 0.5rem 0.75rem !important;
        min-height: 38px !important;
        box-shadow: none !important;
    }
    .ts-control:focus-within {
        border-color: #2563eb !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15) !important;
    }
    .ts-dropdown {
        background-color: #ffffff !important;
        border-radius: 0.75rem !important;
        font-size: 0.75rem !important;
        border: 1px solid #cbd5e1 !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2) !important;
        z-index: 99999 !important;
    }
    .ts-dropdown .ts-dropdown-content {
        max-height: 180px !important;
        background-color: #ffffff !important;
    }
    .ts-dropdown .option {
        padding: 0.5rem 0.75rem !important;
        border-bottom: 1px solid #f1f5f9 !important;
        color: #334155 !important;
    }
    .ts-dropdown .option.active, .ts-dropdown .option:hover {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        font-weight: 600 !important;
    }
</style>

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">កែប្រែប័ណ្ណជួសជុល៖ #{{ $repair->repair_no ?? ('REP-' . $repair->repair_id) }}</h2>
            <p class="text-xs sm:text-sm text-gray-500">ឧបករណ៍៖ <span class="font-bold text-gray-800">{{ $repair->device_name }}</span> ({{ $repair->customer->customer_name ?? $repair->customer->name ?? 'N/A' }})</p>
        </div>
        <a href="{{ route('repairs.index') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <!-- Validation Errors -->
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

    <form action="{{ route('repairs.update', $repair->repair_id) }}" method="POST" class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Customer & Technician Assignment -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user-gear"></i> អតិថិជន & ជាងទទួលបន្ទុក
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">អតិថិជន <span class="text-rose-500">*</span></label>
                    <select id="customerSelect" name="customer_id" placeholder="ស្វែងរកអតិថិជន..." autocomplete="off" required>
                        <option value="">-- ជ្រើសរើសអតិថិជន --</option>
                        @foreach($customers as $cust)
                            <option value="{{ $cust->customer_id }}" {{ old('customer_id', $repair->customer_id) == $cust->customer_id ? 'selected' : '' }}>
                                {{ $cust->customer_name ?? $cust->name }} ({{ $cust->phone ?? 'គ្មានលេខ' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ជាងទទួលបន្ទុកជួសជុល (Technician)</label>
    <select id="technicianSelect" name="technician_id" placeholder="ជ្រើសរើសជាង..." autocomplete="off">
        <option value="">-- មិនទាន់ចាត់តាំង --</option>
        @foreach($technicians as $tech)
            @php
                $techName = $tech->full_name 
                    ?? $tech->staff_name 
                    ?? trim(($tech->first_name ?? '') . ' ' . ($tech->last_name ?? '')) 
                    ?? $tech->name 
                    ?? 'Staff #' . $tech->staff_id;
            @endphp
            <option value="{{ $tech->staff_id }}" {{ old('technician_id', $repair->technician_id) == $tech->staff_id ? 'selected' : '' }}>
                {{ $techName }} {{ !empty($tech->phone) ? '(' . $tech->phone . ')' : '' }}
            </option>
        @endforeach
    </select>
</div>
            </div>
        </div>

        <!-- Section 2: Device & Issue -->
        <div class="pt-4 border-t border-gray-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-laptop-medical"></i> ព័ត៌មានឧបករណ៍ & បញ្ហា
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ឈ្មោះម៉ូឌែលឧបករណ៍ <span class="text-rose-500">*</span></label>
                    <input type="text" name="device_name" value="{{ old('device_name', $repair->device_name) }}" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Serial Number (S/N)</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $repair->serial_number) }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ការរៀបរាប់បញ្ហា <span class="text-rose-500">*</span></label>
                    <textarea name="problem_description" rows="2" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">{{ old('problem_description', $repair->problem_description) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">កំណត់ត្រារបស់ជាង / ដំណោះស្រាយ (Diagnosis)</label>
                    <textarea name="diagnosis" rows="3" placeholder="បញ្ជាក់ពីគ្រឿងបន្លាស់ដែលបានផ្លាស់ប្ដូរ ឬវិធីជួសជុល..." class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">{{ old('diagnosis', $repair->diagnosis) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 3: Status & Pricing -->
        <div class="pt-4 border-t border-gray-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-list-check"></i> ស្ថានភាពការងារ & តម្លៃ
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ស្ថានភាពបច្ចុប្បន្ន <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-blue-400 font-bold rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="PENDING" {{ strtoupper(old('status', $repair->status)) === 'PENDING' ? 'selected' : '' }}>PENDING (រង់ចាំការត្រួតពិនិត្យ)</option>
                        <option value="IN_PROGRESS" {{ strtoupper(old('status', $repair->status)) === 'IN_PROGRESS' ? 'selected' : '' }}>IN_PROGRESS (កំពុងជួសជុល)</option>
                        <option value="COMPLETED" {{ strtoupper(old('status', $repair->status)) === 'COMPLETED' ? 'selected' : '' }}>COMPLETED (ជួសជុលរួចរាល់)</option>
                        <option value="DELIVERED" {{ strtoupper(old('status', $repair->status)) === 'DELIVERED' ? 'selected' : '' }}>DELIVERED (បានប្រគល់ជូនភ្ញៀវ)</option>
                        <option value="CANCELLED" {{ strtoupper(old('status', $repair->status)) === 'CANCELLED' ? 'selected' : '' }}>CANCELLED (បោះបង់)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">តម្លៃប៉ាន់ស្មានដំបូង ($)</label>
                    <input type="number" step="0.01" min="0" name="estimated_cost" value="{{ old('estimated_cost', $repair->estimated_cost) }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">តម្លៃជួសជុលជាក់ស្តែង / Final ($)</label>
                    <input type="number" step="0.01" min="0" name="final_cost" value="{{ old('final_cost', $repair->final_cost) }}" placeholder="0.00" class="w-full px-3.5 py-2.5 bg-gray-50 border border-emerald-400 font-bold text-emerald-600 rounded-xl text-xs font-mono focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Section 4: Notes -->
        <div class="pt-4 border-t border-gray-100">
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">កំណត់សម្គាល់បន្ថែម (Notes)</label>
            <textarea name="notes" rows="2" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">{{ old('notes', $repair->notes) }}</textarea>
        </div>

        <!-- Form Actions -->
        <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
            <a href="{{ route('repairs.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition">បោះបង់</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/25 transition">
                រក្សាទុកការកែប្រែ
            </button>
        </div>
    </form>

</div>

<!-- TomSelect JS -->
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('customerSelect')) {
        new TomSelect('#customerSelect', {
            create: false,
            maxOptions: 5,
            placeholder: 'ស្វែងរកអតិថិជន...',
            allowEmptyOption: true,
            dropdownParent: 'body'
        });
    }

    if (document.getElementById('technicianSelect')) {
        new TomSelect('#technicianSelect', {
            create: false,
            maxOptions: 5,
            placeholder: 'ជ្រើសរើសជាង...',
            allowEmptyOption: true,
            dropdownParent: 'body'
        });
    }
});
</script>
@endsection