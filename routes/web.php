<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;

// 1. Guest Routes (Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 2. Authenticated Routes
Route::middleware('auth')->group(function () {
    
    // Auth Actions
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // POS Terminal & Sales Management
    Route::get('/pos', [SaleController::class, 'posIndex'])->name('pos.index');
    Route::post('/pos/checkout', [SaleController::class, 'store'])->name('pos.store');
    
    // Sales History Routes (Index, Show, Destroy)
    Route::resource('sales', SaleController::class)->only(['index', 'show', 'destroy']);

    // Core POS Management Modules
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('users', UserController::class);

    // Purchases & Exports
    Route::resource('purchases', PurchaseController::class);
    Route::get('/purchases/{purchase}/pdf', [PurchaseController::class, 'exportPdf'])->name('purchases.pdf');
    Route::get('/purchases/{purchase}/excel', [PurchaseController::class, 'exportExcel'])->name('purchases.excel');
    Route::get('/purchases-export-all-excel', [PurchaseController::class, 'exportExcel'])->name('purchases.export.all');

    // Other Services
    Route::resource('repairs', RepairController::class);
    Route::resource('staff', StaffController::class);
    Route::resource('expenses', ExpenseController::class)->except(['create', 'show', 'edit']);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});