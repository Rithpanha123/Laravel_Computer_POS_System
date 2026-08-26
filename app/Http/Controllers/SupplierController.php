<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('purchases');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('supplier_name', 'like', "%{$search}%")
                  ->orWhere('supplier_code', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 100])) {
            $perPage = 10;
        }

        $suppliers = $query->latest('supplier_id')->paginate($perPage)->withQueryString();
        $totalSuppliers = Supplier::count();

        return view('suppliers.index', compact('suppliers', 'totalSuppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name'  => 'required|string|max:150',
            'contact_person' => 'nullable|string|max:100',
            'phone'          => 'required|string|max:30|unique:suppliers,phone',
            'email'          => 'nullable|email|max:100',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'address'        => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('suppliers', 'public');
        }

        $validated['supplier_code'] = 'SUP-' . strtoupper(Str::random(6));
        $validated['created_at'] = now();

        $supplier = Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', "អ្នកផ្គត់ផ្គង់ {$supplier->supplier_name} (#{$supplier->supplier_code}) ត្រូវបានបញ្ចូលជោគជ័យ!");
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'supplier_name'  => 'required|string|max:150',
            'contact_person' => 'nullable|string|max:100',
            'phone'          => 'required|string|max:30|unique:suppliers,phone,' . $supplier->supplier_id . ',supplier_id',
            'email'          => 'nullable|email|max:100',
            'photo'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'address'        => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            if ($supplier->photo && Storage::disk('public')->exists($supplier->photo)) {
                Storage::disk('public')->delete($supplier->photo);
            }
            $validated['photo'] = $request->file('photo')->store('suppliers', 'public');
        }

        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', "ព័ត៌មានអ្នកផ្គត់ផ្គង់ {$supplier->supplier_name} ត្រូវបានធ្វើបច្ចុប្បន្នភាពជោគជ័យ!");
    }

    public function destroy(Supplier $supplier)
    {
        // ការពារកុំឱ្យលុបអ្នកផ្គត់ផ្គង់ដែលមានជាប់ប័ណ្ណទិញទំនិញចូលស្តុក
        if ($supplier->purchases()->exists()) {
            return redirect()->route('suppliers.index')->with('error', 'មិនអាចលុបអ្នកផ្គត់ផ្គង់នេះបានទេ ដោយសារមានប្រវត្តិបញ្ជាទិញចូលស្តុក (PO)!');
        }

        if ($supplier->photo && Storage::disk('public')->exists($supplier->photo)) {
            Storage::disk('public')->delete($supplier->photo);
        }

        $supplierName = $supplier->supplier_name;
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', "អ្នកផ្គត់ផ្គង់ {$supplierName} ត្រូវបានលុបចេញពីប្រព័ន្ធដោយជោគជ័យ!");
    }
}