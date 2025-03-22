<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountManagerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login'); // Change 'welcome' to 'auth.login'
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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

require __DIR__ . '/auth.php';
