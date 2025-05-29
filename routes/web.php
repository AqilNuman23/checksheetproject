<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ChecksheetController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
    Route::post('/checksheets/import', [ChecksheetController::class, 'import'])->name('checksheet.import');
    Route::get('/checksheets/{id}', [ChecksheetController::class, 'show'])->name('checksheet.show');
    Route::get('/checksheets/{id}/edit', [ChecksheetController::class, 'edit'])->name('checksheet.edit');
    Route::post('/checksheets/update/{id}', [ChecksheetController::class, 'update'])->name('checksheet.update');
    Route::delete('/checksheets/{id}', [ChecksheetController::class, 'delete'])->name('checksheet.delete');
    Route::get('/checksheets/import-data', action: [ChecksheetController::class, 'getImportData'])->name('checksheet.getImportData');
});

require __DIR__ . '/auth.php';
