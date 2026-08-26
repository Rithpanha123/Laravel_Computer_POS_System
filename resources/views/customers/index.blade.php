@extends('layouts.app')

@section('title', 'Customer List - POS System')
@section('page_heading', 'Customer Directory')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">បញ្ជីអតិថិជន (Customers)</h2>
            <p class="text-xs sm:text-sm text-gray-500">គ្រប់គ្រងព័ត៌មានអតិថិជន តាមដានប្រវត្តិទិញទំនិញ និងសេវាជួសជុល (សរុប៖ {{ $totalCustomers }} នាក់)</p>
        </div>
        <a href="{{ route('customers.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition">
            <i class="fa-solid fa-user-plus mr-2"></i> ចុះឈ្មោះអតិថិជនថ្មី
        </a>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form id="customerFilterForm" method="GET" action="{{ route('customers.index') }}" class="flex gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="ស្វែងរកតាមឈ្មោះ លេខកូដ លេខទូរស័ព្ទ អ៊ីមែល..." 
                    class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
            <button type="submit" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-semibold transition">
                ស្វែងរក
            </button>
            @if(request()->hasAny(['search', 'per_page']))
                <a href="{{ route('customers.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl text-xs flex items-center justify-center transition" title="កំណត់ឡើងវិញ">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">អតិថិជន</th>
                        <th class="py-3.5 px-6">លេខទូរស័ព្ទ</th>
                        <th class="py-3.5 px-6">អ៊ីមែល</th>
                        <th class="py-3.5 px-6">អាសយដ្ឋាន</th>
                        <th class="py-3.5 px-6 text-center">ប្រវត្តិទិញ/ជួសជុល</th>
                        <th class="py-3.5 px-6 text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50/60 transition">
                        <td class="py-3.5 px-6">
                            <span class="font-bold text-gray-800">{{ $customer->customer_name }}</span>
                            <span class="block font-mono text-[11px] text-blue-600 font-semibold">#{{ $customer->customer_code ?? 'CUST-' . $customer->customer_id }}</span>
                        </td>
                        <td class="py-3.5 px-6 font-semibold text-gray-700">
                            {{ $customer->phone }}
                        </td>
                        <td class="py-3.5 px-6 text-gray-500">
                            {{ $customer->email ?: '-' }}
                        </td>
                        <td class="py-3.5 px-6 text-gray-500 max-w-xs truncate">
                            {{ $customer->address ?: '-' }}
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            <span class="inline-block px-2 py-0.5 rounded-lg text-xs bg-blue-50 text-blue-600 font-semibold mr-1">
                                {{ $customer->sales_count ?? 0 }} Orders
                            </span>
                            <span class="inline-block px-2 py-0.5 rounded-lg text-xs bg-amber-50 text-amber-600 font-semibold">
                                {{ $customer->repairs_count ?? 0 }} Repairs
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-right space-x-1">
                            <a href="{{ route('customers.edit', $customer->customer_id) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition inline-flex items-center" title="កែប្រែ">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('customers.destroy', $customer->customer_id) }}" method="POST" class="inline-block" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបអតិថិជន «{{ $customer->customer_name }}» មែនទេ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition inline-flex items-center" title="លុប">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <i class="fa-solid fa-users text-3xl text-gray-300 mb-2"></i>
                            <p class="text-sm font-medium">មិនទាន់មានទិន្នន័យអតិថិជននៅឡើយ</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-gray-500">
            <div>
                បង្ហាញពី <span class="font-bold text-gray-700">{{ $customers->firstItem() ?? 0 }}</span> ដល់ <span class="font-bold text-gray-700">{{ $customers->lastItem() ?? 0 }}</span> នៃទិន្នន័យសរុប <span class="font-bold text-gray-700">{{ $customers->total() }}</span> នាក់
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-1.5 whitespace-nowrap bg-white px-3 py-1 rounded-xl border border-gray-200 shadow-sm">
                    <span class="text-gray-500 font-medium">បង្ហាញ៖</span>
                    <select 
                        name="per_page" 
                        form="customerFilterForm" 
                        onchange="document.getElementById('customerFilterForm').submit()" 
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
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection