<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosApiController extends Controller
{
    /**
     * ទាញយកបញ្ជីទំនិញ (ជាមួយមុខងារ Search តាម Name, SKU, Barcode)
     */
    public function getProducts(Request $request)
    {
        $query = Product::where('is_active', true);

        if ($request->filled('search')) {
            $search = trim($request->search);
            // ប្រើ ILIKE សម្រាប់ PostgreSQL (Case-insensitive) ឬ LIKE សម្រាប់ MySQL
            $operator = DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';

            $query->where(function ($q) use ($search, $operator) {
                $q->where('product_name', $operator, "%{$search}%")
                  ->orWhere('sku', $operator, "%{$search}%")
                  ->orWhere('barcode', $operator, "%{$search}%");
            });
        }

        $products = $query->orderBy('product_id', 'desc')->get()->map(function ($p) {
            return [
                'id'        => $p->product_id,
                'name'      => $p->product_name,
                'sku'       => $p->sku,
                'barcode'   => $p->barcode,
                'price'     => (float)($p->selling_price ?? 0),
                'stock'     => (int)($p->stock_quantity ?? 0),
                'photo_url' => $p->photo ? asset('storage/' . $p->photo) : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $products
        ], 200);
    }

    /**
     * ទទួលទិន្នន័យពី Flutter ដើម្បីកាត់ស្តុក និងកត់ត្រាការលក់
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'payment_type_id' => 'required',
            'items'           => 'required|array|min:1',
            'items.*.id'      => 'required',
            'items.*.qty'     => 'required|integer|min:1',
            'items.*.price'   => 'required|numeric',
        ]);

        try {
            $sale = DB::transaction(function () use ($request) {
                $totalAmount = 0;
                foreach ($request->items as $item) {
                    $totalAmount += $item['qty'] * $item['price'];
                }

                // ១. បង្កើតវិក្កយបត្រលក់ (Sale Record)
                $sale = Sale::create([
                    'user_id'         => $request->user()->user_id ?? $request->user()->id,
                    'payment_type_id' => $request->payment_type_id,
                    'total_amount'    => $totalAmount,
                    'sale_date'       => now(),
                ]);

                // ២. បញ្ចូលមុខទំនិញ និងកាត់ស្តុក
                foreach ($request->items as $item) {
                    SaleDetail::create([
                        'sale_id'    => $sale->sale_id ?? $sale->id,
                        'product_id' => $item['id'],
                        'quantity'   => $item['qty'],
                        'unit_price' => $item['price'],
                        'subtotal'   => $item['qty'] * $item['price'],
                    ]);

                    // កាត់ចំនួនស្តុកចេញពីតារាង products
                    $product = Product::where('product_id', $item['id'])->first();
                    if ($product) {
                        $product->decrement('stock_quantity', $item['qty']);
                    }
                }

                return $sale;
            });

            return response()->json([
                'success' => true,
                'message' => 'ការទូទាត់ប្រាក់ជោគជ័យ!',
                'sale_id' => $sale->sale_id ?? $sale->id,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'ការលក់បរាជ័យ៖ ' . $e->getMessage()
            ], 500);
        }
    }
}