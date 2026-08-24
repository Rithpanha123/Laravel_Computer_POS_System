@extends('layouts.app')

@section('title', 'Edit Staff - POS System')
@section('page_heading', 'Edit Staff Details')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">កែប្រែព័ត៌មានបុគ្គលិក៖ {{ $staff->full_name }}</h2>
            <p class="text-xs sm:text-sm text-gray-500">កូដសម្គាល់៖ <span class="font-mono font-bold text-blue-600">#{{ $staff->staff_code }}</span></p>
        </div>
        <a href="{{ route('staff.index') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-50 transition shadow-sm">
            <i class="fa-solid fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
        </a>
    </div>

    <!-- Error Validation Messages -->
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

    <form action="{{ route('staff.update', $staff->staff_id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <!-- Photo Upload with Live Preview & Existing Image -->
        <div x-data="{ photoPreview: '{{ $staff->photo ? asset('storage/' . $staff->photo) : '' }}' }" class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-gray-100">
            <div class="w-24 h-24 rounded-full bg-gray-50 border-2 border-dashed border-gray-200 overflow-hidden flex items-center justify-center relative group">
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
                <label class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 text-xs font-bold rounded-xl cursor-pointer hover:bg-blue-100 transition">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i> ប្តូររូបថតថ្មី
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
                <p class="text-[11px] text-gray-400">ទុកទំនេរ ប្រសិនបើមិនចង់ប្តូររូបថតចាស់</p>
            </div>
        </div>

        <!-- Section 1: Basic Information -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-id-card"></i> ព័ត៌មានផ្ទាល់ខ្លួន
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">កូដបុគ្គលិក <span class="text-rose-500">*</span></label>
                    <input type="text" name="staff_code" value="{{ old('staff_code', $staff->staff_code) }}" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">នាមត្រកូល (Last Name) <span class="text-rose-500">*</span></label>
                    <input type="text" name="last_name" value="{{ old('last_name', $staff->last_name) }}" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">នាមខ្លួន (First Name) <span class="text-rose-500">*</span></label>
                    <input type="text" name="first_name" value="{{ old('first_name', $staff->first_name) }}" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">លេខទូរស័ព្ទ <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $staff->phone) }}" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">អ៊ីមែល (Email)</label>
                    <input type="email" name="email" value="{{ old('email', $staff->email) }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ថ្ងៃខែឆ្នាំកំណើត</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($staff->date_of_birth)->format('Y-m-d')) }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Section 2: Employment & Compensation -->
        <div class="pt-4 border-t border-gray-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-briefcase"></i> ព័ត៌មានការងារ និងប្រាក់បៀវត្ស
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Dropdown Position -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        មុខតំណែង / តួនាទី <span class="text-rose-500">*</span>
                    </label>
                    <select name="position_id" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">-- ជ្រើសរើសមុខតំណែង --</option>
                        @if(isset($positions))
                            @foreach($positions as $pos)
                                <option value="{{ $pos->position_id }}" {{ old('position_id', $staff->position_id) == $pos->position_id ? 'selected' : '' }}>
                                    {{ $pos->position_name ?? $pos->{'position_name, 50'} }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ស្ថានភាពការងារ <span class="text-rose-500">*</span></label>
                    <select name="employment_status" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="ACTIVE" {{ old('employment_status', $staff->employment_status) === 'ACTIVE' ? 'selected' : '' }}>ACTIVE (កំពុងបម្រើការ)</option>
                        <option value="INACTIVE" {{ old('employment_status', $staff->employment_status) === 'INACTIVE' ? 'selected' : '' }}>INACTIVE (ផ្អាកបណ្ដោះអាសន្ន)</option>
                        <option value="RESIGNED" {{ old('employment_status', $staff->employment_status) === 'RESIGNED' ? 'selected' : '' }}>RESIGNED (លាឈប់)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ប្រាក់ខែគោល ($)</label>
                    <input type="number" step="0.01" min="0" name="salary" value="{{ old('salary', $staff->salary) }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ថ្ងៃចូលបម្រើការងារ</label>
                    <input type="date" name="hire_date" value="{{ old('hire_date', optional($staff->hire_date)->format('Y-m-d')) }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Section 3: Address & Emergency Contact -->
        <div class="pt-4 border-t border-gray-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-address-book"></i> អាសយដ្ឋាន & ទំនាក់ទំនងពេលមានអាសន្ន
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">អាសយដ្ឋានបច្ចុប្បន្ន</label>
                    <input type="text" name="address" value="{{ old('address', $staff->address) }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ឈ្មោះអ្នកទាក់ទងពេលអាសន្ន</label>
                    <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $staff->emergency_contact_name) }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">លេខទូរស័ព្ទសាច់ញាតិ</label>
                    <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $staff->emergency_contact_phone) }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">ត្រូវជាអ្វី (Relation)</label>
                    <input type="text" name="emergency_contact_relation" value="{{ old('emergency_contact_relation', $staff->emergency_contact_relation) }}" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
            </div>
        </div>

        <!-- Section 4: Notes -->
        <div class="pt-4 border-t border-gray-100">
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">កំណត់សម្គាល់បន្ថែម (Notes)</label>
            <textarea name="notes" rows="3" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">{{ old('notes', $staff->notes) }}</textarea>
        </div>

        <!-- Actions & Delete Button -->
        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
            <button 
                type="button" 
                onclick="if(confirm('តើអ្នកពិតជាចង់លុបបុគ្គលិកនេះមែនទេ?')) { document.getElementById('delete-staff-form').submit(); }"
                class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-xl transition"
            >
                <i class="fa-solid fa-trash-can mr-1.5"></i> លុបបុគ្គលិក
            </button>

            <div class="flex items-center space-x-3">
                <a href="{{ route('staff.index') }}" class="px-5 py-2.5 border border-gray-200 text-gray-600 text-xs font-semibold rounded-xl hover:bg-gray-50 transition">បោះបង់</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/25 transition">
                    ធ្វើបច្ចុប្បន្នភាពទិន្នន័យ
                </button>
            </div>
        </div>
    </form>

    <!-- Hidden Delete Form -->
    <form id="delete-staff-form" action="{{ route('staff.destroy', $staff->staff_id) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

</div>
@endsection