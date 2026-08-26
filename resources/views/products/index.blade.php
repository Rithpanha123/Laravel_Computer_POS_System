@extends('layouts.app')

@section('title', 'Product List - POS System')
@section('page_heading', 'Inventory & Products')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">ការគ្រប់គ្រងទំនិញក្នុងស្តុក (Product Management)</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">តាមដានចំនួនស្តុក តម្លៃដើម តម្លៃលក់ និងប្រភេទទំនិញ។</p>
        </div>
        <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-lg shadow-blue-500/25 transition duration-150 transform active:scale-95">
            <i class="fa-solid fa-plus mr-2"></i> បន្ថែមទំនិញថ្មី
        </a>
    </div>

    <!-- Search, Filter & Controls Form -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form id="filterForm" method="GET" action="{{ route('products.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <!-- Hidden per_page input -->
            <input type="hidden" name="per_page" id="formPerPageInput" value="{{ request('per_page', 10) }}">

            <!-- Search Text -->
            <div class="md:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="ស្វែងរកតាមឈ្មោះ, SKU, ឬ Barcode..." 
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition"
                >
            </div>

            <!-- Category Filter -->
            <div>
                <select name="category_id" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                    <option value="">គ្រប់ប្រភេទទាំងអស់ (All Categories)</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->cate_id }}" {{ request('category_id') == $category->cate_id ? 'selected' : '' }}>
                            {{ $category->cate_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Brand Filter -->
            <div>
                <select name="brand_id" onchange="document.getElementById('filterForm').submit()" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition">
                    <option value="">គ្រប់ម៉ាកទាំងអស់ (All Brands)</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->brand_id }}" {{ request('brand_id') == $brand->brand_id ? 'selected' : '' }}>
                            {{ $brand->brand_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition">
                    ស្វែងរក
                </button>
                @if(request()->hasAny(['search', 'category_id', 'brand_id', 'per_page']))
                    <a href="{{ route('products.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl text-xs flex items-center justify-center transition" title="កំណត់ឡើងវិញ">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Products Table Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">ទំនិញ (Product)</th>
                        <th class="py-3.5 px-6">ប្រភេទ / ម៉ាក</th>
                        <th class="py-3.5 px-6">តម្លៃដើម / លក់ចេញ</th>
                        <th class="py-3.5 px-6">ស្តុក (Stock)</th>
                        <th class="py-3.5 px-6">ស្ថានភាព</th>
                        <th class="py-3.5 px-6 text-right">សកម្មភាព (Actions)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/70 transition">
                        <!-- Product Info & Photo -->
                        <td class="py-3.5 px-6">
                            <div class="flex items-center space-x-3">
                                @if($product->photo)
                                    <img src="{{ asset('storage/' . $product->photo) }}" class="w-10 h-10 rounded-xl object-cover border border-gray-200 shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-base border border-slate-200">
                                        <i class="fa-solid fa-box"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-800">{{ $product->product_name }}</p>
                                    <p class="text-[11px] text-gray-400">SKU: <span class="font-mono text-gray-600 font-semibold">{{ $product->sku ?? $product->product_code ?? 'N/A' }}</span></p>
                                </div>
                            </div>
                        </td>

                        <!-- Category / Brand -->
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $product->category->cate_name ?? 'ទូទៅ' }}
                            </span>
                            @if($product->brand)
                                <span class="block text-[11px] text-gray-400 mt-0.5">{{ $product->brand->brand_name }}</span>
                            @endif
                        </td>

                        <!-- Cost / Selling Price -->
                        <td class="py-3.5 px-6">
                            <p class="font-bold text-emerald-600">${{ number_format($product->selling_price, 2) }}</p>
                            <p class="text-[11px] text-gray-400">ដើម: ${{ number_format($product->cost_price, 2) }}</p>
                        </td>

                        <!-- Stock Level -->
                        <td class="py-3.5 px-6">
                            @if($product->stock_quantity <= ($product->reorder_level ?? 5))
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">
                                    <i class="fa-solid fa-triangle-exclamation"></i> {{ $product->stock_quantity }} ក្នុងស្តុក
                                </span>
                            @else
                                <span class="font-semibold text-gray-800">{{ $product->stock_quantity }} ក្នុងស្តុក</span>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="py-3.5 px-6">
                            @if($product->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> កំពុងលក់
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> ផ្អាកលក់
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-6 text-right space-x-1">
                            <a href="{{ route('products.edit', $product->product_id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition inline-flex items-center" title="កែសម្រួល">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->product_id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button 
                                    type="button" 
                                    onclick="confirmDeleteProduct(this, '{{ $product->product_name }}')" 
                                    class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition inline-flex items-center" 
                                    title="លុបចេញ"
                                >
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-boxes-stacked text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm font-medium">មិនមានទិន្នន័យទំនិញត្រូវបានរកឃើញទេ។</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Styled Pagination Footer with Per Page Dropdown -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <div>
                បង្ហាញពី <span class="font-bold text-gray-800">{{ $products->firstItem() ?? 0 }}</span> ដល់ <span class="font-bold text-gray-800">{{ $products->lastItem() ?? 0 }}</span> នៃទំនិញសរុប <span class="font-bold text-gray-800">{{ $products->total() }}</span> មុខ
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Dropdown Selector -->
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

                <!-- Page Links -->
                <div>
                    {{ $products->links() }}
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

    function confirmDeleteProduct(button, productName) {
        Swal.fire({
            title: '<span class="text-2xl font-black text-gray-800">តើអ្នកពិតជាចង់លុបមែនទេ?</span>',
            html: `
                <div class="mt-2 text-sm text-gray-500 leading-relaxed">
                    អ្នកកំពុងស្នើសុំលុបទំនិញឈ្មោះ <strong class="text-rose-600 font-bold">"${productName}"</strong> ចេញពីស្តុក។<br>
                    <span class="text-xs text-amber-600 font-medium">⚠️ ទិន្នន័យដែលលុបហើយមិនអាចយកមកវិញបានទេ!</span>
                </div>
            `,
            icon: 'warning',
            iconColor: '#e11d48',
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-trash-can mr-2"></i> បាទ/ចាស, លុបឥឡូវនេះ',
            cancelButtonText: 'ថយក្រោយវិញ',
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            reverseButtons: true,
            focusCancel: true,
            width: '30rem',
            padding: '1.75rem',
            backdrop: `rgba(15, 23, 42, 0.65)`,
            customClass: {
                popup: 'rounded-3xl shadow-2xl border border-gray-100',
                confirmButton: 'rounded-xl px-5 py-2.5 text-sm font-bold shadow-lg shadow-rose-500/30',
                cancelButton: 'rounded-xl px-5 py-2.5 text-sm font-semibold hover:bg-slate-600 transition'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
@endsection