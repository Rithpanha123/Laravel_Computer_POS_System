<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('brand_name', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        // Per Page Options (Default: 10)
        $perPage = in_array((int)$request->input('per_page'), [5, 10, 25, 100]) 
                    ? (int)$request->input('per_page') 
                    : 10;

        $totalBrands = Brand::count();
        $brands = $query->orderBy('brand_id', 'desc')
                        ->paginate($perPage)
                        ->withQueryString();

        return view('brands.index', compact('brands', 'totalBrands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_name'  => 'required|string|max:150|unique:brands,brand_name',
            'description' => 'nullable|string|max:500',
            'is_active'   => 'nullable|boolean',
        ], [
            'brand_name.required' => 'សូមបញ្ចូលឈ្មោះម៉ាកយីហោ (Brand)!',
            'brand_name.unique'   => 'ឈ្មោះម៉ាកយីហោនេះមានរួចហើយ!',
        ]);

        Brand::create([
            'brand_name'  => $validated['brand_name'],
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('brands.index')->with('success', 'បានបង្កើតម៉ាកយីហោថ្មីជោគជ័យ!');
    }

    public function edit($id)
    {
        $brand = Brand::where('brand_id', $id)->firstOrFail();
        return view('brands.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::where('brand_id', $id)->firstOrFail();

        $validated = $request->validate([
            'brand_name'  => 'required|string|max:150|unique:brands,brand_name,' . $id . ',brand_id',
            'description' => 'nullable|string|max:500',
            'is_active'   => 'nullable|boolean',
        ], [
            'brand_name.required' => 'សូមបញ្ចូលឈ្មោះម៉ាកយីហោ (Brand)!',
            'brand_name.unique'   => 'ឈ្មោះម៉ាកយីហោនេះមានរួចហើយ!',
        ]);

        $brand->update([
            'brand_name'  => $validated['brand_name'],
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('brands.index')->with('success', 'បានកែប្រែទិន្នន័យម៉ាកយីហោជោគជ័យ!');
    }

    public function destroy($id)
    {
        $brand = Brand::where('brand_id', $id)->firstOrFail();

        if ($brand->products()->count() > 0) {
            return redirect()->route('brands.index')->with('error', 'មិនអាចលុបបានទេ! មានផលិតផលកំពុងប្រើប្រាស់ម៉ាកយីហោនេះ។');
        }

        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'បានលុបម៉ាកយីហោជោគជ័យ!');
    }
}