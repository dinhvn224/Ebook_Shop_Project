<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Admin\{
    UserController,
    AuthorController,
    CategoryController,
    PublisherController,
    OrderController,
    CounterSaleController,
    DashboardController,
    VoucherController,
    VoucherProductController,
    ReviewController,
    BookController
};

//
// 🌐 PUBLIC CLIENT ROUTES
//
Route::get('/', fn() => view('client.home'))->name('home');

Route::middleware(['auth', 'role:user'])->get('/home', fn() => view('client.home'))
    ->name('home.user');
Route::get('/reviews/create/{bookDetailId}', [ReviewController::class, 'create'])->name('reviews.create');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
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

// ⚙️ ADMIN CORE ROUTES
//
Route::prefix('admin')
    ->as('admin.')
    ->middleware(['auth', 'role:ADMIN'])
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

        Route::resource('books', BookController::class)->except(['show']);

        // Reviews
        Route::resource('reviews', ReviewController::class)->except(['show']);
        Route::patch('reviews/{id}/status', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
            
        //
    });


Route::prefix('admin')
    ->as('admin.')
    ->middleware(['auth', 'role:ADMIN'])
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


// API routes (không cần auth)
Route::get('/api/products', [HomeController::class, 'getProductsData'])->name('api.products');

// Route động cho trang chi tiết sản phẩm (không cần auth)
Route::get('/product/{id}', function ($id) {
    return view('client.product_detail', ['id' => $id]);
});

