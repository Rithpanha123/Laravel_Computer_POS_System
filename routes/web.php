<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;

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

});