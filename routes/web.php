<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ChecksheetController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::get('/checksheets', [ChecksheetController::class, 'index'])->name('checksheet.index');
    Route::get('/companies', [CompanyController::class, 'index'])->name('company.index');
    Route::get('/users', [UserController::class, 'index'])->name('user.index');

    Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/products', [ProductController::class, 'store'])->name('product.store');

    Route::get('/checksheets/create', [ChecksheetController::class, 'create'])->name('checksheet.create');
    Route::post('/checksheets', [ChecksheetController::class, 'store'])->name('checksheet.store');
    Route::get('/checsheets/export', [ChecksheetController::class, 'export'])->name('checksheet.export');
    Route::get('/checksheets/{id}', [ChecksheetController::class, 'show'])->name('checksheet.show');
    Route::get('/checksheets/{id}/edit', [ChecksheetController::class, 'edit'])->name('checksheet.edit');
    Route::delete('/checksheets/{id}', [ChecksheetController::class, 'destroy'])->name('checksheet.destroy');
});

require __DIR__.'/auth.php';
