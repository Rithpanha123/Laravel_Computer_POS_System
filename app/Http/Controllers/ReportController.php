<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Purchase;
use App\Models\Repair;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly'); // daily, monthly, yearly
        $selectedDate = $request->get('date', date('Y-m-d'));
        $selectedMonth = $request->get('month', date('Y-m'));
        $selectedYear = $request->get('year', date('Y'));

        $chartLabels = [];
        $chartSales = [];
        $chartPurchases = [];
        $chartExpenses = [];
        $chartNetProfits = [];

        $breakdown = [];

        if ($period === 'daily') {
            $targetDate = Carbon::parse($selectedDate);
            
            $totalSales = (float) Sale::whereDate('sale_date', $targetDate)->sum('total_amount');
            $totalPurchases = (float) Purchase::whereDate('purchase_date', $targetDate)->sum('total_amount');
            $totalExpenses = (float) Expense::whereDate('expense_date', $targetDate)->sum('amount');
            $totalRepairs = (float) Repair::whereDate('received_at', $targetDate)->sum('final_cost');
            
            for ($i = 6; $i >= 0; $i--) {
                $d = $targetDate->copy()->subDays($i);
                $dStr = $d->format('Y-m-d');
                $label = $d->format('d M');

                $s = (float) Sale::whereDate('sale_date', $dStr)->sum('total_amount');
                $p = (float) Purchase::whereDate('purchase_date', $dStr)->sum('total_amount');
                $e = (float) Expense::whereDate('expense_date', $dStr)->sum('amount');
                $net = $s - ($p + $e);

                $chartLabels[] = $label;
                $chartSales[] = $s;
                $chartPurchases[] = $p;
                $chartExpenses[] = $e;
                $chartNetProfits[] = $net;

                $breakdown[] = [
                    'date' => $d->format('d-M-Y'),
                    'sales' => $s,
                    'purchases' => $p,
                    'expenses' => $e,
                    'net' => $net
                ];
            }

        } elseif ($period === 'yearly') {
            $year = $selectedYear;

            $totalSales = (float) Sale::whereYear('sale_date', $year)->sum('total_amount');
            $totalPurchases = (float) Purchase::whereYear('purchase_date', $year)->sum('total_amount');
            $totalExpenses = (float) Expense::whereYear('expense_date', $year)->sum('amount');
            $totalRepairs = (float) Repair::whereYear('received_at', $year)->sum('final_cost');

            for ($m = 1; $m <= 12; $m++) {
                $monthDate = Carbon::createFromDate($year, $m, 1);
                $label = $monthDate->format('M');

                $s = (float) Sale::whereYear('sale_date', $year)->whereMonth('sale_date', $m)->sum('total_amount');
                $p = (float) Purchase::whereYear('purchase_date', $year)->whereMonth('purchase_date', $m)->sum('total_amount');
                $e = (float) Expense::whereYear('expense_date', $year)->whereMonth('expense_date', $m)->sum('amount');
                $net = $s - ($p + $e);

                $chartLabels[] = $label;
                $chartSales[] = $s;
                $chartPurchases[] = $p;
                $chartExpenses[] = $e;
                $chartNetProfits[] = $net;

                $breakdown[] = [
                    'date' => $monthDate->format('F Y'),
                    'sales' => $s,
                    'purchases' => $p,
                    'expenses' => $e,
                    'net' => $net
                ];
            }

        } else {
            // Default: Monthly
            $year = Carbon::parse($selectedMonth)->year;
            $month = Carbon::parse($selectedMonth)->month;
            $daysInMonth = Carbon::parse($selectedMonth)->daysInMonth;

            $totalSales = (float) Sale::whereYear('sale_date', $year)->whereMonth('sale_date', $month)->sum('total_amount');
            $totalPurchases = (float) Purchase::whereYear('purchase_date', $year)->whereMonth('purchase_date', $month)->sum('total_amount');
            $totalExpenses = (float) Expense::whereYear('expense_date', $year)->whereMonth('expense_date', $month)->sum('amount');
            $totalRepairs = (float) Repair::whereYear('received_at', $year)->whereMonth('received_at', $month)->sum('final_cost');

            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dayDate = Carbon::createFromDate($year, $month, $d);
                $dStr = $dayDate->format('Y-m-d');
                $label = $dayDate->format('d');

                $s = (float) Sale::whereDate('sale_date', $dStr)->sum('total_amount');
                $p = (float) Purchase::whereDate('purchase_date', $dStr)->sum('total_amount');
                $e = (float) Expense::whereDate('expense_date', $dStr)->sum('amount');
                $net = $s - ($p + $e);

                $chartLabels[] = $label;
                $chartSales[] = $s;
                $chartPurchases[] = $p;
                $chartExpenses[] = $e;
                $chartNetProfits[] = $net;

                if ($s > 0 || $p > 0 || $e > 0) {
                    $breakdown[] = [
                        'date' => $dayDate->format('d M Y'),
                        'sales' => $s,
                        'purchases' => $p,
                        'expenses' => $e,
                        'net' => $net
                    ];
                }
            }
        }

        $netProfit = $totalSales - ($totalPurchases + $totalExpenses);

        return view('reports.index', compact(
            'period',
            'selectedDate',
            'selectedMonth',
            'selectedYear',
            'totalSales',
            'totalPurchases',
            'totalExpenses',
            'totalRepairs',
            'netProfit',
            'chartLabels',
            'chartSales',
            'chartPurchases',
            'chartExpenses',
            'chartNetProfits',
            'breakdown'
        ));
    }
}