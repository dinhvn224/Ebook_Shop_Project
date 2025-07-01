<?php

use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\BookController as AdminBookController;

use Illuminate\Support\Facades\Route;

// Client Controllers
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController;
use App\Http\Controllers\Client\CategoryController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\ReviewController;
use App\Http\Controllers\Client\UserProfileController;


// Other Controllers
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\SearchController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Admin\VoucherProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CounterSaleController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Client\BookController;

// Admin Controllers






/*
|--------------------------------------------------------------------------
| CLIENT ROUTES (Các route cho người dùng)
|--------------------------------------------------------------------------
*/

// 🌐 Các trang công khai, không cần đăng nhập

// ✍️ Authors

// 🔐 Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
});

Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
Route::get('/author/{id}', [AuthorController::class, 'show'])->name('author.show');




// 🌐 Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{id}', [CategoryController::class, 'show'])->name('category.show');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/books/{id}', [ProductController::class, 'show'])->name('books.show');
Route::get('/search', [SearchController::class, 'index'])->name('search');



// Reviews
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');



// 👤 Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile']);
});

// 🛒 Cart & User Area (User only)
Route::middleware(['auth', 'role:user'])->group(function () {
    // Cart
    Route::prefix('cart')->name('cart.')->controller(CartController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/add', 'addToCart')->name('add');
        Route::post('/update/{id}', 'updateQuantity')->name('update');
        Route::post('/remove/{id}', 'removeFromCart')->name('remove');
        Route::post('/clear', 'clearCart')->name('clear');
    });

    // User Profile (chi tiết)
    Route::prefix('profile')->name('profile.')->controller(UserProfileController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
    });
});

Route::middleware(['auth', 'role:admin'])->get('/admin', fn() => view('admin.dashboard'))
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



Route::prefix('admin')
    ->name('admin.') // 💥 THÊM DÒNG NÀY
    ->middleware(['auth', 'role:admin']) // 👉 Thêm middleware nếu cần bảo vệ
    ->group(function () {

        // 📚 Quản lý sách
        Route::resource('books', AdminBookController::class);

        // ➕ Chi tiết sách (BookDetail)
        Route::post('books/{book}/details', [AdminBookController::class, 'addDetail'])->name('books.details.add');
        Route::put('books/{book}/details/{detail}', [AdminBookController::class, 'updateDetail'])->name('books.details.update');
        Route::delete('books/{book}/details/{detail}', [AdminBookController::class, 'deleteDetail'])->name('books.details.delete');
    });

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.') // 👉 Đặt tên tiền tố 'admin.'
    ->group(function () {
        // 🧾 Orders
        Route::resource('orders', OrderController::class)
            ->only(['index', 'show', 'update', 'destroy']);
    });


Route::prefix('cart')->name('cart.')->middleware('auth')->group(function() {
    // Hiển thị giỏ hàng
    Route::get('/', [CartController::class, 'index'])->name('index');
    
    // Thêm sản phẩm vào giỏ hàng
    Route::post('add', [CartController::class, 'addToCart'])->name('add');
    
    // Cập nhật số lượng sản phẩm
    Route::get('update/{id}', [CartController::class, 'updateQuantity'])->name('update');
    
    // Xóa một sản phẩm khỏi giỏ
    Route::delete('remove/{id}', [CartController::class, 'removeFromCart'])->name('remove');
    
    // Xóa toàn bộ giỏ hàng
    Route::post('clear', [CartController::class, 'clearCart'])->name('clear');
});

Route::get('book/{id}', [BookController::class, 'show'])->name('book.detail');
