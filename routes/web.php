<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CounterSaleController;


Route::prefix('admin')->group(function () {
    Route::resource('orders', OrderController::class)
        ->only(['index', 'show', 'update', 'destroy']);
});



Route::prefix('admin/counter-sale')->name('counter.')->group(function () {
    Route::get('/', [CounterSaleController::class, 'index'])->name('index');
    Route::post('/create', [CounterSaleController::class, 'createOrder'])->name('createOrder');
    Route::get('/{order}', [CounterSaleController::class, 'show'])->name('show');
    Route::put('/update-status/{order}', [CounterSaleController::class, 'updateStatus'])->name('updateStatus');

    Route::post('/add-item', [CounterSaleController::class, 'addItem'])->name('addItem');
    Route::put('/update-item/{item}', [CounterSaleController::class, 'updateItem'])->name('updateItem');
    Route::delete('/delete-item/{item}', [CounterSaleController::class, 'deleteItem'])->name('deleteItem');

    Route::post('/checkout/{order}', [CounterSaleController::class, 'checkout'])->name('checkout');
    Route::get('/receipt/{order}', [CounterSaleController::class, 'receipt'])->name('receipt');
    Route::get('/pdf/{order}', [CounterSaleController::class, 'exportPdf'])->name('pdf');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('vouchers', \App\Http\Controllers\Admin\VoucherController::class);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('voucher-products', [\App\Http\Controllers\Admin\VoucherProductController::class, 'index'])->name('voucher-products');
    Route::post('voucher-products/attach', [\App\Http\Controllers\Admin\VoucherProductController::class, 'attach'])->name('voucher-products.attach');
    Route::post('voucher-products/detach', [\App\Http\Controllers\Admin\VoucherProductController::class, 'detach'])->name('voucher-products.detach');
});
