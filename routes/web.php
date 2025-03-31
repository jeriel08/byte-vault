<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountManagerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierOrderController;
use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\ReturnToSupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Account Manager
Route::middleware('auth')->group(function () {
    Route::get('/account-manager', [AccountManagerController::class, 'index'])->name('account.manager');
    Route::get('/account-manager/add', [AccountManagerController::class, 'add'])->name('account.add');
    Route::post('/account-manager/store', [AccountManagerController::class, 'store'])->name('account.store');
    Route::get('/account-manager/edit/{employeeID}', [AccountManagerController::class, 'edit'])->name('account.edit');
    Route::patch('/account-manager/update/{employeeID}', [AccountManagerController::class, 'update'])->name('account.update');
});

// Suppliers
Route::middleware('auth')->group(function () {
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/add', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers/store', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/edit/{supplier}', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::patch('/suppliers/update/{supplierID}', [SupplierController::class, 'update'])->name('suppliers.update');
});

// Brands
Route::middleware('auth')->group(function () {
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::get('/brands/{brandID}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('/brands/{brandID}', [BrandController::class, 'update'])->name('brands.update');
});

// Categories
Route::middleware('auth')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{categoryID}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{categoryID}', [CategoryController::class, 'update'])->name('categories.update');
});

// Products
Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{productID}', [ProductController::class, 'update'])->name('products.update');
});

// Supplier Orders
Route::middleware('auth')->group(function () {
    Route::get('/supplier-orders', [SupplierOrderController::class, 'index'])->name('supplier_orders.index');
    Route::get('/supplier-orders/create', [SupplierOrderController::class, 'create'])->name('supplier_orders.create');
    Route::post('/supplier-orders', [SupplierOrderController::class, 'store'])->name('supplier_orders.store');
    Route::get('/supplier-orders/{supplierOrder}/edit', [SupplierOrderController::class, 'edit'])->name('supplier_orders.edit');
    Route::put('/supplier-orders/{supplierOrderID}', [SupplierOrderController::class, 'update'])->name('supplier_orders.update');
    Route::get('/supplier-orders/{supplierOrder}', [SupplierOrderController::class, 'show'])->name('supplier_orders.show');
});

// Adjustments
Route::middleware('auth')->group(function () {
    Route::get('/adjustments', [AdjustmentController::class, 'index'])->name('adjustments.index');
    Route::get('/adjustments/create', [AdjustmentController::class, 'create'])->name('adjustments.create');
    Route::post('/adjustments', [AdjustmentController::class, 'store'])->name('adjustments.store');
    Route::get('/adjustments/{adjustment}/edit', [AdjustmentController::class, 'edit'])->name('adjustments.edit');
    Route::put('/adjustments/{adjustmentID}', [AdjustmentController::class, 'update'])->name('adjustments.update');
    Route::get('/adjustments/{adjustment}', [AdjustmentController::class, 'show'])->name('adjustments.show');
});

// Return To Supplier
Route::middleware('auth')->group(function () {
    Route::get('/supplier_returns', [ReturnToSupplierController::class, 'index'])->name('supplier_returns.index');
    Route::get('/supplier_returns/create', [ReturnToSupplierController::class, 'create'])->name('supplier_returns.create');
    Route::post('/supplier_returns', [ReturnToSupplierController::class, 'store'])->name('supplier_returns.store');
    Route::get('/supplier_returns/{returnSupplierID}', [ReturnToSupplierController::class, 'show'])->name('supplier_returns.show');
    Route::patch('/supplier_returns/{returnSupplierID}/complete', [ReturnToSupplierController::class, 'complete'])->name('supplier_returns.complete');
    Route::patch('/supplier_returns/{returnSupplierID}/reject', [ReturnToSupplierController::class, 'reject'])->name('supplier_returns.reject');
});

// Orders
Route::middleware('auth')->group(function () {
    Route::resource('orders', \App\Http\Controllers\OrderController::class);
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [\App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/edit', [\App\Http\Controllers\OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'destroy'])->name('orders.destroy');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

Route::middleware('auth')->group(function () {
    // Existing routes...
    Route::get('/audit', [App\Http\Controllers\AuditLogController::class, 'index'])->name('audit.index');
});

require __DIR__ . '/auth.php';
