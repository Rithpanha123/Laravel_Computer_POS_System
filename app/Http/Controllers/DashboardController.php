<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Repair;
use App\Models\Sale;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfMonth = Carbon::now()->startOfMonth();

        // ១. ស្ថិតិការលក់ (Sales)
        $todaySales = (float) Sale::whereDate('sale_date', $today)->sum('total_amount');
        $yesterdaySales = (float) Sale::whereDate('sale_date', $yesterday)->sum('total_amount');
        $salesGrowth = $yesterdaySales > 0 ? round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1) : 0;
        $monthSales = (float) Sale::whereDate('sale_date', '>=', $startOfMonth)->sum('total_amount');
        $todayOrdersCount = Sale::whereDate('sale_date', $today)->count();

        // ២. ស្ថិតិការទិញចូលស្តុក (Purchases)
        $todayPurchases = (float) Purchase::whereDate('purchase_date', $today)->sum('total_amount');
        $monthPurchases = (float) Purchase::whereDate('purchase_date', '>=', $startOfMonth)->sum('total_amount');
        $todayPurchasesCount = Purchase::whereDate('purchase_date', $today)->count();

        // ៣. ស្ថិតិការចំណាយ (Expenses)
        $todayExpenses = (float) Expense::whereDate('expense_date', $today)->sum('amount');
        $monthExpenses = (float) Expense::whereDate('expense_date', '>=', $startOfMonth)->sum('amount');

        // ៤. ស្ថិតិការងារជួសជុល (Repairs)
        $pendingRepairs = Repair::whereIn('status', ['PENDING', 'DIAGNOSING', 'pending'])->count();
        $inProgressRepairs = Repair::whereIn('status', ['IN_PROGRESS', 'in_progress'])->count();
        $completedRepairs = Repair::whereIn('status', ['COMPLETED', 'completed'])->count();
        $deliveredRepairs = Repair::whereIn('status', ['DELIVERED', 'delivered'])->count();
        $activeRepairsCount = $pendingRepairs + $inProgressRepairs;

        // ៥. ស្ថិតិស្តុក & ទំនិញជិតអស់ (Inventory & Low Stock)
        $totalProducts = Product::where('is_active', true)->count();
        $lowStockProducts = Product::where(function ($q) {
                $q->whereColumn('stock_quantity', '<=', 'reorder_level')
                  ->orWhere('stock_quantity', '<=', 5);
            })
            ->where('is_active', true)
            ->get();
        $lowStockCount = $lowStockProducts->count();

        // ៦. ចំនួនអតិថិជន និងបុគ្គលិកសរុប
        $totalCustomers = Customer::count();
        $totalStaff = Staff::where('employment_status', 'ACTIVE')->count();

        // ៧. វិក្កយបត្រលក់ & ទិញចូល ៥ ចុងក្រោយបង្អស់
        $recentSales = Sale::with(['customer', 'items.product'])->latest('sale_id')->take(5)->get();
        $recentPurchases = Purchase::with(['supplier', 'items.product'])->latest('purchase_id')->take(5)->get();

        // ៨. ទិន្នន័យក្រាហ្វ ៧ ថ្ងៃចុងក្រោយ (Sales vs Purchases vs Expenses)
        $chartLabels = [];
        $chartSales = [];
        $chartPurchases = [];
        $chartExpenses = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartSales[] = (float) Sale::whereDate('sale_date', $date)->sum('total_amount');
            $chartPurchases[] = (float) Purchase::whereDate('purchase_date', $date)->sum('total_amount');
            $chartExpenses[] = (float) Expense::whereDate('expense_date', $date)->sum('amount');
        }

        return view('dashboard', compact(
            'todaySales',
            'salesGrowth',
            'monthSales',
            'todayOrdersCount',
            'todayPurchases',
            'monthPurchases',
            'todayPurchasesCount',
            'todayExpenses',
            'monthExpenses',
            'pendingRepairs',
            'inProgressRepairs',
            'completedRepairs',
            'deliveredRepairs',
            'activeRepairsCount',
            'totalProducts',
            'lowStockCount',
            'lowStockProducts',
            'totalCustomers',
            'totalStaff',
            'recentSales',
            'recentPurchases',
            'chartLabels',
            'chartSales',
            'chartPurchases',
            'chartExpenses'
        ));
    }
}