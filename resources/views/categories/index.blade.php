@extends('layouts.app')

@section('title', 'Categories List - POS System')
@section('page_heading', 'Category Management')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">បញ្ជីប្រភេទផលិតផល (Categories)</h2>
            <p class="text-xs sm:text-sm text-gray-500">គ្រប់គ្រង និងចាត់ថ្នាក់ទំនិញក្នុងស្តុក (សរុប៖ {{ $totalCategories }} ប្រភេទ)</p>
        </div>
        <a href="{{ route('categories.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition">
            <i class="fa-solid fa-plus mr-2"></i> បន្ថែមប្រភេទថ្មី
        </a>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-2xl shadow-sm flex justify-between items-center text-xs sm:text-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-green-500"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-2xl shadow-sm flex justify-between items-center text-xs sm:text-sm">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-xmark text-red-500"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-700 font-bold">&times;</button>
        </div>
    @endif

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form id="filterForm" method="GET" action="{{ route('categories.index') }}" class="flex gap-3">
            <input type="hidden" name="per_page" id="formPerPageInput" value="{{ request('per_page', 10) }}">

            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="ស្វែងរកតាមឈ្មោះប្រភេទផលិតផល, ការពិពណ៌នា..." 
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition">
                ស្វែងរក
            </button>
            @if(request()->hasAny(['search', 'per_page']))
                <a href="{{ route('categories.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl text-xs flex items-center justify-center transition" title="កំណត់ឡើងវិញ">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">ID</th>
                        <th class="py-3.5 px-6">ឈ្មោះប្រភេទផលិតផល</th>
                        <th class="py-3.5 px-6">ការពិពណ៌នា</th>
                        <th class="py-3.5 px-6 text-center">ចំនួនផលិតផល</th>
                        <th class="py-3.5 px-6 text-center">ស្ថានភាព</th>
                        <th class="py-3.5 px-6 text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3.5 px-6 font-mono text-[11px] text-blue-600 font-bold">
                            #{{ $category->cate_id }}
                        </td>
                        <td class="py-3.5 px-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs border border-blue-100">
                                    <i class="fa-solid fa-layer-group"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $category->cate_name }}</p>
                                    <span class="text-[10px] text-gray-400">{{ $category->created_at ? $category->created_at->format('d/m/Y') : '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-6 text-gray-500 max-w-xs truncate">
                            {{ $category->description ?: '-' }}
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-lg text-xs bg-indigo-50 text-indigo-700 font-semibold">
                                {{ $category->products_count ?? 0 }} មុខ
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $category->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-right space-x-1">
                            <a href="{{ route('categories.edit', $category->cate_id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition inline-flex items-center" title="កែប្រែ">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('categories.destroy', $category->cate_id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDeleteCategory(this, '{{ $category->cate_name }}')" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition inline-flex items-center" title="លុប">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-layer-group text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm font-medium">មិនទាន់មានទិន្នន័យប្រភេទផលិតផលនៅឡើយ</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <div>
                បង្ហាញពី <span class="font-bold text-gray-700">{{ $categories->firstItem() ?? 0 }}</span> ដល់ <span class="font-bold text-gray-700">{{ $categories->lastItem() ?? 0 }}</span> នៃទិន្នន័យសរុប <span class="font-bold text-gray-700">{{ $categories->total() }}</span> ប្រភេទ
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-1.5 whitespace-nowrap bg-white px-3 py-1 rounded-xl border border-gray-200 shadow-sm">
                    <span class="text-gray-500 font-medium">បង្ហាញ៖</span>
                    <select 
                        id="perPageSelectDropdown" 
                        onchange="changePerPage(this.value)" 
                        class="bg-transparent border-none text-xs font-bold text-gray-800 outline-none cursor-pointer focus:ring-0 py-0.5 pr-6 pl-1"
                    >
                        <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-gray-500 font-medium">ជួរ</span>
                </div>

                <div>
                    {{ $categories->links() }}
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

function confirmDeleteCategory(button, name) {
    Swal.fire({
        title: '<span class="text-xl font-bold text-gray-800">តើអ្នកពិតជាចង់លុបមែនទេ?</span>',
        html: `អ្នកកំពុងស្នើសុំលុបប្រភេទផលិតផល <strong class="text-rose-600 font-bold">"${name}"</strong> ចេញពីប្រព័ន្ធ។`,
        icon: 'warning',
        iconColor: '#e11d48',
        showCancelButton: true,
        confirmButtonText: 'បាទ/ចាស, លុបឥឡូវនេះ',
        cancelButtonText: 'បោះបង់',
        confirmButtonColor: '#e11d48',
        cancelButtonColor: '#64748b',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-3xl shadow-2xl border border-gray-100',
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