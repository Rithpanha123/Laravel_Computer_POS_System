@extends('layouts.app')

@section('title', 'របាយការណ៍ចំណូល-ចំណាយ - POS System')
@section('page_heading', 'Financial & Profit/Loss Reports')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">

    <!-- Header & Filter Controls -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 no-print">
        <div>
            <h2 class="text-xl font-bold text-gray-800">របាយការណ៍ហិរញ្ញវត្ថុ & ប្រាក់ចំណេញសុទ្ធ</h2>
            <p class="text-xs sm:text-sm text-gray-500">តាមដានចំណូលពីការលក់ ទិញចូលស្តុក ការចំណាយទូទៅ និងប្រាក់ចំណេញសុទ្ធ</p>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-center gap-2 bg-white p-2 rounded-2xl border border-gray-100 shadow-sm">
            <select name="period" onchange="this.form.submit()" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold text-gray-700 outline-none">
                <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>ប្រចាំថ្ងៃ (Daily)</option>
                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>ប្រចាំខែ (Monthly)</option>
                <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>ប្រចាំឆ្នាំ (Yearly)</option>
            </select>

            @if($period === 'daily')
                <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()" class="px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-xs outline-none">
            @elseif($period === 'yearly')
                <select name="year" onchange="this.form.submit()" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold outline-none">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>ឆ្នាំ {{ $y }}</option>
                    @endfor
                </select>
            @else
                <input type="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()" class="px-3 py-1.5 bg-gray-50 border border-gray-200 rounded-xl text-xs outline-none">
            @endif

            <button type="button" onclick="window.print()" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-print"></i> បោះពុម្ព
            </button>
        </form>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1: Total Sales -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">ចំណូលលក់សរុប (Revenue)</p>
                <h3 class="text-2xl font-black text-blue-600 mt-1">${{ number_format($totalSales, 2) }}</h3>
                <p class="text-[11px] text-gray-400 mt-1">ពីការលក់ POS ទាំងអស់</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
        </div>

        <!-- Card 2: Purchases Cost -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">ថ្លៃទិញចូល (Cost of Goods)</p>
                <h3 class="text-2xl font-black text-indigo-600 mt-1">${{ number_format($totalPurchases, 2) }}</h3>
                <p class="text-[11px] text-gray-400 mt-1">ថ្លៃទំនិញនាំចូលស្តុក</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
        </div>

        <!-- Card 3: Expenses -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">ចំណាយទូទៅ (Expenses)</p>
                <h3 class="text-2xl font-black text-rose-600 mt-1">${{ number_format($totalExpenses, 2) }}</h3>
                <p class="text-[11px] text-gray-400 mt-1">ថ្លៃជួល ទឹកភ្លើង ប្រាក់ខែ</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-arrow-down-wide-short"></i>
            </div>
        </div>

        <!-- Card 4: Net Profit / Loss -->
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">ចំណេញសុទ្ធ (Net Profit)</p>
                <h3 class="text-2xl font-black {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-1">
                    ${{ number_format($netProfit, 2) }}
                </h3>
                <p class="text-[11px] font-medium {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-500' }} mt-1">
                    <i class="fa-solid {{ $netProfit >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1"></i>
                    ចំណូល - (ទិញចូល + ចំណាយ)
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl {{ $netProfit >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center text-xl">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
        </div>

    </div>

    <!-- Chart: Performance Timeline -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm space-y-4 no-print">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-sm font-bold text-gray-800">ក្រាហ្វិកប្រៀបធៀបចំណូល ចំណាយ និងប្រាក់ចំណេញសុទ្ធ</h3>
                <p class="text-xs text-gray-400">ស្ថិតិលម្អិតតាមកាលវិភាគដែលបានជ្រើសរើស</p>
            </div>
            <div class="flex items-center gap-4 text-xs font-semibold">
                <span class="flex items-center gap-1 text-blue-600"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> ការលក់</span>
                <span class="flex items-center gap-1 text-indigo-600"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> ទិញចូល</span>
                <span class="flex items-center gap-1 text-rose-500"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> ចំណាយ</span>
                <span class="flex items-center gap-1 text-emerald-600"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> ចំណេញសុទ្ធ</span>
            </div>
        </div>
        <div class="h-72 w-full">
            <canvas id="financialReportChart"></canvas>
        </div>
    </div>

    <!-- Detailed Breakdown Table -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden print-container">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 text-sm">តារាងលម្អិតចំណូល-ចំណាយ (Financial Breakdown)</h3>
            <span class="text-xs text-gray-400">របៀប៖ <b class="uppercase text-gray-700">{{ $period }}</b></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 border-b border-gray-100 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">កាលបរិច្ឆេទ / រយៈពេល</th>
                        <th class="py-3.5 px-6 text-right">ការលក់ចេញ ($)</th>
                        <th class="py-3.5 px-6 text-right">ទិញចូលស្តុក ($)</th>
                        <th class="py-3.5 px-6 text-right">ចំណាយទូទៅ ($)</th>
                        <th class="py-3.5 px-6 text-right">ចំណេញសុទ្ធ / Net ($)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($breakdown as $row)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-3.5 px-6 font-semibold text-gray-800">{{ $row['date'] }}</td>
                        <td class="py-3.5 px-6 text-right font-bold text-blue-600">${{ number_format($row['sales'], 2) }}</td>
                        <td class="py-3.5 px-6 text-right font-medium text-indigo-600">${{ number_format($row['purchases'], 2) }}</td>
                        <td class="py-3.5 px-6 text-right font-medium text-rose-500">${{ number_format($row['expenses'], 2) }}</td>
                        <td class="py-3.5 px-6 text-right font-black {{ $row['net'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            ${{ number_format($row['net'], 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">គ្មានទិន្នន័យសម្រាប់ចន្លោះពេលនេះទេ</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50/80 font-bold border-t border-gray-200 text-xs">
                    <tr>
                        <td class="py-3.5 px-6 text-gray-800 uppercase">សរុបរួម (Grand Total)</td>
                        <td class="py-3.5 px-6 text-right text-blue-600">${{ number_format($totalSales, 2) }}</td>
                        <td class="py-3.5 px-6 text-right text-indigo-600">${{ number_format($totalPurchases, 2) }}</td>
                        <td class="py-3.5 px-6 text-right text-rose-500">${{ number_format($totalExpenses, 2) }}</td>
                        <td class="py-3.5 px-6 text-right text-base {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            ${{ number_format($netProfit, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<!-- Chart Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('financialReportChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: 'ការលក់ ($)',
                    data: {!! json_encode($chartSales) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.85)',
                    borderRadius: 6,
                },
                {
                    label: 'ទិញចូល ($)',
                    data: {!! json_encode($chartPurchases) !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.85)',
                    borderRadius: 6,
                },
                {
                    label: 'ចំណាយ ($)',
                    data: {!! json_encode($chartExpenses) !!},
                    backgroundColor: 'rgba(244, 63, 94, 0.85)',
                    borderRadius: 6,
                },
                {
                    label: 'ចំណេញសុទ្ធ ($)',
                    data: {!! json_encode($chartNetProfits) !!},
                    type: 'line',
                    borderColor: '#10b981',
                    borderWidth: 3,
                    pointBackgroundColor: '#10b981',
                    fill: false,
                    tension: 0.3
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
});
</script>

<style>
@media print {
    body * { visibility: hidden; }
    .no-print { display: none !important; }
    .print-container, .print-container * { visibility: visible; }
    .print-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection