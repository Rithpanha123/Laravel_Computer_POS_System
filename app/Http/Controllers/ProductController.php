<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        // Search តាមឈ្មោះ, SKU ឬ Barcode
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        // Filter តាម Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter តាម Brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter តាម Status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // ទទួលយកតម្លៃ per_page (5, 10, 25, 100) - Default: 10
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 100])) {
            $perPage = 10;
        }

        $products = $query->latest('product_id')->paginate($perPage)->withQueryString();
        $categories = Category::all();
        $brands = Brand::all();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();

        return view('products.create', compact('categories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku'            => 'nullable|string|max:100|unique:products,sku',
            'barcode'        => 'nullable|string|max:100|unique:products,barcode',
            'product_name'   => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,cate_id',
            'brand_id'       => 'nullable|exists:brands,brand_id',
            'cost_price'     => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level'  => 'nullable|integer|min:0',
            'description'    => 'nullable|string',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_serialized'  => 'nullable|boolean',
            'is_active'      => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('products', 'public');
        }

        $validated['reorder_level'] = $validated['reorder_level'] ?? 5;
        $validated['is_serialized'] = $request->boolean('is_serialized');
        $validated['is_active']     = $request->boolean('is_active', true);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'ទំនិញត្រូវបានបង្កើតដោយជោគជ័យ!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();

        return view('products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name'   => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,cate_id',
            'brand_id'       => 'nullable|exists:brands,brand_id',
            'cost_price'     => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level'  => 'nullable|integer|min:0',
            'sku'            => ['nullable', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($product->product_id, 'product_id')],
            'barcode'        => ['nullable', 'string', 'max:100', Rule::unique('products', 'barcode')->ignore($product->product_id, 'product_id')],
            'description'    => 'nullable|string',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_serialized'  => 'nullable|boolean',
            'is_active'      => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($product->photo && Storage::disk('public')->exists($product->photo)) {
                Storage::disk('public')->delete($product->photo);
            }
            $validated['photo'] = $request->file('photo')->store('products', 'public');
        }

        $validated['reorder_level'] = $validated['reorder_level'] ?? 5;
        $validated['is_serialized'] = $request->boolean('is_serialized');
        $validated['is_active']     = $request->boolean('is_active');

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'ទំនិញត្រូវបានកែប្រែដោយជោគជ័យ!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // ពិនិត្យការពារកុំឱ្យលុបទំនិញដែលមានជាប់វិក្កយបត្រលក់ ឬប័ណ្ណទិញចូល
        if (method_exists($product, 'saleItems') && $product->saleItems()->exists()) {
            return redirect()->route('products.index')->with('error', 'មិនអាចលុបទំនិញនេះបានទេ ដោយសារមានប្រវត្តិលក់ចេញក្នុងវិក្កយបត្រ!');
        }

        if (method_exists($product, 'purchaseItems') && $product->purchaseItems()->exists()) {
            return redirect()->route('products.index')->with('error', 'មិនអាចលុបទំនិញនេះបានទេ ដោយសារមានប្រវត្តិនាំចូលស្តុក (PO)!');
        }

        // 1. លុបរូបភាពពី Storage
        if ($product->photo && Storage::disk('public')->exists($product->photo)) {
            Storage::disk('public')->delete($product->photo);
        }

        // 2. លុបទិន្នន័យពី Database
        $product->delete();

        return redirect()->route('products.index')->with('success', 'ទំនិញត្រូវបានលុបចេញពីប្រព័ន្ធដោយជោគជ័យ!');
    }
}