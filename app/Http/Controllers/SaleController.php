<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(Request $request)
{
    $query = Sale::with(['customer', 'user', 'items']);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('invoice_no', 'like', "%{$search}%")
              ->orWhereHas('customer', function ($cq) use ($search) {
                  $cq->where('customer_name', 'like', "%{$search}%")
                     ->orWhere('phone', 'like', "%{$search}%");
              });
        });
    }

    if ($request->filled('payment_status')) {
        $query->where('payment_status', strtoupper($request->payment_status));
    }

    if ($request->filled('from_date')) {
        $query->whereDate('sale_date', '>=', $request->from_date);
    }

    $perPage = (int) $request->get('per_page', 10);
    if (!in_array($perPage, [5, 10, 25, 100])) {
        $perPage = 10;
    }

    // សំខាន់៖ ត្រូវមាន withQueryString()
    $sales = $query->latest('sale_id')->paginate($perPage)->withQueryString();

    $totalRevenue = (float) Sale::sum('total_amount');
    $totalPaid = (float) Sale::sum('paid_amount');
    $totalDue = (float) Sale::sum('due_amount');

    return view('sales.index', compact('sales', 'totalRevenue', 'totalPaid', 'totalDue'));
}

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product']);
        return view('sales.show', compact('sale'));
    }
    public function posIndex()
{
    $categories = Category::all();
    $customers = Customer::all();
    $products = Product::where('is_active', true)
        ->where('stock_quantity', '>', 0)
        ->get();

    return view('pos.index', compact('categories', 'customers', 'products'));
}
}