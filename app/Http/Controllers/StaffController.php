<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    /**
     * បង្ហាញបញ្ជីបុគ្គលិកទាំងអស់
     */
    public function index(Request $request)
    {
        $query = Staff::with('position');

        // Search តាមឈ្មោះ, កូដ, លេខទូរស័ព្ទ ឬ អ៊ីមែល
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('staff_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter តាមស្ថានភាពការងារ
        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }

        $staffMembers = $query->latest('staff_id')->paginate(10)->withQueryString();
        
        $totalStaff = Staff::count();
        $activeStaff = Staff::where('employment_status', 'ACTIVE')->count();

        return view('staff.index', compact('staffMembers', 'totalStaff', 'activeStaff'));
    }

    /**
     * បង្ហាញ Form បង្កើតបុគ្គលិកថ្មី
     */
    public function create()
    {
        $positions = Position::all();
        return view('staff.create', compact('positions'));
    }

    /**
     * រក្សាទុកទិន្នន័យបុគ្គលិកថ្មីចូល Database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_code'        => 'required|string|max:50|unique:staff,staff_code',
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'phone'             => 'required|string|max:50',
            'email'             => 'nullable|email|max:100',
            'position_id'       => 'required|exists:positions,position_id',
            'gender_id'         => 'nullable|integer',
            'salary'            => 'nullable|numeric|min:0',
            'hire_date'         => 'nullable|date',
            'employment_status' => 'required|string|max:50',
            'address'           => 'nullable|string',
            'city'              => 'nullable|string|max:100',
            'province'          => 'nullable|string|max:100',
            'date_of_birth'     => 'nullable|date',
            'notes'             => 'nullable|string',
            'emergency_contact_name'     => 'nullable|string|max:100',
            'emergency_contact_phone'    => 'nullable|string|max:50',
            'emergency_contact_relation' => 'nullable|string|max:50',
            'photo'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['full_name'] = trim($validated['first_name'] . ' ' . $validated['last_name']);
        $validated['salary'] = $validated['salary'] ?? 0.00;

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('staff', 'public');
        }

        Staff::create($validated);

        return redirect()->route('staff.index')->with('success', 'បុគ្គលិកថ្មីត្រូវបានចុះឈ្មោះដោយជោគជ័យ!');
    }

    /**
     * បង្ហាញព័ត៌មានលម្អិតបុគ្គលិក
     */
    public function show(Staff $staff)
    {
        $staff->load('position');
        return view('staff.show', compact('staff'));
    }

    /**
     * បង្ហាញ Form កែប្រែព័ត៌មានបុគ្គលិក
     */
    public function edit(Staff $staff)
    {
        $positions = Position::all();
        return view('staff.edit', compact('staff', 'positions'));
    }

    /**
     * កែប្រែទិន្នន័យបុគ្គលិកក្នុង Database
     */
    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'staff_code'        => ['required', 'string', 'max:50', Rule::unique('staff', 'staff_code')->ignore($staff->staff_id, 'staff_id')],
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'phone'             => 'required|string|max:50',
            'email'             => 'nullable|email|max:100',
            'position_id'       => 'required|exists:positions,position_id',
            'gender_id'         => 'nullable|integer',
            'salary'            => 'nullable|numeric|min:0',
            'hire_date'         => 'nullable|date',
            'employment_status' => 'required|string|max:50',
            'address'           => 'nullable|string',
            'city'              => 'nullable|string|max:100',
            'province'          => 'nullable|string|max:100',
            'date_of_birth'     => 'nullable|date',
            'notes'             => 'nullable|string',
            'emergency_contact_name'     => 'nullable|string|max:100',
            'emergency_contact_phone'    => 'nullable|string|max:50',
            'emergency_contact_relation' => 'nullable|string|max:50',
            'photo'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['full_name'] = trim($validated['first_name'] . ' ' . $validated['last_name']);

        if ($request->hasFile('photo')) {
            if ($staff->photo && Storage::disk('public')->exists($staff->photo)) {
                Storage::disk('public')->delete($staff->photo);
            }
            $validated['photo'] = $request->file('photo')->store('staff', 'public');
        }

        $staff->update($validated);

        return redirect()->route('staff.index')->with('success', 'ព័ត៌មានបុគ្គលិកត្រូវបានកែប្រែដោយជោគជ័យ!');
    }

    /**
     * លុបបុគ្គលិកចេញពីប្រព័ន្ធ
     */
    public function destroy(Staff $staff)
    {
        if ($staff->photo && Storage::disk('public')->exists($staff->photo)) {
            Storage::disk('public')->delete($staff->photo);
        }
        
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'បុគ្គលិកត្រូវបានលុបចេញពីប្រព័ន្ធដោយជោគជ័យ!');
    }
}