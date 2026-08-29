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

            $discount = (float)($request->discount ?? $request->discount_amount ?? 0);
            $tax = (float)($request->tax ?? $request->tax_amount ?? 0);
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
                'user_id'        => Auth::id() ?? 1,
                'purchase_date'  => $request->purchase_date,
                'subtotal'       => $subtotal,
                'discount'       => $discount,
                'discount_amount'=> $discount,
                'tax'            => $tax,
                'tax_amount'     => $tax,
                'total_amount'   => $totalAmount,
                'paid_amount'    => $paidAmount,
                'due_amount'     => $dueAmount,
                'payment_status' => $paymentStatus,
                'status'         => 'COMPLETED',
                'notes'          => $request->notes,
                'created_at'     => now(),
            ]);

            // ២. បញ្ចូលទំនិញនីមួយៗ និងបូកស្តុក
            foreach ($request->items as $item) {
                $qty = (int) $item['qty'];
                $cost = (float) $item['cost'];
                $itemSubtotal = $qty * $cost;

                $purchase->items()->create([
                    'product_id' => $item['id'],
                    'quantity'   => $qty,
                    'unit_cost'  => $cost,
                    'total'      => $itemSubtotal,
                    'subtotal'   => $itemSubtotal,
                ]);

                // បូកស្តុកទំនិញចូល
                $product = Product::where('product_id', $item['id'])->first();
                if ($product) {
                    $product->increment('stock_quantity', $qty);
                }
            }

            return redirect()->route('purchases.index')->with('success', 'បានបញ្ចូលវិក្កយបត្រទិញចូលជោគជ័យ!');
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
            'supplier_id'   => 'required',
            'items'         => 'required|array|min:1',
            'items.*.id'    => 'required',
            'items.*.qty'   => 'required|integer|min:1',
            'items.*.cost'  => 'required|numeric|min:0',
            'paid_amount'   => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $purchase) {
                
                // ១. ផ្ទៀងផ្ទាត់ និងដកស្តុកចាស់ចេញវិញដោយសុវត្ថិភាព
                foreach ($purchase->items as $oldItem) {
                    $product = Product::where('product_id', $oldItem->product_id)->lockForUpdate()->first();
                    if ($product) {
                        if ($product->stock_quantity < $oldItem->quantity) {
                            throw new \Exception("មិនអាចកែប្រែបានទេ ពីព្រោះទំនិញ '{$product->product_name}' ត្រូវបានលក់ចេញខ្លះហើយ! (ស្តុកបច្ចុប្បន្ននៅសល់តែ: {$product->stock_quantity})");
                        }
                        $product->decrement('stock_quantity', $oldItem->quantity);
                    }
                }

                // ២. លុប items ចាស់ៗចោល
                $purchase->items()->delete();

                // ៣. បញ្ចូល items ថ្មី និងបូកស្តុកថ្មីចូល
                $subtotal = 0;
                foreach ($request->items as $item) {
                    $qty = (int) $item['qty'];
                    $cost = (float) $item['cost'];
                    $itemSubtotal = $qty * $cost;
                    $subtotal += $itemSubtotal;

                    $purchase->items()->create([
                        'product_id' => $item['id'],
                        'quantity'   => $qty,
                        'unit_cost'  => $cost,
                        'total'      => $itemSubtotal,     // បន្ថែមត្រង់នេះដើម្បីកុំឱ្យ Error Not-null
                        'subtotal'   => $itemSubtotal,
                    ]);

                    // បូកស្តុកទំនិញថ្មីចូល
                    $product = Product::where('product_id', $item['id'])->first();
                    if ($product) {
                        $product->increment('stock_quantity', $qty);
                    }
                }

                // ៤. ធ្វើបច្ចុប្បន្នភាពព័ត៌មានវិក្កយបត្រ Purchase
                $discountAmount = (float) ($request->discount_amount ?? $request->discount ?? 0);
                $taxAmount      = (float) ($request->tax_amount ?? $request->tax ?? 0);
                $totalAmount    = max(0, ($subtotal - $discountAmount) + $taxAmount);
                $paidAmount     = (float) $request->paid_amount;
                $dueAmount      = max(0, $totalAmount - $paidAmount);

                $paymentStatus = 'UNPAID';
                if ($paidAmount >= $totalAmount && $totalAmount > 0) {
                    $paymentStatus = 'PAID';
                } elseif ($paidAmount > 0) {
                    $paymentStatus = 'PARTIAL';
                }

                $purchase->update([
                    'supplier_id'     => $request->supplier_id,
                    'purchase_date'   => $request->purchase_date ?? $purchase->purchase_date,
                    'subtotal'        => $subtotal,
                    'discount'        => $discountAmount,
                    'discount_amount' => $discountAmount,
                    'tax'             => $taxAmount,
                    'tax_amount'      => $taxAmount,
                    'total_amount'    => $totalAmount,
                    'paid_amount'     => $paidAmount,
                    'due_amount'      => $dueAmount,
                    'payment_status'  => $paymentStatus,
                    'notes'           => $request->notes ?? null,
                ]);
            });

            return redirect()->route('purchases.index')->with('success', 'បានកែប្រែវិក្កយបត្រទិញចូលជោគជ័យ!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
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