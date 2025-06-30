<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\{
    AdminReviewController,
    UserController,
    AuthorController,
    BookController,
    CategoryController,
    PublisherController,
    OrderController,
    CounterSaleController,
    DashboardController,
    ImageController,
    VoucherController,
    VoucherProductController
};
use App\Http\Controllers\Client\BookController as ClientBookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CartController as ClientCartController;
use App\Http\Controllers\Client\ClientReviewController;
use App\Http\Controllers\Client\UserProfileController;

//
// 🌐 PUBLIC CLIENT ROUTES
//
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/books', [BookController::class, 'index'])->name('books.index');


Route::get('/book/{book}', [ClientBookController::class, 'show'])->name('book.detail');

Route::middleware(['auth', 'role:user'])->get('/home', fn() => view('client.home'))
    ->name('home.user');

// Các route giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

//
// 🔐 AUTH ROUTES
//
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

//
// 🛠 ADMIN DASHBOARD
//
Route::middleware(['auth', 'role:ADMIN'])->get('/admin', fn() => view('admin.dashboard'))
    ->name('admin.dashboard');

//
// ⚙️ ADMIN CORE ROUTES
//
Route::prefix('admin')
    ->as('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // 👤 Users
        Route::resource('users', UserController::class)->except(['show']);

        // ✍️ Authors
        Route::resource('authors', AuthorController::class)->except(['show']);
        Route::post('authors/{id}/restore', [AuthorController::class, 'restore'])
            ->name('authors.restore');

        // 🗂 Categories
        Route::resource('categories', CategoryController::class);
        Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])
            ->name('categories.restore');

        // 📚 Publishers
        Route::resource('publishers', PublisherController::class)->except(['show']);
        Route::post('publishers/{id}/restore', [PublisherController::class, 'restore'])
            ->name('publishers.restore');

        // 🧾 Orders
        Route::resource('orders', OrderController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        // 🎫 Vouchers
        Route::resource('vouchers', VoucherController::class);

        // 🏷 Voucher-Product Mapping
        Route::prefix('voucher-products')->as('voucher-products.')->group(function () {
            Route::get('/', [VoucherProductController::class, 'index'])->name('index');
            Route::post('/attach', [VoucherProductController::class, 'attach'])->name('attach');
            Route::post('/detach', [VoucherProductController::class, 'detach'])->name('detach');
        });

        Route::prefix('reviews')->as('reviews.')->group(function () {
            Route::get('/', [AdminReviewController::class, 'index'])->name('index');                      // Danh sách đánh giá
            Route::patch('/{review}/status', [AdminReviewController::class, 'updateStatus'])->name('updateStatus'); // Duyệt / ẩn / chờ
        });

        //
    });


Route::prefix('admin')
    ->as('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        // 💵 ROUTES cho quản lý đơn hàng tại quầy
        Route::prefix('counter-sale')
            ->as('counter.')
            ->controller(CounterSaleController::class)
            ->group(function () {

                // 📄 Trang danh sách đơn hàng
                Route::get('/', 'index')->name('index');

                // ➕ Tạo đơn mới
                Route::post('/create', 'createOrder')->name('createOrder');

                // 👁 Xem chi tiết đơn
                Route::get('/{order}', 'show')->name('show');

                // 🔄 Cập nhật trạng thái đơn hàng (PENDING, PAID, CANCELLED...)
                Route::put('/update-status/{order}', 'updateStatus')->name('updateStatus');

                // 🛒 Thêm sản phẩm vào đơn
                Route::post('/add-item', 'addItem')->name('addItem');

                // ✏️ Cập nhật số lượng sản phẩm
                Route::put('/update-item/{item}', 'updateItem')->name('updateItem');

                // 🗑 Xóa sản phẩm khỏi đơn
                Route::delete('/delete-item/{item}', 'deleteItem')->name('deleteItem');

                // 💳 Thanh toán đơn
                Route::post('/checkout/{order}', 'checkout')->name('checkout');

                // 🖨 In hóa đơn (HTML)
                Route::get('/receipt/{order}', 'receipt')->name('receipt');

                // ⬇️ Xuất hóa đơn PDF
                Route::get('/pdf/{order}', 'exportPdf')->name('pdf');
            });
    });

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::prefix('admin')->name('admin.')->group(function () {

    Route::resource('books', BookController::class)->except(['show']);
    Route::post('books/{book}/details', [BookController::class, 'addDetail'])->name('books.details.add');
    Route::put('books/{book}/details/{detail}', [BookController::class, 'updateDetail'])->name('books.details.update');
    Route::delete('books/{book}/details/{detail}', [BookController::class, 'deleteDetail'])->name('books.details.delete');
});

Route::middleware(['auth', 'role:user'])
    ->prefix('profile')
    ->name('profile.')
    ->group(function () {
        Route::get('/', [UserProfileController::class, 'index'])->name('index');      // profile.index
        Route::get('/edit', [UserProfileController::class, 'edit'])->name('edit');    // profile.edit
        Route::put('/update', [UserProfileController::class, 'update'])->name('update'); // profile.update
    });

Route::prefix('admin')
    ->as('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::resource('images', ImageController::class);
    });

Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::middleware(['auth', 'role:user'])->group(function () {
        Route::post('/', [ClientReviewController::class, 'store'])->name('store');         // Viết mới
        Route::put('/{review}', [ClientReviewController::class, 'update'])->name('update'); // Cập nhật trong 24h
    });
});
