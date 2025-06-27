<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BookController;

Route::prefix('admin')->name('admin.')->group(function() {
    Route::resource('authors', AuthorController::class)->except(['show']);
    Route::post('authors/{id}/restore', [AuthorController::class, 'restore'])->name('authors.restore');
    Route::resource('books', BookController::class)->except(['show']);
    Route::post('books/{book}/details', [BookController::class, 'addDetail'])->name('books.details.add');
    Route::put('books/{book}/details/{detail}', [BookController::class, 'updateDetail'])->name('books.details.update');
    Route::delete('books/{book}/details/{detail}', [BookController::class, 'deleteDetail'])->name('books.details.delete');
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:user'])->get('/home', function () {
    return view('client.home');
})->name('home');

Route::middleware(['auth', 'role:admin'])->get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');
