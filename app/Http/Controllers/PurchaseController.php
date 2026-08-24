<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'user', 'items.product']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('purchase_no', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('supplier_name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $purchases = $query->latest('purchase_id')->paginate(10)->withQueryString();
        $totalPurchases = Purchase::sum('total_amount');
        $totalPaid = Purchase::sum('paid_amount');
        $totalDue = Purchase::sum('due_amount');

        return view('purchases.index', compact('purchases', 'totalPurchases', 'totalPaid', 'totalDue'));
    }

   public function create()
{
    // ដក where('is_active', true) ចេញពី Supplier
    $suppliers = Supplier::all(); 
    $products = Product::where('is_active', true)->get();

    return view('purchases.create', compact('suppliers', 'products'));
}

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'    => 'required|exists:suppliers,supplier_id',
            'purchase_date'  => 'required|date',
            'paid_amount'    => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'tax'            => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:products,product_id',
            'items.*.qty'    => 'required|integer|min:1',
            'items.*.cost'   => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += ($item['cost'] * $item['qty']);
            }

            $discount = (float)($request->discount ?? 0);
            $tax = (float)($request->tax ?? 0);
            $totalAmount = max(0, ($subtotal - $discount) + $tax);
            $paidAmount = (float)$request->paid_amount;
            $dueAmount = max(0, $totalAmount - $paidAmount);

            $paymentStatus = 'UNPAID';
            if ($paidAmount >= $totalAmount) {
                $paymentStatus = 'PAID';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'PARTIAL';
            }

            // ១. បញ្ចូលក្នុងតារាង purchases
            $purchase = Purchase::create([
                'purchase_no'    => 'PO-' . strtoupper(Str::random(8)),
                'supplier_id'    => $request->supplier_id,
                'user_id'        => Auth::user()->user_id ?? 1,
                'purchase_date'  => $request->purchase_date,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'tax'            => $tax,
                'total_amount'   => $totalAmount,
                'paid_amount'    => $paidAmount,
                'due_amount'     => $dueAmount,
                'payment_status' => $paymentStatus,
                'status'         => 'COMPLETED',
                'notes'          => $request->notes,
                'created_at'     => now(),
            ]);

            // ២. បញ្ចូល items និងបន្ថែមស្តុក (Stock Increment)
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);
                $itemTotal = (float)($item['cost'] * $item['qty']);

                PurchaseItem::create([
                    'purchase_id' => $purchase->purchase_id,
                    'product_id'  => $product->product_id,
                    'quantity'    => $item['qty'],
                    'unit_cost'   => $item['cost'],
                    'total'       => $itemTotal,
                ]);

                // បង្កើនស្តុក និង Update តម្លៃដើម (Cost Price) ចុងក្រោយ
                $product->increment('stock_quantity', $item['qty']);
                $product->update(['cost_price' => $item['cost']]);
            }

            return redirect()->route('purchases.index')->with('success', "ការទិញទំនិញចូលស្តុកបានជោគជ័យ! ប័ណ្ណបញ្ជាទិញលេខ #{$purchase->purchase_no}");
        });
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product']);
        return view('purchases.show', compact('purchase'));
    }
}