<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('user');

        // Search តាម description ឬ category
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter តាម Category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter តាមចន្លោះកាលបរិច្ឆេទ
        if ($request->filled('from_date')) {
            $query->whereDate('expense_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('expense_date', '<=', $request->to_date);
        }

        // ទទួលយកតម្លៃ per_page (5, 10, 25, 100) - Default: 10
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [5, 10, 25, 100])) {
            $perPage = 10;
        }

        $expenses = $query->latest('expense_date')->paginate($perPage)->withQueryString();

        // ស្ថិតិសង្ខេប
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $todayExpense = (float) Expense::whereDate('expense_date', $today)->sum('amount');
        $monthExpense = (float) Expense::whereDate('expense_date', '>=', $thisMonth)->sum('amount');
        $totalExpense = (float) Expense::sum('amount');

        // បញ្ជី Categories គំរូ
        $categories = [
            'ថ្លៃជួលទីតាំង (Rent)',
            'ទឹក ភ្លើង អ៊ីនធឺណិត (Utilities)',
            'ប្រាក់ខែបុគ្គលិក (Salaries)',
            'ថ្លៃដឹកជញ្ជូន (Delivery/Freight)',
            'ការផ្សព្វផ្សាយ (Marketing/Ads)',
            'សម្ភារៈការិយាល័យ (Office Supplies)',
            'ជួសជុល & ថែទាំ (Maintenance)',
            'ចំណាយផ្សេងៗ (Other)',
        ];

        return view('expenses.index', compact(
            'expenses',
            'todayExpense',
            'monthExpense',
            'totalExpense',
            'categories'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category'     => 'required|string|max:100',
            'amount'       => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'description'  => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::user()->user_id ?? 1;
        $validated['created_at'] = now();

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'ការចំណាយត្រូវបានកត់ត្រាដោយជោគជ័យ!');
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category'     => 'required|string|max:100',
            'amount'       => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'description'  => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'ការចំណាយត្រូវបានកែប្រែជោគជ័យ!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'ទិន្នន័យចំណាយត្រូវបានលុប!');
    }
}