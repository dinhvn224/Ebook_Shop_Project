<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BookController;

// Root route - redirect based on authentication and role
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
})->name('root');

Route::prefix('admin')->name('admin.')->group(function() {
    Route::resource('authors', AuthorController::class)->except(['show']);
    Route::post('authors/{id}/restore', [AuthorController::class, 'restore'])->name('authors.restore');
    Route::resource('books', BookController::class)->except(['show']);
    Route::post('books/{book}/details', [BookController::class, 'addDetail'])->name('books.details.add');
    Route::put('books/{book}/details/{detail}', [BookController::class, 'updateDetail'])->name('books.details.update');
    Route::delete('books/{book}/details/{detail}', [BookController::class, 'deleteDetail'])->name('books.details.delete');
});

// Client routes
Route::middleware(['auth'])->group(function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

// API routes (không cần auth)
Route::get('/api/products', [HomeController::class, 'getProductsData'])->name('api.products');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->get('/user', function () {
    return view('client.home');
})->name('home.user');

Route::middleware(['auth', 'role:admin'])->get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// Route động cho trang chi tiết sản phẩm (không cần auth)
Route::get('/product/{id}', function ($id) {
    return view('client.product_detail', ['id' => $id]);
});
