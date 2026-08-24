<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'user', 'items.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $sales = $query->latest('sale_id')->paginate(10)->withQueryString();
        $totalRevenue = Sale::sum('total_amount');
        $totalPaid = Sale::sum('paid_amount');
        $totalDue = Sale::sum('due_amount');

        return view('sales.index', compact('sales', 'totalRevenue', 'totalPaid', 'totalDue'));
    }

    /**
     * បង្ហាញផ្ទាំង POS Terminal
     */
    public function posIndex()
    {
        $products = Product::where('is_active', true)->where('stock_quantity', '>', 0)->get();
        $customers = Customer::all();

        return view('pos.index', compact('products', 'customers'));
    }

    /**
     * រក្សាទុកការលក់ (Checkout & Insert Sale)
     */
    public function store(Request $request)
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,product_id',
            'items.*.qty'    => 'required|integer|min:1',
            'items.*.price'  => 'required|numeric|min:0',
            'customer_id'    => 'nullable|exists:customers,customer_id',
            'paid_amount'    => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'tax'            => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $subtotal = 0;

            // ផ្ទៀងផ្ទាត់ និងគណនា Subtotal
            foreach ($request->items as $item) {
                $product = Product::lockForUpdate()->find($item['id']);
                if ($product->stock_quantity < $item['qty']) {
                    return back()->with('error', "ទំនិញ {$product->product_name} មានស្តុកមិនគ្រប់គ្រាន់ទេ!");
                }
                $subtotal += ($item['price'] * $item['qty']);
            }

            $discount = (float)($request->discount ?? 0);
            $tax = (float)($request->tax ?? 0);
            $totalAmount = max(0, ($subtotal - $discount) + $tax);
            $paidAmount = (float)$request->paid_amount;
            $dueAmount = max(0, $totalAmount - $paidAmount);

            // កំណត់ Payment Status ជា UPPERCASE តាម CHECK constraint របស់ DB
            $paymentStatus = 'UNPAID';
            if ($paidAmount >= $totalAmount) {
                $paymentStatus = 'PAID';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'PARTIAL';
            }

            // ១. បញ្ចូលក្នុងតារាង sales
            // ១. បញ្ចូលក្នុងតារាង sales
$sale = Sale::create([
    'invoice_no'     => 'INV-' . strtoupper(Str::random(8)),
    'customer_id'    => $request->customer_id,
    'user_id'        => Auth::user()->user_id ?? 1,
    'sale_date'      => now(),
    'subtotal'       => $subtotal,
    'discount'       => $discount,
    'tax'            => $tax,
    'total_amount'   => $totalAmount,
    'paid_amount'    => $paidAmount,
    'due_amount'     => $dueAmount,
    'payment_status' => $paymentStatus, // 'PAID', 'PARTIAL', 'UNPAID'
    'status'         => 'COMPLETED',    // <-- កែជាអក្សរធំ COMPLETED
    'notes'          => $request->notes,
    'created_at'     => now(),
]);

            // ២. បញ្ចូលក្នុងតារាង sale_items និងដកស្តុក
           // ២. បញ្ចូលក្នុងតារាង sale_items និងដកស្តុក
// ២. បញ្ចូលក្នុងតារាង sale_items និងដកស្តុក
foreach ($request->items as $item) {
    $product = Product::find($item['id']);
    $itemTotal = (float)($item['price'] * $item['qty']);

    SaleItem::create([
        'sale_id'    => $sale->sale_id,
        'product_id' => $product->product_id,
        'quantity'   => $item['qty'],
        'unit_price' => $item['price'],
        'discount'   => 0.00,
        'total'      => $itemTotal,
    ]);

    // កាត់ស្តុកទំនិញ
    $product->decrement('stock_quantity', $item['qty']);
}

            return redirect()->route('sales.index')->with('success', "ការលក់ទទួលបានជោគជ័យ! វិក្កយបត្រលេខ #{$sale->invoice_no}");
        });
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product']);
        return view('sales.show', compact('sale'));
    }
}