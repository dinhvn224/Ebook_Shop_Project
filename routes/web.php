<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PublisherController;
Route::prefix('admin')->name('admin.')->group(function() {
   Route::resource('publishers', PublisherController::class)->except(['show']);
    Route::post('publishers/{id}/restore', [PublisherController::class, 'restore'])->name('publishers.restore');
      Route::resource('categories', CategoryController::class);
    Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::resource('users', UserController::class)->except(['show']);

    Route::resource('authors', AuthorController::class)->except(['show']);
    Route::post('authors/{id}/restore', [AuthorController::class, 'restore'])->name('authors.restore');
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
Route::middleware(['auth', 'role:user'])->get('/home', function () {
    return view('client.home');
})->name('home.user');


Route::middleware(['auth', 'role:admin'])->get('/admin', function () {
    return view('admin.dashboard');

})->name('admin.dashboard');

