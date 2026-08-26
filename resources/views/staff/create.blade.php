@extends('layouts.app')

@section('title', 'Add New Staff - POS System')
@section('page_heading', 'New Staff Registration')

@section('content')
<!-- Custom CSS for specialized animations, floating particles, and interactions -->
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
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">ចុះឈ្មោះបុគ្គលិកថ្មី</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">បញ្ចូលព័ត៌មានលម្អិតបុគ្គលិក ជាងជួសជុល ឬអ្នកគិតលុយ</p>
        </div>
        <a href="{{ route('staff.index') }}" class="magnetic-btn px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition shadow-sm inline-flex items-center">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <!-- Error Validation Messages -->
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

    <form action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data" class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm p-6 sm:p-8 space-y-6 relative z-10">
        @csrf

        <!-- Photo Upload with Live Preview -->
        <div x-data="{ photoPreview: null }" class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-gray-100 dark:border-slate-700">
            <div class="w-24 h-24 rounded-full bg-gray-50 dark:bg-slate-900 border-2 border-dashed border-gray-200 dark:border-slate-700 overflow-hidden flex items-center justify-center relative group">
                <template x-if="photoPreview">
                    <img :src="photoPreview" class="w-full h-full object-cover">
                </template>
                <template x-if="!photoPreview">
                    <div class="text-center text-gray-400">
                        <i class="fa-solid fa-camera text-2xl mb-1"></i>
                        <span class="block text-[9px] font-bold uppercase">រូបថត</span>
                    </div>
                </template>
            </div>
            <div class="space-y-1.5 text-center sm:text-left">
                <label class="magnetic-btn inline-flex items-center px-4 py-2 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 text-xs font-bold rounded-xl cursor-pointer hover:bg-blue-100 dark:hover:bg-blue-900/50 transition border border-blue-100 dark:border-blue-900">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i> ជ្រើសរើសរូបថត
                    <input 
                        type="file" 
                        name="photo" 
                        accept="image/*" 
                        class="hidden"
                        @change="
                            const file = $event.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = (e) => { photoPreview = e.target.result; };
                                reader.readAsDataURL(file);
                            }
                        "
                    >
                </label>
                <p class="text-[11px] text-gray-400">ប្រភេទហ្វាលអនុញ្ញាត៖ JPG, PNG, WEBP (ទំហំអតិបរមា 2MB)</p>
            </div>
        </div>

        <!-- Section 1: Basic Information -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-id-card"></i> ព័ត៌មានផ្ទាល់ខ្លួន
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">កូដបុគ្គលិក <span class="text-rose-500">*</span></label>
                    <input type="text" name="staff_code" value="{{ old('staff_code', 'STF-' . rand(100, 999)) }}" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-mono font-bold text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">នាមត្រកូល (Last Name) <span class="text-rose-500">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="ឧ. សុខ" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">នាមខ្លួន (First Name) <span class="text-rose-500">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="ឧ. ពិសាល" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">លេខទូរស័ព្ទ <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="012 345 678" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">អ៊ីមែល (Email)</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="staff@example.com" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ថ្ងៃខែឆ្នាំកំណើត</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Section 2: Employment & Compensation -->
        <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-briefcase"></i> ព័ត៌មានការងារ និងប្រាក់បៀវត្ស
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                        មុខតំណែង / តួនាទី <span class="text-rose-500">*</span>
                    </label>
                    <select name="position_id" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">-- ជ្រើសរើសមុខតំណែង --</option>
                        @if(isset($positions))
                            @foreach($positions as $pos)
                                <option value="{{ $pos->position_id }}" {{ old('position_id') == $pos->position_id ? 'selected' : '' }}>
                                    {{ $pos->position_name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ស្ថានភាពការងារ <span class="text-rose-500">*</span></label>
                    <select name="employment_status" required class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="ACTIVE" selected>ACTIVE (កំពុងបម្រើការ)</option>
                        <option value="INACTIVE">INACTIVE (ផ្អាកបណ្ដោះអាសន្ន)</option>
                        <option value="RESIGNED">RESIGNED (លាឈប់)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ប្រាក់ខែគោល ($)</label>
                    <input type="number" step="0.01" min="0" name="salary" value="{{ old('salary', '0.00') }}" placeholder="0.00" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-bold text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ថ្ងៃចូលបម្រើការងារ</label>
                    <input type="date" name="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Section 3: Address & Emergency Contact -->
        <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-address-book"></i> អាសយដ្ឋាន & ទំនាក់ទំនងពេលមានអាសន្ន
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">អាសយដ្ឋានបច្ចុប្បន្ន</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="ផ្ទះលេខ, ផ្លូវ, សង្កាត់, ខណ្ឌ..." class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ឈ្មោះអ្នកទាក់ទងពេលអាសន្ន</label>
                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" placeholder="ឈ្មោះសាច់ញាតិ" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">លេខទូរស័ព្ទសាច់ញាតិ</label>
                    <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" placeholder="012 xxx xxx" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">ត្រូវជាអ្វី (Relation)</label>
                    <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation') }}" placeholder="ឧ. បងប្រុស, ម្ដាយ, ឪពុក" class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Section 4: Notes -->
        <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">កំណត់សម្គាល់បន្ថែម (Notes)</label>
            <textarea name="notes" rows="3" placeholder="ជំនាញពិសេស, បទពិសោធន៍ការងារពីមុន..." class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('notes') }}</textarea>
        </div>

        <!-- Actions -->
        <div class="pt-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-end space-x-3">
            <a href="{{ route('staff.index') }}" class="magnetic-btn px-5 py-2.5 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 text-xs font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition">បោះបង់</a>
            <button type="submit" class="magnetic-btn px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/25 transition">
                រក្សាទុកទិន្នន័យ
            </button>
        </div>
    </form>

</div>
@endsection