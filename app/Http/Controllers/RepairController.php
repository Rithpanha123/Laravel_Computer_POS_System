<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Repair;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RepairController extends Controller
{
    public function index(Request $request)
    {
        $query = Repair::with(['customer', 'technician']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('repair_no', 'like', "%{$search}%")
                  ->orWhere('device_name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('customer_name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $repairs = $query->latest('repair_id')->paginate(10)->withQueryString();

        // ស្ថិតិការងារជួសជុល
        $pendingCount = Repair::whereIn('status', ['PENDING', 'DIAGNOSING', 'IN_PROGRESS'])->count();
        $completedCount = Repair::where('status', 'COMPLETED')->count();
        $deliveredCount = Repair::where('status', 'DELIVERED')->count();

        return view('repairs.index', compact('repairs', 'pendingCount', 'completedCount', 'deliveredCount'));
    }

    public function create()
    {
        $customers = Customer::all();
        $technicians = Staff::all();

        return view('repairs.create', compact('customers', 'technicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'         => 'required|exists:customers,customer_id',
            'technician_id'       => 'nullable|exists:staff,staff_id',
            'device_name'         => 'required|string|max:255',
            'serial_number'       => 'nullable|string|max:100',
            'problem_description' => 'required|string',
            'diagnosis'           => 'nullable|string',
            'estimated_cost'      => 'nullable|numeric|min:0',
            'received_at'         => 'required|date',
            'notes'               => 'nullable|string',
        ]);

        $validated['repair_no'] = 'REP-' . strtoupper(Str::random(8));
        $validated['status'] = 'PENDING';
        $validated['estimated_cost'] = $validated['estimated_cost'] ?? 0;

        $repair = Repair::create($validated);

        return redirect()->route('repairs.index')->with('success', "ប័ណ្ណទទួលជួសជុលត្រូវបានបង្កើតជោគជ័យ! លេខកូដ #{$repair->repair_no}");
    }

    public function show(Repair $repair)
    {
        $repair->load(['customer', 'technician']);
        return view('repairs.show', compact('repair'));
    }

    public function edit(Repair $repair)
    {
        $customers = Customer::all();
        $technicians = Staff::all();

        return view('repairs.edit', compact('repair', 'customers', 'technicians'));
    }

    public function update(Request $request, Repair $repair)
    {
        $validated = $request->validate([
            'customer_id'         => 'required|exists:customers,customer_id',
            'technician_id'       => 'nullable|exists:staff,staff_id',
            'device_name'         => 'required|string|max:255',
            'serial_number'       => 'nullable|string|max:100',
            'problem_description' => 'required|string',
            'diagnosis'           => 'nullable|string',
            'estimated_cost'      => 'nullable|numeric|min:0',
            'final_cost'          => 'nullable|numeric|min:0',
            'status'              => 'required|string',
            'completed_at'        => 'nullable|date',
            'notes'               => 'nullable|string',
        ]);

        if ($validated['status'] === 'COMPLETED' && empty($validated['completed_at'])) {
            $validated['completed_at'] = now();
        }

        $repair->update($validated);

        return redirect()->route('repairs.index')->with('success', 'ព័ត៌មានជួសជុលត្រូវបានកែប្រែដោយជោគជ័យ!');
    }
}