<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        // Search Filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('cate_name', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        // Per Page Options (Default: 10)
        $perPage = in_array((int)$request->input('per_page'), [5, 10, 25, 100]) 
                    ? (int)$request->input('per_page') 
                    : 10;

        $totalCategories = Category::count();
        $categories = $query->orderBy('cate_id', 'desc')
                            ->paginate($perPage)
                            ->withQueryString();

        return view('categories.index', compact('categories', 'totalCategories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cate_name'   => 'required|string|max:150|unique:categories,cate_name',
            'description' => 'nullable|string|max:500',
            'is_active'   => 'nullable|boolean',
        ], [
            'cate_name.required' => 'សូមបញ្ចូលឈ្មោះប្រភេទផលិតផល!',
            'cate_name.unique'   => 'ឈ្មោះប្រភេទផលិតផលនេះមានរួចហើយ!',
        ]);

        Category::create([
            'cate_name'   => $validated['cate_name'],
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('categories.index')->with('success', 'បានបង្កើតប្រភេទផលិតផលថ្មីជោគជ័យ!');
    }

    public function edit($id)
    {
        $category = Category::where('cate_id', $id)->firstOrFail();
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::where('cate_id', $id)->firstOrFail();

        $validated = $request->validate([
            'cate_name'   => 'required|string|max:150|unique:categories,cate_name,' . $id . ',cate_id',
            'description' => 'nullable|string|max:500',
            'is_active'   => 'nullable|boolean',
        ], [
            'cate_name.required' => 'សូមបញ្ចូលឈ្មោះប្រភេទផលិតផល!',
            'cate_name.unique'   => 'ឈ្មោះប្រភេទផលិតផលនេះមានរួចហើយ!',
        ]);

        $category->update([
            'cate_name'   => $validated['cate_name'],
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('categories.index')->with('success', 'បានកែប្រែទិន្នន័យជោគជ័យ!');
    }

    public function destroy($id)
    {
        $category = Category::where('cate_id', $id)->firstOrFail();

        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'មិនអាចលុបបានទេ! មានផលិតផលកំពុងប្រើប្រាស់ប្រភេទនេះ។');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'បានលុបប្រភេទផលិតផលជោគជ័យ!');
    }
}