@extends('layouts.app')

@section('title', 'ផ្ទាំងគ្រប់គ្រងទូទៅ - POS System')
@section('page_heading', 'Dashboard Overview')

@section('content')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">

    <!-- Top Statistics Cards (Row 1: 4 Main Financials) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1: Today Sales -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">ការលក់ថ្ងៃនេះ (Today Sales)</p>
                <h3 class="text-2xl font-black text-gray-800 mt-1">${{ number_format($todaySales, 2) }}</h3>
                <p class="text-[11px] font-medium {{ $salesGrowth >= 0 ? 'text-emerald-600' : 'text-rose-500' }} mt-1 flex items-center">
                    <i class="fa-solid {{ $salesGrowth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1"></i>
                    {{ $salesGrowth >= 0 ? '+' : '' }}{{ $salesGrowth }}% ធៀបម្សិលមិញ
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
        </div>

        <!-- Card 2: Purchases (ទិញចូលស្តុក) -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">ទិញចូលខែនេះ (Purchases)</p>
                <h3 class="text-2xl font-black text-indigo-600 mt-1">${{ number_format($monthPurchases, 2) }}</h3>
                <p class="text-[11px] font-medium text-gray-400 mt-1">
                    <i class="fa-solid fa-truck-ramp-box mr-1"></i> ថ្ងៃនេះទិញ ${{ number_format($todayPurchases, 2) }} ({{ $todayPurchasesCount }})
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-cart-flatbed"></i>
            </div>
        </div>

        <!-- Card 3: Monthly Expenses (ការចំណាយ) -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">ចំណាយខែនេះ (Expenses)</p>
                <h3 class="text-2xl font-black text-rose-600 mt-1">${{ number_format($monthExpenses, 2) }}</h3>
                <p class="text-[11px] font-medium text-gray-500 mt-1">
                    <i class="fa-solid fa-wallet mr-1"></i> ចំណាយថ្ងៃនេះ ${{ number_format($todayExpenses, 2) }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-arrow-down-wide-short"></i>
            </div>
        </div>

        <!-- Card 4: Active Repairs (កំពុងជួសជុល) -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">កំពុងជួសជុល (Repairs)</p>
                <h3 class="text-2xl font-black text-amber-600 mt-1">{{ $activeRepairsCount }} គ្រឿង</h3>
                <p class="text-[11px] font-medium text-emerald-600 mt-1">
                    <i class="fa-solid fa-circle-check mr-1"></i> រួចរាល់ {{ $completedRepairs }} គ្រឿង
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
        </div>

    </div>

    <!-- Row 2: Secondary Quick Overview (Stock, Customers, Staff) -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400">ជិតអស់ពីស្តុក</p>
                <h4 class="text-base font-bold text-rose-600">{{ $lowStockCount }} មុខ</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-cubes"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400">មុខទំនិញសរុប</p>
                <h4 class="text-base font-bold text-gray-800">{{ $totalProducts }} មុខ</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400">អតិថិជនសរុប</p>
                <h4 class="text-base font-bold text-gray-800">{{ $totalCustomers }} នាក់</h4>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg">
                <i class="fa-solid fa-user-gear"></i>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-400">បុគ្គលិកសកម្ម</p>
                <h4 class="text-base font-bold text-gray-800">{{ $totalStaff }} នាក់</h4>
            </div>
        </div>
    </div>

    <!-- Charts Section: 7 Days Sales vs Purchases vs Expenses -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Chart 1: 7 Days Multi-Bar Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                <div>
                    <h3 class="text-sm font-bold text-gray-800">ស្ថិតិប្រៀបធៀបចំណូល-ចំណាយ (៧ ថ្ងៃចុងក្រោយ)</h3>
                    <p class="text-xs text-gray-400">ការលក់ចេញ vs ការទិញចូលស្តុក vs ការចំណាយទូទៅ</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-semibold">
                    <span class="flex items-center gap-1 text-blue-600"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> លក់</span>
                    <span class="flex items-center gap-1 text-indigo-600"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> ទិញចូល</span>
                    <span class="flex items-center gap-1 text-rose-500"><span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span> ចំណាយ</span>
                </div>
            </div>
            <div class="h-64 sm:h-72 w-full">
                <canvas id="salesExpenseChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Repairs Status Breakdown -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="mb-4">
                <h3 class="text-sm font-bold text-gray-800">ស្ថានភាពសេវាកម្មជួសជុល</h3>
                <p class="text-xs text-gray-400">បែងចែកតាមដំណាក់កាលការងារ</p>
            </div>
            <div class="h-56 w-full flex items-center justify-center relative">
                <canvas id="repairsPieChart"></canvas>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs pt-4 border-t border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <span class="text-gray-600">Pending: <b>{{ $pendingRepairs }}</b></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    <span class="text-gray-600">Progress: <b>{{ $inProgressRepairs }}</b></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span class="text-gray-600">Completed: <b>{{ $completedRepairs }}</b></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                    <span class="text-gray-600">Delivered: <b>{{ $deliveredRepairs }}</b></span>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Data & Shortcuts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left 2 Cols: Recent Sales & Recent Purchases Tabs -->
        <div class="lg:col-span-2 space-y-6" x-data="{ tab: 'sales' }">
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <!-- Tabs Header -->
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <button @click="tab = 'sales'" :class="tab === 'sales' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-3.5 py-1.5 rounded-xl text-xs transition">
                            <i class="fa-solid fa-receipt mr-1"></i> ការលក់ថ្មីៗ (Sales)
                        </button>
                        <button @click="tab = 'purchases'" :class="tab === 'purchases' ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-3.5 py-1.5 rounded-xl text-xs transition">
                            <i class="fa-solid fa-truck-ramp-box mr-1"></i> ទិញចូលថ្មីៗ (Purchases)
                        </button>
                    </div>
                    <a :href="tab === 'sales' ? '{{ route('sales.index') }}' : '{{ route('purchases.index') }}'" class="text-xs font-semibold text-blue-600 hover:underline">
                        មើលទាំងអស់ →
                    </a>
                </div>

                <!-- Recent Sales Tab Table -->
                <div x-show="tab === 'sales'" class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-gray-50/60 text-gray-400 uppercase text-[11px] font-bold border-b border-gray-100">
                                <th class="py-3 px-6">Invoice</th>
                                <th class="py-3 px-6">អតិថិជន</th>
                                <th class="py-3 px-6">សរុប ($)</th>
                                <th class="py-3 px-6 text-right">ស្ថានភាព</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @forelse($recentSales as $sale)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-6 font-mono font-bold text-blue-600">
                                    <a href="{{ route('sales.show', $sale->sale_id) }}" class="hover:underline">#{{ $sale->invoice_no }}</a>
                                </td>
                                <td class="py-3.5 px-6 font-semibold text-gray-800">
                                    {{ $sale->customer->name ?? $sale->customer->customer_name ?? 'Walk-in Customer' }}
                                </td>
                                <td class="py-3.5 px-6 font-bold text-emerald-600">
                                    ${{ number_format($sale->total_amount, 2) }}
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ strtoupper($sale->payment_status) === 'PAID' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ strtoupper($sale->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-400">មិនទាន់មានការលក់នៅឡើយ</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Recent Purchases Tab Table -->
                <div x-show="tab === 'purchases'" x-cloak class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-gray-50/60 text-gray-400 uppercase text-[11px] font-bold border-b border-gray-100">
                                <th class="py-3 px-6">PO Number</th>
                                <th class="py-3 px-6">អ្នកផ្គត់ផ្គង់</th>
                                <th class="py-3 px-6">សរុប ($)</th>
                                <th class="py-3 px-6 text-right">ស្ថានភាព</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-gray-700">
                            @forelse($recentPurchases as $po)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3.5 px-6 font-mono font-bold text-indigo-600">
                                    <a href="{{ route('purchases.show', $po->purchase_id) }}" class="hover:underline">#{{ $po->purchase_no }}</a>
                                </td>
                                <td class="py-3.5 px-6 font-semibold text-gray-800">
                                    {{ $po->supplier->supplier_name ?? 'N/A' }}
                                </td>
                                <td class="py-3.5 px-6 font-bold text-indigo-600">
                                    ${{ number_format($po->total_amount, 2) }}
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ strtoupper($po->payment_status) === 'PAID' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ strtoupper($po->payment_status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-gray-400">មិនទាន់មានការទិញចូលនៅឡើយ</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <!-- Right Col: Shortcuts & Low Stock -->
        <div class="space-y-6">
            <!-- Quick Actions Panel -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-5 space-y-3">
                <h3 class="font-bold text-gray-800 text-sm">ផ្លូវកាត់រហ័ស (Quick Actions)</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('pos.index') }}" class="p-3.5 bg-gray-50 hover:bg-blue-50 hover:border-blue-200 border border-gray-100 rounded-2xl flex flex-col items-center justify-center text-center transition group">
                        <i class="fa-solid fa-cash-register text-2xl text-blue-600 mb-1.5 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-gray-700">ផ្ទាំងលក់ POS</span>
                    </a>
                    <a href="{{ route('purchases.create') }}" class="p-3.5 bg-gray-50 hover:bg-indigo-50 hover:border-indigo-200 border border-gray-100 rounded-2xl flex flex-col items-center justify-center text-center transition group">
                        <i class="fa-solid fa-truck-ramp-box text-2xl text-indigo-600 mb-1.5 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-gray-700">ទិញចូលស្តុក</span>
                    </a>
                    <a href="{{ route('repairs.create') }}" class="p-3.5 bg-gray-50 hover:bg-amber-50 hover:border-amber-200 border border-gray-100 rounded-2xl flex flex-col items-center justify-center text-center transition group">
                        <i class="fa-solid fa-screwdriver-wrench text-2xl text-amber-600 mb-1.5 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-gray-700">ទទួលជួសជុល</span>
                    </a>
                    <a href="{{ route('expenses.index') }}" class="p-3.5 bg-gray-50 hover:bg-rose-50 hover:border-rose-200 border border-gray-100 rounded-2xl flex flex-col items-center justify-center text-center transition group">
                        <i class="fa-solid fa-wallet text-2xl text-rose-600 mb-1.5 group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-semibold text-gray-700">កត់ត្រាចំណាយ</span>
                    </a>
                </div>
            </div>

            <!-- Low Stock Mini List -->
            @if($lowStockProducts->count() > 0)
            <div class="bg-white rounded-3xl border border-rose-100 shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-rose-600 flex items-center gap-1.5">
                        <i class="fa-solid fa-triangle-exclamation"></i> ទំនិញជិតអស់ពីស្តុក
                    </h4>
                    <span class="text-[11px] font-bold text-rose-500 bg-rose-50 px-2 py-0.5 rounded-full">{{ $lowStockProducts->count() }} មុខ</span>
                </div>
                <div class="space-y-2.5">
                    @foreach($lowStockProducts->take(4) as $item)
                    <div class="flex items-center justify-between text-xs pb-2 border-b border-gray-50 last:border-0 last:pb-0">
                        <span class="font-medium text-gray-700 truncate max-w-[170px]">{{ $item->product_name }}</span>
                        <span class="font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md">សល់ {{ $item->stock_quantity }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

    </div>

</div>

<!-- Chart.js Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Sales vs Purchases vs Expenses Multi-Bar Chart
    const ctxSales = document.getElementById('salesExpenseChart').getContext('2d');
    new Chart(ctxSales, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'ការលក់ ($)',
                    data: {!! json_encode($chartSales) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.85)',
                    borderRadius: 6,
                    barPercentage: 0.7,
                },
                {
                    label: 'ទិញចូល ($)',
                    data: {!! json_encode($chartPurchases) !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.85)',
                    borderRadius: 6,
                    barPercentage: 0.7,
                },
                {
                    label: 'ការចំណាយ ($)',
                    data: {!! json_encode($chartExpenses) !!},
                    backgroundColor: 'rgba(244, 63, 94, 0.85)',
                    borderRadius: 6,
                    barPercentage: 0.7,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(243, 244, 246, 1)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // Repairs Doughnut Chart
    const ctxRepairs = document.getElementById('repairsPieChart').getContext('2d');
    new Chart(ctxRepairs, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'In Progress', 'Completed', 'Delivered'],
            datasets: [{
                data: [
                    {{ $pendingRepairs }},
                    {{ $inProgressRepairs }},
                    {{ $completedRepairs }},
                    {{ $deliveredRepairs }}
                ],
                backgroundColor: ['#fbbf24', '#3b82f6', '#10b981', '#6366f1'],
                borderWidth: 2,
                borderColor: '#ffffff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endsection