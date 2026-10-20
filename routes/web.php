<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ActivityLogController;

// Redirect halaman utama ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route yang memerlukan autentikasi (Login)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Barang (Item)
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    
    // Route Khusus Barcode / QR Code Item (Diperbarui namanya agar sesuai dengan view)
    Route::get('/items/{item}/barcode-options', [ItemController::class, 'barcodeOptions'])->name('items.barcode.options');
    Route::get('/items/{item}/print-barcode', [ItemController::class, 'printBarcode'])->name('items.barcode.print');

    // Manajemen Kategori
    Route::resource('categories', CategoryController::class);

    // Peminjaman oleh Siswa (User)
    Route::get('/loans', [LoanController::class, 'myLoans'])->name('loans.my');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');

    // Manajemen Peminjaman & Persetujuan (Admin)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/loans', [LoanController::class, 'adminIndex'])->name('loans.index');
        Route::patch('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
        Route::patch('/loans/{loan}/return', [LoanController::class, 'processReturn'])->name('loans.return');
        Route::delete('/loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');
        Route::get('/loans-pdf', [LoanController::class, 'exportPdf'])->name('loans.export_pdf');
    });

    // Activity Log (Riwayat Aktivitas Sistem)
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

});

// Route bawaan autentikasi Laravel Breeze/Jetstream
require __DIR__.'/auth.php';