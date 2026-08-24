<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // ១. សរុបការលក់ថ្ងៃនេះ & ម្សិលមិញ
        $todaySales = Sale::whereDate('sale_date', $today)->sum('total_amount');
        $yesterdaySales = Sale::whereDate('sale_date', $yesterday)->sum('total_amount');

        $salesGrowth = 0;
        if ($yesterdaySales > 0) {
            $salesGrowth = round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1);
        }

        // ២. ចំនួនវិក្កយបត្រថ្ងៃនេះ
        $todayOrdersCount = Sale::whereDate('sale_date', $today)->count();

        // ៣. ចំនួនទំនិញជិតអស់ស្តុក (Low Stock Alert)
        $lowStockProducts = Product::whereColumn('stock_quantity', '<=', 'reorder_level')
            ->orWhere('stock_quantity', '<=', 5)
            ->where('is_active', true)
            ->get();
        $lowStockCount = $lowStockProducts->count();

        // ៤. វិក្កយបត្រលក់ថ្មីៗ ៥ ចុងក្រោយ
        $recentSales = Sale::with(['customer', 'items.product'])
            ->latest('sale_id')
            ->take(5)
            ->get();

        // ៥. សរុបទូទៅ
        $totalCustomers = Customer::count();
        $totalProducts = Product::where('is_active', true)->count();

        return view('dashboard', compact(
            'todaySales',
            'salesGrowth',
            'todayOrdersCount',
            'lowStockCount',
            'lowStockProducts',
            'recentSales',
            'totalCustomers',
            'totalProducts'
        ));
    }
}