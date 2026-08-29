<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Repair;
use App\Models\Staff;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function index(Request $request)
    {
        $query = Repair::with(['customer', 'technician']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('repair_no', 'like', "%{$search}%")
                  ->orWhere('device_name', 'ilike', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('customer_name', 'ilike', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', strtoupper($request->status));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('received_at', '>=', $request->from_date);
        }

        $perPage = (int) $request->get('per_page', 5);
        if (!in_array($perPage, [5, 10, 25, 100])) {
            $perPage = 5;
        }

        $repairs = $query->orderBy('repair_id', 'desc')->paginate($perPage)->withQueryString();

        $pendingCount    = Repair::whereIn('status', ['PENDING', 'DIAGNOSING'])->count();
        $inProgressCount = Repair::where('status', 'IN_PROGRESS')->count();
        $completedCount  = Repair::where('status', 'COMPLETED')->count();
        $deliveredCount  = Repair::where('status', 'DELIVERED')->count();

        return view('repairs.index', compact('repairs', 'pendingCount', 'inProgressCount', 'completedCount', 'deliveredCount'));
    }

    public function create()
    {
        $customers   = Customer::all();
        $technicians = Staff::where('employment_status', 'ACTIVE')->orWhereNull('employment_status')->get();
        return view('repairs.create', compact('customers', 'technicians'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'         => 'required|exists:customers,customer_id',
            'device_name'         => 'required|string|max:255',
            'serial_number'       => 'nullable|string|max:100',
            'problem_description' => 'required|string',
            'diagnosis'           => 'nullable|string',
            'technician_id'       => 'nullable|exists:staff,staff_id',
            'estimated_cost'      => 'nullable|numeric|min:0',
            'deposit_amount'      => 'nullable|numeric|min:0',
            'notes'               => 'nullable|string',
        ]);

        $estimate = (float) ($request->estimated_cost ?? 0);
        $deposit  = (float) ($request->deposit_amount ?? 0);

        $validated['repair_no']       = 'REP-' . date('ymd') . '-' . strtoupper(substr(uniqid(), -4));
        $validated['technician_id']   = $request->filled('technician_id') ? $request->technician_id : null;
        $validated['status']          = 'PENDING';
        $validated['final_cost']      = 0;
        $validated['due_amount']      = max(0, $estimate - $deposit);
        $validated['payment_status']  = ($deposit >= $estimate && $estimate > 0) ? 'PAID' : ($deposit > 0 ? 'PARTIAL' : 'UNPAID');
        $validated['received_at']     = now();
        $validated['created_at']      = now();

        $repair = Repair::create($validated);

        return redirect()->route('repairs.show', $repair->repair_id)
                         ->with('success', "ប័ណ្ណទទួលជួសជុលលេខ #{$repair->repair_no} ត្រូវបានបង្កើតដោយជោគជ័យ!");
    }

    public function show(Repair $repair)
    {
        $repair->load(['customer', 'technician']);
        return view('repairs.show', compact('repair'));
    }

    public function edit(Repair $repair)
    {
        $repair->load(['customer', 'technician']);
        $customers   = Customer::all();
        $technicians = Staff::where('employment_status', 'ACTIVE')->orWhereNull('employment_status')->get();

        return view('repairs.edit', compact('repair', 'customers', 'technicians'));
    }

    public function update(Request $request, Repair $repair)
    {
        $validated = $request->validate([
            'customer_id'         => 'required|exists:customers,customer_id',
            'device_name'         => 'required|string|max:255',
            'serial_number'       => 'nullable|string|max:100',
            'problem_description' => 'required|string',
            'diagnosis'           => 'nullable|string',
            'technician_id'       => 'nullable|exists:staff,staff_id',
            'status'              => 'required|string',
            'estimated_cost'      => 'nullable|numeric|min:0',
            'final_cost'          => 'nullable|numeric|min:0',
            'deposit_amount'      => 'nullable|numeric|min:0',
            'notes'               => 'nullable|string',
        ]);

        $final   = (float) ($validated['final_cost'] ?? $repair->final_cost ?? $validated['estimated_cost'] ?? 0);
        $deposit = (float) ($validated['deposit_amount'] ?? $repair->deposit_amount ?? 0);
        $due     = max(0, $final - $deposit);

        $paymentStatus = 'UNPAID';
        if ($deposit >= $final && $final > 0) {
            $paymentStatus = 'PAID';
        } elseif ($deposit > 0) {
            $paymentStatus = 'PARTIAL';
        }

        $validated['technician_id'] = $request->filled('technician_id') ? $request->technician_id : null;
        $validated['final_cost']     = $final;
        $validated['due_amount']     = $due;
        $validated['payment_status'] = $paymentStatus;

        if ($validated['status'] === 'COMPLETED' && !$repair->completed_at) {
            $validated['completed_at'] = now();
        }
        if ($validated['status'] === 'DELIVERED' && !$repair->delivered_at) {
            $validated['delivered_at'] = now();
        }

        $repair->update($validated);

        return redirect()->route('repairs.show', $repair->repair_id)
                         ->with('success', 'ព័ត៌មានជួសជុល និងជាងទទួលបន្ទុកត្រូវបានធ្វើបច្ចុប្បន្នភាពជោគជ័យ!');
    }

    public function destroy(Repair $repair)
    {
        $repair->delete();
        return redirect()->route('repairs.index')->with('success', 'ប័ណ្ណជួសជុលត្រូវបានលុបចេញពីប្រព័ន្ធ!');
    }
}