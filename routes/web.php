<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountManagerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login'); // Change 'welcome' to 'auth.login'
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/account-manager', [AccountManagerController::class, 'index'])->name('account.manager');
    Route::get('/account-manager/add', [AccountManagerController::class, 'add'])->name('account.add');
    Route::post('/account-manager/store', [AccountManagerController::class, 'store'])->name('account.store');
    Route::get('/account-manager/edit/{employeeID}', [AccountManagerController::class, 'edit'])->name('account.edit');
    Route::patch('/account-manager/update/{employeeID}', [AccountManagerController::class, 'update'])->name('account.update');
});

require __DIR__ . '/auth.php';
