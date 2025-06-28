<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BookController;

use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\ReviewController;
Route::prefix('admin')->name('admin.')->group(function() {
   Route::resource('publishers', PublisherController::class)->except(['show']);
    Route::post('publishers/{id}/restore', [PublisherController::class, 'restore'])->name('publishers.restore');
      Route::resource('categories', CategoryController::class);
    Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::resource('users', UserController::class)->except(['show']);

    Route::resource('authors', AuthorController::class)->except(['show']);
    Route::post('authors/{id}/restore', [AuthorController::class, 'restore'])->name('authors.restore');
    Route::resource('books', BookController::class)->except(['show']);
    Route::post('books/{book}/details', [BookController::class, 'addDetail'])->name('books.details.add');
    Route::put('books/{book}/details/{detail}', [BookController::class, 'updateDetail'])->name('books.details.update');
    Route::delete('books/{book}/details/{detail}', [BookController::class, 'deleteDetail'])->name('books.details.delete');
    Route::resource('reviews', ReviewController::class)->except(['show']);
    Route::patch('reviews/{id}/status', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
});

// Trang chủ cho tất cả mọi người
Route::get('/', function () {
    return view('client.home');
})->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Yêu cầu đăng nhập
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/home', function () {
        return view('client.home');
    })->name('home.user');
    Route::get('/reviews/create/{bookDetailId}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});
Route::middleware(['auth', 'role:admin'])->get('/admin', function () {
    return view('admin.dashboard');

})->name('admin.dashboard');

