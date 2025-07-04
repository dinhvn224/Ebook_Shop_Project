<?php

use Illuminate\Support\Facades\Route;

// ========== Client Controllers ==========
use App\Http\Controllers\Client\{
    HomeController,
    ProductController,
    CategoryController,
    CartController,
    AuthController,
    ReviewController,
    UserProfileController,
    BookController,
    CheckoutController
};

// ========== Admin Controllers ==========
use App\Http\Controllers\Admin\{
    DashboardController,
    OrderController,
    PublisherController,
    BookController as AdminBookController,
    CounterSaleController,
    ImageController,
    VoucherController,
    VoucherProductController,
    UserController,
    AdminReviewController
};

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| 🌐 PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/books/{id}', [ProductController::class, 'show'])->name('books.show');
Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
Route::get('/author/{id}', [AuthorController::class, 'show'])->name('author.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');

/*
|--------------------------------------------------------------------------
| 🔐 AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

/*
|--------------------------------------------------------------------------
| 👤 USER ROUTES (requires login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Profile
    Route::prefix('profile')->name('profile.')->controller(UserProfileController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
    });

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Cart
    Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/add', 'addToCart')->name('add');
        Route::post('/update/{id}', 'updateQuantity')->name('update');
        Route::post('/remove/{id}', 'removeFromCart')->name('remove');
        Route::post('/clear', 'clearCart')->name('clear');
    });

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'showForm'])->name('checkout.form');
    Route::post('/checkout', [CheckoutController::class, 'processOrder'])->name('checkout.process');
});

/*
|--------------------------------------------------------------------------
| 🧾 BOOK DETAIL PAGE
|--------------------------------------------------------------------------
*/
Route::get('book/{id}', [BookController::class, 'show'])->name('book.detail');

/*
|--------------------------------------------------------------------------
| 🛠 ADMIN ROUTES (role:admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Resources
    Route::resource('users', UserController::class)->except('show');
    Route::resource('authors', AuthorController::class)->except('show');
    Route::post('authors/{id}/restore', [AuthorController::class, 'restore'])->name('authors.restore');

    Route::resource('categories', CategoryController::class);
    Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');

    Route::resource('publishers', PublisherController::class)->except('show');
    Route::post('publishers/{id}/restore', [PublisherController::class, 'restore'])->name('publishers.restore');

    Route::resource('vouchers', VoucherController::class);
    Route::resource('images', ImageController::class);

    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update', 'destroy']);

    // Voucher - Product Mapping
    Route::prefix('voucher-products')->as('voucher-products.')->group(function () {
        Route::get('/', [VoucherProductController::class, 'index'])->name('index');
        Route::post('/attach', [VoucherProductController::class, 'attach'])->name('attach');
        Route::post('/detach', [VoucherProductController::class, 'detach'])->name('detach');
    });

    // Reviews
    Route::prefix('reviews')->as('reviews.')->group(function () {
        Route::get('/', [AdminReviewController::class, 'index'])->name('index');
        Route::patch('/{review}/status', [AdminReviewController::class, 'updateStatus'])->name('updateStatus');
    });

    // Books & Book Details
    Route::resource('books', AdminBookController::class);
    Route::post('books/{book}/details', [AdminBookController::class, 'addDetail'])->name('books.details.add');
    Route::put('books/{book}/details/{detail}', [AdminBookController::class, 'updateDetail'])->name('books.details.update');
    Route::delete('books/{book}/details/{detail}', [AdminBookController::class, 'deleteDetail'])->name('books.details.delete');

    // Counter Sales
    Route::prefix('counter-sale')->as('counter.')->controller(CounterSaleController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/create', 'createOrder')->name('createOrder');
        Route::get('/{order}', 'show')->name('show');
        Route::put('/update-status/{order}', 'updateStatus')->name('updateStatus');
        Route::post('/add-item', 'addItem')->name('addItem');
        Route::put('/update-item/{item}', 'updateItem')->name('updateItem');
        Route::delete('/delete-item/{item}', 'deleteItem')->name('deleteItem');
        Route::post('/checkout/{order}', 'checkout')->name('checkout');
        Route::get('/receipt/{order}', 'receipt')->name('receipt');
        Route::get('/pdf/{order}', 'exportPdf')->name('pdf');
    });
});

Route::prefix('admin')
    ->as('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::patch('reviews/{id}/status', [AdminReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
        Route::post('reviews/{id}/reply', [AdminReviewController::class, 'reply'])->name('reviews.reply'); // Nếu có
        Route::delete('reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        // ✅ Thêm dòng này để sửa lỗi bạn gặp:
        Route::get('reviews/{id}', [AdminReviewController::class, 'show'])->name('reviews.show');
    });
