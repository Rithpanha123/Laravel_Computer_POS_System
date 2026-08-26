@extends('layouts.app')

@section('title', 'របាយការណ៍ចំណូល-ចំណាយ - POS System')
@section('page_heading', 'Financial & Profit/Loss Reports')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

<div class="space-y-6 p-4 sm:p-6 rounded-3xl animated-gradient-bg relative overflow-hidden">

    <!-- Floating Background Decorative Particles -->
    <div class="absolute -top-10 -left-10 w-72 h-72 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-3xl pointer-events-none floating-particle"></div>
    <div class="absolute top-1/2 -right-10 w-72 h-72 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl pointer-events-none floating-particle" style="animation-delay: 3s;"></div>

    <!-- Header & Filter Controls -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 no-print relative z-10">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">របាយការណ៍ហិរញ្ញវត្ថុ & ប្រាក់ចំណេញសុទ្ធ</h2>
            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">តាមដានចំណូលពីការលក់ ទិញចូលស្តុក ការចំណាយទូទៅ និងប្រាក់ចំណេញសុទ្ធ</p>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-center gap-2 bg-white dark:bg-slate-800 p-2 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm">
            <select name="period" onchange="this.form.submit()" class="px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-200 outline-none">
                <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>ប្រចាំថ្ងៃ (Daily)</option>
                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>ប្រចាំខែ (Monthly)</option>
                <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>ប្រចាំឆ្នាំ (Yearly)</option>
            </select>

            @if($period === 'daily')
                <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()" class="px-3 py-1.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 outline-none">
            @elseif($period === 'yearly')
                <select name="year" onchange="this.form.submit()" class="px-3 py-2 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-200 outline-none">
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>ឆ្នាំ {{ $y }}</option>
                    @endfor
                </select>
            @else
                <input type="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()" class="px-3 py-1.5 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-200 outline-none">
            @endif

            <button type="button" onclick="window.print()" class="magnetic-btn px-3.5 py-2 bg-slate-900 hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-md">
                <i class="fa-solid fa-print"></i> បោះពុម្ព
            </button>
        </form>
    </div>

    <!-- Summary KPI Cards with Count-up Animation -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 relative z-10" x-data="countUpMetrics()">
        
        <!-- Card 1: Total Sales -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-400">ចំណូលលក់សរុប (Revenue)</p>
                <h3 class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1" x-text="formatCurrency(displaySales)">$0.00</h3>
                <p class="text-[11px] text-gray-400 mt-1">ពីការលក់ POS ទាំងអស់</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl border border-blue-100 dark:border-blue-900 shadow-inner">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
        </div>

        <!-- Card 2: Purchases Cost -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-400">ថ្លៃទិញចូល (Cost of Goods)</p>
                <h3 class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mt-1" x-text="formatCurrency(displayPurchases)">$0.00</h3>
                <p class="text-[11px] text-gray-400 mt-1">ថ្លៃទំនិញនាំចូលស្តុក</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl border border-indigo-100 dark:border-indigo-900 shadow-inner">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
        </div>

        <!-- Card 3: Expenses -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-400">ចំណាយទូទៅ (Expenses)</p>
                <h3 class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1" x-text="formatCurrency(displayExpenses)">$0.00</h3>
                <p class="text-[11px] text-gray-400 mt-1">ថ្លៃជួល ទឹកភ្លើង ប្រាក់ខែ</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl border border-rose-100 dark:border-rose-900 shadow-inner">
                <i class="fa-solid fa-arrow-down-wide-short"></i>
            </div>
        </div>

        <!-- Card 4: Net Profit / Loss -->
        <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-400">ចំណេញសុទ្ធ (Net Profit)</p>
                <h3 class="text-2xl font-black {{ $netProfit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} mt-1" x-text="formatCurrency(displayNet)">
                    $0.00
                </h3>
                <p class="text-[11px] font-medium {{ $netProfit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500 dark:text-rose-400' }} mt-1">
                    <i class="fa-solid {{ $netProfit >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1"></i>
                    ចំណូល - (ទិញចូល + ចំណាយ)
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl {{ $netProfit >= 0 ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900' : 'bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-900' }} flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
        </div>

    </div>

    <!-- Chart: Performance Timeline -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 p-6 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm space-y-4 no-print relative z-10">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-sm font-bold text-gray-800 dark:text-white">ក្រាហ្វិកប្រៀបធៀបចំណូល ចំណាយ និងប្រាក់ចំណេញសុទ្ធ</h3>
                <p class="text-xs text-gray-400">ស្ថិតិលម្អិតតាមកាលវិភាគដែលបានជ្រើសរើស</p>
            </div>
            <div class="flex items-center gap-4 text-xs font-semibold">
                <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> ការលក់</span>
                <span class="flex items-center gap-1 text-indigo-600 dark:text-indigo-400"><span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span> ទិញចូល</span>
                <span class="flex items-center gap-1 text-rose-500 dark:text-rose-400"><span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> ចំណាយ</span>
                <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> ចំណេញសុទ្ធ</span>
            </div>
        </div>
        <div class="h-72 w-full">
            <canvas id="financialReportChart"></canvas>
        </div>
    </div>

    <!-- Detailed Breakdown Table -->
    <div class="card-3d-hover glow-card bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden print-container relative z-10">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
            <h3 class="font-bold text-gray-800 dark:text-white text-sm">តារាងលម្អិតចំណូល-ចំណាយ (Financial Breakdown)</h3>
            <span class="text-xs text-gray-400">របៀប៖ <b class="uppercase text-gray-700 dark:text-gray-300">{{ $period }}</b></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-slate-900/50 border-b border-gray-100 dark:border-slate-700 text-[11px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-6">កាលបរិច្ឆេទ / រយៈពេល</th>
                        <th class="py-3.5 px-6 text-right">ការលក់ចេញ ($)</th>
                        <th class="py-3.5 px-6 text-right">ទិញចូលស្តុក ($)</th>
                        <th class="py-3.5 px-6 text-right">ចំណាយទូទៅ ($)</th>
                        <th class="py-3.5 px-6 text-right">ចំណេញសុទ្ធ / Net ($)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-gray-700 dark:text-gray-300">
                    @forelse($breakdown as $row)
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-slate-700/50 transition">
                        <td class="py-3.5 px-6 font-semibold text-gray-800 dark:text-white">{{ $row['date'] }}</td>
                        <td class="py-3.5 px-6 text-right font-bold text-blue-600 dark:text-blue-400">${{ number_format($row['sales'], 2) }}</td>
                        <td class="py-3.5 px-6 text-right font-medium text-indigo-600 dark:text-indigo-400">${{ number_format($row['purchases'], 2) }}</td>
                        <td class="py-3.5 px-6 text-right font-medium text-rose-500 dark:text-rose-400">${{ number_format($row['expenses'], 2) }}</td>
                        <td class="py-3.5 px-6 text-right font-black {{ $row['net'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            ${{ number_format($row['net'], 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-400">គ្មានទិន្នន័យសម្រាប់ចន្លោះពេលនេះទេ</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50/80 dark:bg-slate-900/80 font-bold border-t border-gray-200 dark:border-slate-700 text-xs">
                    <tr>
                        <td class="py-3.5 px-6 text-gray-800 dark:text-white uppercase">សរុបរួម (Grand Total)</td>
                        <td class="py-3.5 px-6 text-right text-blue-600 dark:text-blue-400">${{ number_format($totalSales, 2) }}</td>
                        <td class="py-3.5 px-6 text-right text-indigo-600 dark:text-indigo-400">${{ number_format($totalPurchases, 2) }}</td>
                        <td class="py-3.5 px-6 text-right text-rose-500 dark:text-rose-400">${{ number_format($totalExpenses, 2) }}</td>
                        <td class="py-3.5 px-6 text-right text-base {{ $netProfit >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            ${{ number_format($netProfit, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<!-- Count-up & Chart Script -->
<script>
function countUpMetrics() {
    return {
        displaySales: 0,
        displayPurchases: 0,
        displayExpenses: 0,
        displayNet: 0,
        targetSales: {{ $totalSales }},
        targetPurchases: {{ $totalPurchases }},
        targetExpenses: {{ $totalExpenses }},
        targetNet: {{ $netProfit }},
        init() {
            let duration = 1200;
            let steps = 40;
            let stepTime = duration / steps;
            let currentStep = 0;

            let timer = setInterval(() => {
                currentStep++;
                let progress = currentStep / steps;
                
                this.displaySales = this.targetSales * progress;
                this.displayPurchases = this.targetPurchases * progress;
                this.displayExpenses = this.targetExpenses * progress;
                this.displayNet = this.targetNet * progress;

                if (currentStep >= steps) {
                    this.displaySales = this.targetSales;
                    this.displayPurchases = this.targetPurchases;
                    this.displayExpenses = this.targetExpenses;
                    this.displayNet = this.targetNet;
                    clearInterval(timer);
                }
            }, stepTime);
        },
        formatCurrency(value) {
            return '$' + Number(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
    }
}

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
            animation: {
                duration: 1500,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.1)' }
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
        background: white !important;
        color: black !important;
    }
}
</style>
@endsection