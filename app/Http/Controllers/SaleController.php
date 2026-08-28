<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    /**
     * បង្ហាញប្រវត្តិវិក្កយបត្រលក់
     */
    public function index(Request $request)
    {
        $query = Sale::with(['customer', 'user']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'ilike', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('customer_name', 'ilike', "%{$search}%")
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

        $perPage = in_array((int) $request->get('per_page'), [5, 10, 25, 100])
            ? (int) $request->get('per_page')
            : 10;

        $sales = $query->orderBy('sale_id', 'desc')->paginate($perPage)->withQueryString();

        $totalSales   = Sale::count();
        $totalRevenue = (float) Sale::sum('total_amount');
        $totalPaid    = (float) Sale::sum('paid_amount');
        $totalDue     = (float) Sale::sum('due_amount');

        return view('sales.index', compact('sales', 'totalSales', 'totalRevenue', 'totalPaid', 'totalDue'));
    }

    /**
     * ផ្ទាំង POS Screen
     */
    public function posIndex()
    {
        $categories = Category::all();
        $customers  = Customer::all();
        $products   = Product::where('is_active', true)
                             ->where('stock_quantity', '>', 0)
                             ->get();

        $paymentMethods = [
            'Cash' => 'សាច់ប្រាក់ (Cash)',
            'KHQR' => 'ABA KHQR / Bakong',
            'Card' => 'Credit / Debit Card',
            'Wing' => 'Wing Money',
        ];

        return view('pos.index', compact('categories', 'customers', 'products', 'paymentMethods'));
    }

    /**
     * មើលព័ត៌មានលម្អិតនៃវិក្កយបត្រ
     */
    public function show($id)
    {
        $sale = Sale::with(['customer', 'user'])->where('sale_id', $id)->firstOrFail();

        return view('sales.show', compact('sale'));
    }

    /**
     * រក្សាទុកការលក់ និងកាត់ស្តុកទំនិញ
     */
    public function store(Request $request)
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required',
            'items.*.qty'    => 'required|integer|min:1',
            'items.*.price'  => 'required|numeric',
            'payment_method' => 'nullable|string',
        ], [
            'items.required' => 'សូមជ្រើសរើសមុខទំនិញយ៉ាងហោចណាស់ ១ មុខ!',
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {
                $subtotal  = 0;
                $soldItems = [];

                foreach ($request->items as $item) {
                    $product = Product::where('product_id', $item['id'])->first();
                    $qty     = (int) $item['qty'];
                    $price   = (float) $item['price'];
                    $itemSubtotal = $qty * $price;
                    $subtotal += $itemSubtotal;

                    $soldItems[] = [
                        'product_id'   => $item['id'],
                        'product_name' => $product ? ($product->product_name ?? $product->name) : 'ទំនិញ',
                        'quantity'     => $qty,
                        'unit_price'   => $price,
                        'subtotal'     => $itemSubtotal,
                    ];

                    // កាត់ស្តុកទំនិញ
                    if ($product) {
                        $product->decrement('stock_quantity', $qty);
                    }
                }

                $discountAmount = (float) ($request->discount_amount ?? 0);
                $taxAmount      = (float) ($request->tax_amount ?? 0);
                $totalAmount    = $subtotal - $discountAmount + $taxAmount;
                $paidAmount     = $request->filled('paid_amount') ? (float) $request->paid_amount : $totalAmount;
                $dueAmount      = max(0, $totalAmount - $paidAmount);
                $paymentStatus  = $dueAmount > 0 ? 'PARTIAL' : 'PAID';

                // បង្កើតលេខវិក្កយបត្រ (INV-YYMMDD-XXXX)
                $invoiceNo = 'INV-' . date('ymd') . '-' . strtoupper(substr(uniqid(), -4));

                return Sale::create([
                    'invoice_no'      => $invoiceNo,
                    'customer_id'     => $request->customer_id ?: null,
                    'user_id'         => Auth::id() ?? 1,
                    'sale_date'       => now(),
                    'subtotal'        => $subtotal,
                    'discount_amount' => $discountAmount,
                    'tax_amount'      => $taxAmount,
                    'total_amount'    => $totalAmount,
                    'paid_amount'     => $paidAmount,
                    'due_amount'      => $dueAmount,
                    'payment_status'  => $paymentStatus,
                    'payment_method'  => $request->payment_method ?? 'Cash',
                    'notes'           => $request->notes ?? null,
                    'items'           => $soldItems,
                    'created_at'      => now(),
                ]);
            });

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'ការលក់ទំនិញបានជោគជ័យ!',
                    'sale_id' => $sale->sale_id,
                ], 200);
            }

            return redirect()->route('sales.index')->with('success', 'ការលក់ទំនិញបានជោគជ័យ!');

        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'ការលក់បរាជ័យ៖ ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'ការលក់បរាជ័យ៖ ' . $e->getMessage())->withInput();
        }
    }

    /**
     * លុបវិក្កយបត្រ និងបង្វិលស្តុកចូលវិញ
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $sale = Sale::where('sale_id', $id)->firstOrFail();

                if (!empty($sale->items) && is_array($sale->items)) {
                    foreach ($sale->items as $item) {
                        $product = Product::where('product_id', $item['product_id'])->first();
                        if ($product) {
                            $product->increment('stock_quantity', $item['quantity']);
                        }
                    }
                }

                $sale->delete();
            });

            return redirect()->route('sales.index')->with('success', 'បានលុបវិក្កយបត្រ និងបង្វិលស្តុកចូលវិញជោគជ័យ!');
        } catch (\Exception $e) {
            return redirect()->route('sales.index')->with('error', 'ការលុបបរាជ័យ៖ ' . $e->getMessage());
        }
    }
}