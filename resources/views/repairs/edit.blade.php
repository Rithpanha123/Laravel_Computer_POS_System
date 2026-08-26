@extends('layouts.app')

@section('title', 'Edit Repair Ticket - POS System')
@section('page_heading', 'Update Repair Progress')

@section('content')
<!-- Custom CSS for specialized animations, floating particles, and card interactions -->
<style>
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .animated-gradient-bg {
        background: linear-gradient(-45deg, rgba(59, 130, 246, 0.06), rgba(99, 102, 241, 0.06), rgba(139, 92, 246, 0.06), rgba(236, 72, 153, 0.06));
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
    }
    .dark .animated-gradient-bg {
        background: linear-gradient(-45deg, rgba(59, 130, 246, 0.03), rgba(99, 102, 241, 0.03), rgba(139, 92, 246, 0.03), rgba(236, 72, 153, 0.03));
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
    }
    @keyframes floating {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-8px) rotate(1deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }
    .floating-particle {
        animation: floating 6s ease-in-out infinite;
    }
    .card-3d-hover {
        transition: transform 0.3s cubic-bezier(0.2, 0, 0, 1), box-shadow 0.3s ease;
    }
    .card-3d-hover:hover {
        transform: translateY(-3px) rotateX(0.5deg) rotateY(-0.5deg);
        box-shadow: 0 20px 40px -15px rgba(59, 130, 246, 0.15);
    }
    .glow-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glow-card:hover {
        box-shadow: 0 0 25px -5px rgba(59, 130, 246, 0.2);
    }
    .magnetic-btn {
        transition: transform 0.2s cubic-bezier(0.2, 0, 0, 1), box-shadow 0.2s ease;
    }
    .magnetic-btn:hover {
        transform: translateY(-2px);
    }
</style>

<div class="max-w-4xl mx-auto space-y-6 p-4 sm:p-6 rounded-3xl animated-gradient-bg relative overflow-hidden">

    <!-- Floating Background Decorative Particles -->
    <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-3xl pointer-events-none floating-particle"></div>
    <div class="absolute top-1/2 -right-10 w-72 h-72 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl pointer-events-none floating-particle" style="animation-delay: 3s;"></div>

    <!-- Header -->
    <div class="flex items-center justify-between relative z-10">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">កែប្រែប័ណ្ណជួសជុល៖ #{{ $repair->repair_no }}</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">ឧបករណ៍៖ <span class="font-bold text-gray-800 dark:text-gray-200">{{ $repair->device_name }}</span> ({{ $repair->customer->name ?? $repair->customer->customer_name ?? 'N/A' }})</p>
        </div>
        <a href="{{ route('repairs.index') }}" class="magnetic-btn px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition shadow-sm inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="p-4 bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-900 rounded-2xl relative z-10">
            <div class="flex items-center gap-2 text-rose-700 dark:text-rose-400 font-bold text-xs mb-1">
                <i class="fa-solid fa-triangle-exclamation"></i> សូមពិនិត្យមើលកំហុសខាងក្រោម៖
            </div>
            <ul class="list-disc list-inside text-xs text-rose-600 dark:text-rose-300 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('repairs.update', $repair->repair_id) }}" method="POST" class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 sm:p-8 space-y-6 relative z-10">
        @csrf
        @method('PUT')

        <!-- Section 1: Customer & Technician Assignment -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user-gear"></i> អតិថិជន & ជាងទទួលបន្ទុក
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">អតិថិជន <span class="text-rose-500">*</span></label>
                    <select name="customer_id" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                        @foreach($customers as $cust)
                            <option value="{{ $cust->customer_id }}" {{ old('customer_id', $repair->customer_id) == $cust->customer_id ? 'selected' : '' }}>
                                {{ $cust->name ?? $cust->customer_name }} ({{ $cust->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ជាងទទួលបន្ទុកជួសជុល (Technician)</label>
                    <select name="technician_id" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">-- មិនទាន់ចាត់តាំង --</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->staff_id }}" {{ old('technician_id', $repair->technician_id) == $tech->staff_id ? 'selected' : '' }}>
                                {{ $tech->full_name ?? $tech->name }} ({{ $tech->phone }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 2: Device & Issue -->
        <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-laptop-medical"></i> ព័ត៌មានឧបករណ៍ & បញ្ហា
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ឈ្មោះម៉ូឌែលឧបករណ៍ <span class="text-rose-500">*</span></label>
                    <input type="text" name="device_name" value="{{ old('device_name', $repair->device_name) }}" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">Serial Number (S/N)</label>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $repair->serial_number) }}" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ការរៀបរាប់បញ្ហា <span class="text-rose-500">*</span></label>
                    <textarea name="problem_description" rows="2" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('problem_description', $repair->problem_description) }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">កំណត់ត្រារបស់ជាង / ដំណោះស្រាយ (Diagnosis)</label>
                    <textarea name="diagnosis" rows="3" placeholder="បញ្ជាក់ពីគ្រឿងបន្លាស់ដែលបានផ្លាស់ប្ដូរ ឬវិធីជួសជុល..." class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('diagnosis', $repair->diagnosis) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 3: Status & Pricing -->
        <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-list-check"></i> ស្ថានភាពការងារ & តម្លៃ
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ស្ថានភាពបច្ចុប្បន្ន <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-blue-400 dark:border-blue-500 font-bold rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="PENDING" {{ strtoupper(old('status', $repair->status)) === 'PENDING' ? 'selected' : '' }}>PENDING (រង់ចាំការត្រួតពិនិត្យ)</option>
                        <option value="IN_PROGRESS" {{ strtoupper(old('status', $repair->status)) === 'IN_PROGRESS' ? 'selected' : '' }}>IN_PROGRESS (កំពុងជួសជុល)</option>
                        <option value="COMPLETED" {{ strtoupper(old('status', $repair->status)) === 'COMPLETED' ? 'selected' : '' }}>COMPLETED (ជួសជុលរួចរាល់ / រង់ចាំមកយក)</option>
                        <option value="DELIVERED" {{ strtoupper(old('status', $repair->status)) === 'DELIVERED' ? 'selected' : '' }}>DELIVERED (បានប្រគល់ជូនភ្ញៀវ)</option>
                        <option value="CANCELLED" {{ strtoupper(old('status', $repair->status)) === 'CANCELLED' ? 'selected' : '' }}>CANCELLED (បោះបង់)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">តម្លៃប៉ាន់ស្មានដំបូង ($)</label>
                    <input type="number" step="0.01" min="0" name="estimated_cost" value="{{ old('estimated_cost', $repair->estimated_cost) }}" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">តម្លៃជួសជុលជាក់ស្តែង / Final ($)</label>
                    <input type="number" step="0.01" min="0" name="final_cost" value="{{ old('final_cost', $repair->final_cost) }}" placeholder="0.00" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-emerald-400 dark:border-emerald-600 font-bold text-emerald-600 dark:text-emerald-400 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Section 4: Notes -->
        <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">កំណត់សម្គាល់បន្ថែម (Notes)</label>
            <textarea name="notes" rows="2" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('notes', $repair->notes) }}</textarea>
        </div>

        <!-- Form Actions -->
        <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-end space-x-3">
            <a href="{{ route('repairs.index') }}" class="magnetic-btn px-5 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition">បោះបង់</a>
            <button type="submit" class="magnetic-btn px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/25 transition">
                រក្សាទុកការកែប្រែ
            </button>
        </div>
    </form>

</div>
@endsection