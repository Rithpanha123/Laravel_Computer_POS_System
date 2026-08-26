<?php

namespace App\Http\Controllers;

use App\Exports\PurchaseExport;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'user', 'items.product']);

        // Search តាម PO Number, ឈ្មោះអ្នកផ្គត់ផ្គង់ ឬលេខទូរស័ព្ទ
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('purchase_no', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('supplier_name', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        // Filter តាម Payment Status (PAID, PARTIAL, UNPAID)
        if ($request->filled('payment_status')) {
            $query->where('payment_status', strtoupper($request->payment_status));
        }

        // Filter តាមកាលបរិច្ឆេទ
        if ($request->filled('from_date')) {
            $query->whereDate('purchase_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('purchase_date', '<=', $request->to_date);
        }

        // ទទួលយកតម្លៃ per_page (5, 10, 25, 100) - Default: 10
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 100])) {
            $perPage = 10;
        }

        $purchases = $query->latest('purchase_id')->paginate($perPage)->withQueryString();

        // ស្ថិតិសង្ខេប
        $totalPurchases = (float) Purchase::sum('total_amount');
        $totalPaid = (float) Purchase::sum('paid_amount');
        $totalDue = (float) Purchase::sum('due_amount');

        return view('purchases.index', compact('purchases', 'totalPurchases', 'totalPaid', 'totalDue'));
    }

    public function create()
    {
        $suppliers = Supplier::all(); 
        $products = Product::where('is_active', true)->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'   => 'required|exists:suppliers,supplier_id',
            'purchase_date' => 'required|date',
            'paid_amount'   => 'required|numeric|min:0',
            'discount'      => 'nullable|numeric|min:0',
            'tax'           => 'nullable|numeric|min:0',
            'notes'         => 'nullable|string',
            'items'         => 'required|array|min:1',
            'items.*.id'    => 'required|exists:products,product_id',
            'items.*.qty'   => 'required|integer|min:1',
            'items.*.cost'  => 'required|numeric|min:0',
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
            if ($paidAmount >= $totalAmount && $totalAmount > 0) {
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

            // ២. បញ្ចូល items និងបន្ថែមស្តុក
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

                // បង្កើនស្តុក និង Update តម្លៃដើមចុងក្រោយ
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

    public function edit(Purchase $purchase)
    {
        $purchase->load(['supplier', 'items.product']);
        $suppliers = Supplier::all();
        $products = Product::where('is_active', true)->get();

        return view('purchases.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'supplier_id'   => 'required|exists:suppliers,supplier_id',
            'purchase_date' => 'required|date',
            'paid_amount'   => 'required|numeric|min:0',
            'discount'      => 'nullable|numeric|min:0',
            'tax'           => 'nullable|numeric|min:0',
            'notes'         => 'nullable|string',
            'items'         => 'required|array|min:1',
            'items.*.id'    => 'required|exists:products,product_id',
            'items.*.qty'   => 'required|integer|min:1',
            'items.*.cost'  => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request, $purchase) {
            // ១. ដកចំនួនចាស់ចេញពីស្តុកទំនិញជាមុន
            foreach ($purchase->items as $oldItem) {
                Product::where('product_id', $oldItem->product_id)
                    ->decrement('stock_quantity', $oldItem->quantity);
            }

            // ២. លុប items ចាស់ៗចោល
            $purchase->items()->delete();

            // ៣. គណនាសរុបថ្មី និងបញ្ចូលទំនិញថ្មី
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
            if ($paidAmount >= $totalAmount && $totalAmount > 0) {
                $paymentStatus = 'PAID';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'PARTIAL';
            }

            // ៤. បញ្ចូល items ថ្មី និងបូកស្តុកថ្មីចូលវិញ
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

                $product->increment('stock_quantity', $item['qty']);
                $product->update(['cost_price' => $item['cost']]);
            }

            // ៥. Update លើ Purchase
            $purchase->update([
                'supplier_id'    => $request->supplier_id,
                'purchase_date'  => $request->purchase_date,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'tax'            => $tax,
                'total_amount'   => $totalAmount,
                'paid_amount'    => $paidAmount,
                'due_amount'     => $dueAmount,
                'payment_status' => $paymentStatus,
                'notes'          => $request->notes,
            ]);

            return redirect()->route('purchases.show', $purchase->purchase_id)->with('success', 'ប័ណ្ណបញ្ជាទិញត្រូវបានកែប្រែដោយជោគជ័យ!');
        });
    }

    public function destroy(Purchase $purchase)
    {
        return DB::transaction(function () use ($purchase) {
            // ដកស្តុកទំនិញចេញវិញមុនពេលលុប
            foreach ($purchase->items as $item) {
                Product::where('product_id', $item->product_id)
                    ->decrement('stock_quantity', $item->quantity);
            }

            $purchase->items()->delete();
            $purchase->delete();

            return redirect()->route('purchases.index')->with('success', 'ប័ណ្ណទិញចូលត្រូវបានលុបចេញពីប្រព័ន្ធ!');
        });
    }

    // Export Single PO to PDF
    public function exportPdf(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product']);
        $pdf = Pdf::loadView('purchases.pdf', compact('purchase'))->setPaper('a4');

        return $pdf->download("PO_{$purchase->purchase_no}.pdf");
    }

    // Export Single PO or All POs to Excel
    public function exportExcel(Purchase $purchase = null)
    {
        $purchaseId = $purchase ? $purchase->purchase_id : null;
        $fileName = $purchase ? "PO_{$purchase->purchase_no}.xlsx" : "Purchases_Report_" . date('Ymd_His') . ".xlsx";

        return Excel::download(new PurchaseExport($purchaseId), $fileName);
    }
}