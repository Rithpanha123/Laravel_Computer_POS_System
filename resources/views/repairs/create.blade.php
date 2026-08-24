@extends('layouts.app')

@section('title', 'New Repair Ticket - POS System')
@section('page_heading', 'Receive Device for Repair')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">New Repair Job</h2>
            <p class="text-xs sm:text-sm text-gray-500">Record incoming client device, report issues, and assign technician.</p>
        </div>
        <a href="{{ route('repairs.index') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <form action="{{ route('repairs.store') }}" method="POST" class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Customer -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Customer <span class="text-rose-500">*</span></label>
                <select name="customer_id" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Select Customer</option>
                    @foreach($customers as $cust)
                        <option value="{{ $cust->customer_id }}">{{ $cust->name }} ({{ $cust->phone }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Technician -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Assign Technician</label>
                <select name="technician_id" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Assign Later (Unassigned)</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->staff_id }}">{{ $tech->full_name ?? $tech->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Device Name -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Device Name / Model <span class="text-rose-500">*</span></label>
                <input type="text" name="device_name" placeholder="e.g. ASUS ROG Strix G15, MacBook Pro M1" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <!-- Serial Number -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Serial Number (S/N)</label>
                <input type="text" name="serial_number" placeholder="Device Serial Number" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <!-- Received Date -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Received Date <span class="text-rose-500">*</span></label>
                <input type="datetime-local" name="received_at" value="{{ date('Y-m-d\TH:i') }}" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <!-- Estimated Cost -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Estimated Cost ($)</label>
                <input type="number" step="0.01" name="estimated_cost" placeholder="0.00" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
        </div>

        <!-- Problem Description -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Problem Description <span class="text-rose-500">*</span></label>
            <textarea name="problem_description" rows="3" required placeholder="Describe issue stated by customer (e.g. No power, broken screen, blue screen error)..." class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
        </div>

        <!-- Initial Diagnosis / Notes -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">Initial Diagnosis / Notes</label>
            <textarea name="diagnosis" rows="2" placeholder="Preliminary checks, scratches on device, accessories left behind..." class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
            <a href="{{ route('repairs.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/25 transition">
                Create Repair Ticket
            </button>
        </div>
    </form>

</div>
@endsection