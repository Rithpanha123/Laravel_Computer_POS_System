<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Add this line to register users.index, users.create, users.store, etc.

    Route::middleware('auth')->group(function () {
    Route::resource('users', UserController::class);
});

// Product Routes
Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
});

// Sale Routes
Route::middleware('auth')->group(function () {
    // POS Terminal Routes
    Route::get('/pos', [SaleController::class, 'posIndex'])->name('pos.index');
    Route::post('/pos/checkout', [SaleController::class, 'store'])->name('pos.store');

    // Sales Routes
    Route::resource('sales', SaleController::class)->only(['index', 'show']);
});
Route::middleware('auth')->group(function () {
    // ត្រូវប្រាកដថាប្រើ DashboardController ត្រង់នេះ
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index']);
});

// Purchase Routes
Route::middleware('auth')->group(function () {
    Route::resource('purchases', PurchaseController::class);
});
Route::middleware('auth')->group(function () {
    Route::get('/purchases/{purchase}/pdf', [PurchaseController::class, 'exportPdf'])->name('purchases.pdf');
    Route::get('/purchases/{purchase}/excel', [PurchaseController::class, 'exportExcel'])->name('purchases.excel');
    Route::get('/purchases-export-all-excel', [PurchaseController::class, 'exportExcel'])->name('purchases.export.all');
});


// Repair Routes
Route::middleware('auth')->group(function () {
    Route::resource('repairs', RepairController::class);
});

// Staff Routes
Route::middleware('auth')->group(function () {
    Route::resource('staff', StaffController::class);
});

// Expense Routes
Route::middleware('auth')->group(function () {
    Route::resource('expenses', ExpenseController::class)->except(['create', 'show', 'edit']);
});

// Report Routes
Route::middleware('auth')->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

//Customer
Route::middleware('auth')->group(function () {
    Route::resource('customers', CustomerController::class);
});

//Supplier
Route::middleware('auth')->group(function () {
    Route::resource('suppliers', SupplierController::class);
});

});