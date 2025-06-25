<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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
