@extends('layouts.app')

@section('title', 'Suppliers List - POS System')
@section('page_heading', 'Supplier Management')

@section('content')
<!-- Custom CSS for specialized animations, floating particles, and dark mode tweaks -->
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

<div class="space-y-6 p-4 sm:p-6 rounded-3xl animated-gradient-bg relative overflow-hidden">

    <!-- Floating Background Decorative Particles -->
    <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-3xl pointer-events-none floating-particle"></div>
    <div class="absolute top-1/2 -right-10 w-72 h-72 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl pointer-events-none floating-particle" style="animation-delay: 3s;"></div>

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 relative z-10">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">បញ្ជីអ្នកផ្គត់ផ្គង់ (Suppliers)</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">គ្រប់គ្រងក្រុមហ៊ុនដៃគូផ្គត់ផ្គង់ទំនិញចូលស្តុក (សរុប៖ {{ $totalSuppliers }} ក្រុមហ៊ុន)</p>
        </div>
        <a href="{{ route('suppliers.create') }}" class="magnetic-btn inline-flex items-center justify-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25 transition">
            <i class="fa-solid fa-plus mr-2"></i> បន្ថែមអ្នកផ្គត់ផ្គង់ថ្មី
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-4 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm relative z-10">
        <form id="filterForm" method="GET" action="{{ route('suppliers.index') }}" class="flex gap-3">
            <input type="hidden" name="per_page" id="formPerPageInput" value="{{ request('per_page', 10) }}">

            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="ស្វែងរកតាមឈ្មោះក្រុមហ៊ុន, កូដ, អ្នកទំនាក់ទំនង, លេខទូរស័ព្ទ..." 
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                >
            </div>
            <button type="submit" class="magnetic-btn px-5 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition shadow-md">
                ស្វែងរក
            </button>
            @if(request()->hasAny(['search', 'per_page']))
                <a href="{{ route('suppliers.index') }}" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-xl text-xs flex items-center justify-center transition" title="កំណត់ឡើងវិញ">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Suppliers Table -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden relative z-10">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">អ្នកផ្គត់ផ្គង់</th>
                        <th class="py-3.5 px-6">អ្នកទំនាក់ទំនង</th>
                        <th class="py-3.5 px-6">លេខទូរស័ព្ទ</th>
                        <th class="py-3.5 px-6">អ៊ីមែល</th>
                        <th class="py-3.5 px-6">អាសយដ្ឋាន</th>
                        <th class="py-3.5 px-6 text-center">ប្រវត្តិបញ្ជាទិញ</th>
                        <th class="py-3.5 px-6 text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/50 transition">
                        <td class="py-3.5 px-6">
                            <div class="flex items-center space-x-3">
                                @if($supplier->photo)
                                    <img src="{{ asset('storage/' . $supplier->photo) }}" class="w-9 h-9 rounded-xl object-cover border border-gray-200 dark:border-slate-700">
                                @else
                                    <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xs border border-blue-100 dark:border-blue-900">
                                        {{ mb_substr($supplier->supplier_name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-800 dark:text-white">{{ $supplier->supplier_name }}</p>
                                    <span class="font-mono text-[10px] text-blue-600 dark:text-blue-400 font-semibold">#{{ $supplier->supplier_code ?? 'SUP-' . $supplier->supplier_id }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-6 font-medium text-gray-700 dark:text-gray-300">
                            {{ $supplier->contact_person ?: '-' }}
                        </td>
                        <td class="py-3.5 px-6 font-semibold text-gray-800 dark:text-gray-200">
                            {{ $supplier->phone }}
                        </td>
                        <td class="py-3.5 px-6 text-gray-500 dark:text-gray-400">
                            {{ $supplier->email ?: '-' }}
                        </td>
                        <td class="py-3.5 px-6 text-gray-500 dark:text-gray-400 max-w-xs truncate">
                            {{ $supplier->address ?: '-' }}
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-lg text-xs bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 font-semibold border border-indigo-100 dark:border-indigo-900">
                                {{ $supplier->purchases_count ?? 0 }} POs
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-right space-x-1">
                            <a href="{{ route('suppliers.edit', $supplier->supplier_id) }}" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-xl transition inline-flex items-center" title="កែប្រែ">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier->supplier_id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDeleteSupplier(this, '{{ $supplier->supplier_name }}')" class="p-2 text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-xl transition inline-flex items-center" title="លុប">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-truck-field text-3xl text-gray-300 dark:text-slate-600 mb-2"></i>
                            <p class="text-sm font-medium">មិនទាន់មានទិន្នន័យអ្នកផ្គត់ផ្គង់នៅឡើយ</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500 dark:text-gray-400">
            <div>
                បង្ហាញពី <span class="font-bold text-gray-700 dark:text-gray-200">{{ $suppliers->firstItem() ?? 0 }}</span> ដល់ <span class="font-bold text-gray-700 dark:text-gray-200">{{ $suppliers->lastItem() ?? 0 }}</span> នៃទិន្នន័យសរុប <span class="font-bold text-gray-700 dark:text-gray-200">{{ $suppliers->total() }}</span> ក្រុមហ៊ុន
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-1.5 whitespace-nowrap bg-white dark:bg-slate-800 px-3 py-1 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
                    <span class="text-gray-500 dark:text-gray-400 font-medium">បង្ហាញ៖</span>
                    <select 
                        id="perPageSelectDropdown" 
                        onchange="changePerPage(this.value)" 
                        class="bg-transparent border-none text-xs font-bold text-gray-800 dark:text-white outline-none cursor-pointer focus:ring-0 py-0.5 pr-6 pl-1"
                    >
                        <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-gray-500 dark:text-gray-400 font-medium">ជួរ</span>
                </div>

                <div>
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function changePerPage(value) {
    document.getElementById('formPerPageInput').value = value;
    document.getElementById('filterForm').submit();
}

function confirmDeleteSupplier(button, name) {
    Swal.fire({
        title: '<span class="text-xl font-bold text-gray-800 dark:text-white">តើអ្នកពិតជាចង់លុបមែនទេ?</span>',
        html: `អ្នកកំពុងស្នើសុំលុបអ្នកផ្គត់ផ្គង់ <strong class="text-rose-600 font-bold">"${name}"</strong> ចេញពីប្រព័ន្ធ។`,
        icon: 'warning',
        iconColor: '#e11d48',
        showCancelButton: true,
        confirmButtonText: 'បាទ/ចាស, លុបឥឡូវនេះ',
        cancelButtonText: 'បោះបង់',
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-3xl shadow-2xl border border-gray-100 dark:border-slate-700 dark:bg-slate-800 dark:text-white',
            confirmButton: 'rounded-xl px-5 py-2.5 text-xs font-bold shadow-lg shadow-rose-500/30',
            cancelButton: 'rounded-xl px-5 py-2.5 text-xs font-semibold hover:bg-slate-600 transition'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}
</script>
@endsection