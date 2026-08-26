<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::withCount(['sales', 'repairs']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 100])) {
            $perPage = 10;
        }

        $customers = $query->latest('customer_id')->paginate($perPage)->withQueryString();
        $totalCustomers = Customer::count();

        return view('customers.index', compact('customers', 'totalCustomers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'phone'         => 'required|string|max:20|unique:customers,phone',
            'email'         => 'nullable|email|max:100',
            'address'       => 'nullable|string|max:255',
        ]);

        $validated['customer_code'] = 'CUST-' . strtoupper(Str::random(6));
        $validated['created_at'] = now();

        $customer = Customer::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'អតិថិជនត្រូវបានចុះឈ្មោះជោគជ័យ!',
                'customer' => $customer
            ]);
        }

        return redirect()->route('customers.index')->with('success', "អតិថិជន {$customer->customer_name} (#{$customer->customer_code}) ត្រូវបានបញ្ចូលជោគជ័យ!");
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'phone'         => 'required|string|max:20|unique:customers,phone,' . $customer->customer_id . ',customer_id',
            'email'         => 'nullable|email|max:100',
            'address'       => 'nullable|string|max:255',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', "ព័ត៌មានអតិថិជន {$customer->customer_name} ត្រូវបានធ្វើបច្ចុប្បន្នភាពជោគជ័យ!");
    }

    public function destroy(Customer $customer)
    {
        if ($customer->sales()->exists() || $customer->repairs()->exists()) {
            return redirect()->route('customers.index')->with('error', 'មិនអាចលុបអតិថិជននេះបានទេ ដោយសារមានទិន្នន័យប្រវត្តិទិញទំនិញ ឬប័ណ្ណជួសជុលនៅក្នុងប្រព័ន្ធ!');
        }

        $customerName = $customer->customer_name;
        $customer->delete();

        return redirect()->route('customers.index')->with('success', "អតិថិជន {$customerName} ត្រូវបានលុបចេញពីប្រព័ន្ធដោយជោគជ័យ!");
    }
}