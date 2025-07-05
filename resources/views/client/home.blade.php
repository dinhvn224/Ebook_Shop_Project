@extends('client.layouts.app')

@section('title', 'BookStore - Khám Phá Thế Giới Tri Thức')

@section('content')

    {{-- Trang chủ (active mặc định) --}}
    <div id="home" class="page active">
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <h1 class="display-4 mb-4 fw-bold">Khám Phá Thế Giới Tri Thức</h1>
                        <p class="lead mb-4">Hàng ngàn cuốn sách hay từ các tác giả nổi tiếng, từ văn học đến khoa học, từ
                            tiểu thuyết đến sách giáo dục. Đặc biệt có nhiều chương trình khuyến mãi hấp dẫn!</p>
                        <div class="d-flex gap-3">
                            <button class="btn btn-accent btn-lg" onclick="showPage('books')">
                                <i class="fas fa-shopping-bag me-2"></i>Mua Sách Ngay
                            </button>
                            <button class="btn btn-outline-light btn-lg" onclick="showPage('authors')">
                                <i class="fas fa-users me-2"></i>Khám Phá Tác Giả
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5" style="background-color: #f8f9fa;">
            <style>
                .product-card {
                    border: none;
                    perspective: 1000px;
                    transition: transform 0.3s ease;
                    border-radius: 6px;
                    overflow: hidden;
                }

                .product-card-inner {
                    transition: transform 0.5s;
                    transform-style: preserve-3d;
                }

                .product-card:hover .product-card-inner {
                    transform: rotateY(3deg) scale(1.02);
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                }

                .product-card img {
                    width: 100%;
                    height: 260px;
                    object-fit: contain;
                    border-radius: 8px;
                    background: #fff;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                    transition: transform 0.2s;
                    padding: 8px;
                }
                .product-card:hover img {
                    transform: scale(1.03);
                    box-shadow: 0 4px 16px rgba(0,0,0,0.10);
                }

                .discount-badge {
                    position: absolute;
                    bottom: 16px;
                    left: 16px;
                    top: auto;
                    right: auto;
                    transform: none;
                    background: linear-gradient(90deg, #ff416c 0%, #ff4b2b 100%);
                    color: #fff;
                    padding: 6px 18px;
                    font-size: 1rem;
                    font-weight: bold;
                    border-radius: 20px;
                    z-index: 2;
                    box-shadow: 0 4px 16px rgba(255, 65, 108, 0.15);
                    letter-spacing: 1px;
                    border: 2px solid #fff;
                    opacity: 0.95;
                }

                .book-price {
                    font-size: 1.1rem;
                    font-weight: 600;
                    color: #007bff;
                }

                .book-price .old-price {
                    text-decoration: line-through;
                    font-size: 0.85rem;
                    color: #888;
                    margin-left: 6px;
                }
            </style>

            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h2 class="fw-bold fs-2">Sản Phẩm Nổi Bật</h2>
                    <a href="#" onclick="showPage('books')" class="btn btn-outline-primary">
                        Xem Tất Cả <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>

                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4" id="newestBooks">
                    @forelse($newestBooks->take(8) as $book)
                        @php
                            $detail = optional($book->details)->first();
                            $hasPromotion = $detail && $detail->promotion_price && $detail->promotion_price < $detail->price;
                        @endphp

                        <div class="col">
                            <div class="card h-100 product-card">
                                <div class="product-card-inner">
                                    <div class="position-relative">
                                        @if($hasPromotion)
                                            <div class="discount-badge">
                                                -{{ round((($detail->price - $detail->promotion_price) / $detail->price) * 100) }}%
                                            </div>
                                        @endif
                                        <img src="{{ $book->main_image_url }}" alt="{{ $book->name }}">
                                    </div>

                                    <div class="card-body d-flex flex-column">
                                        <h6 class="flex-grow-1 fs-6">
                                            <a href="{{ route('product.show', $book->id) }}"
                                                class="text-dark text-decoration-none">
                                                {{ $book->name }}
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-2">{{ $book->author->name ?? 'N/A' }}</p>

                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <p class="book-price mb-0">
                                                @if($hasPromotion)
                                                    {{ number_format($detail->promotion_price, 0, '', '.') }}₫
                                                    <span class="old-price">{{ number_format($detail->price, 0, '', '.') }}₫</span>
                                                @elseif($detail)
                                                    {{ number_format($detail->price, 0, '', '.') }}₫
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                            <a href="{{ route('product.show', $book->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted">Hiện chưa có sách mới nào.</p>
                        </div>
                    @endforelse
                </div>


            </div>
        </section>





        {{-- Danh Mục Sách Phổ Biến (tăng kích thước + hiệu ứng đẹp) --}}
        {{-- Danh Mục Sách Phổ Biến (áp CSS + biểu tượng theo nội dung) --}}
        <section class="py-5" style="background-color: white;">
            <style>
                .category-hover {
                    width: 90px;
                    height: 90px;
                    border-radius: 50%;
                    background-color: #e9f0ff;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0 auto 15px;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }

                .category-hover:hover {
                    transform: scale(1.1);
                    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
                }

                .category-hover i {
                    font-size: 30px;
                    color: #007bff;
                    transition: color 0.3s ease;
                }

                .category-hover:hover i {
                    color: #0056b3;
                }

                .category-title {
                    font-size: 1rem;
                    font-weight: 600;
                    color: #212529;
                    transition: color 0.3s ease;
                }

                .category-hover:hover+.category-title {
                    color: #0d6efd;
                }
            </style>

            <div class="container">
                <h2 class="text-center mb-5 fw-bold" style="font-size: 2rem;">Danh Mục Sách Phổ Biến</h2>
                <div class="row justify-content-center" id="popularCategories">
                    @forelse ($popularCategories as $category)
                        @php
                            $categoryIcons = [
                                'Khoa Học' => 'fa-flask-vial',
                                'Thiếu Nhi' => 'fa-child',
                                'Giáo Dục' => 'fa-graduation-cap',
                                'Tình Cảm' => 'fa-heart',
                                'Tâm Lý' => 'fa-brain',
                                'Tiểu Thuyết' => 'fa-book-open',
                                'Kỹ Năng Sống' => 'fa-hands-helping',
                                'Ngoại Ngữ' => 'fa-language',
                                // Thêm danh mục khác ở đây
                            ];
                            $iconClass = $categoryIcons[$category->name] ?? 'fa-book';
                            $url = route('category.show', $category->id);
                        @endphp

                        <div class="col-lg-3 col-md-4 col-6 mb-4 text-center">
                            <a href="{{ $url }}" class="d-block text-decoration-none">
                                <div class="category-hover">
                                    <i class="fas {{ $iconClass }}"></i>
                                </div>
                                <h5 class="mb-0 category-title">{{ $category->name }}</h5>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p class="text-muted">Chưa có danh mục nào để hiển thị.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>


        <section class="py-5" style="background-color: #f8f9fa;">
            <div class="container">
                <h2 class="text-center mb-5 fw-bold">📣 Khách Hàng Nói Gì Về BookStore</h2>

                @if(session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                <div class="row">
                    @forelse($reviews as $review)
                        <div class="col-md-4 mb-4">
                            <div class="review-card text-center h-100 p-4 border rounded bg-white shadow-sm">
                                {{-- Ảnh đại diện ngẫu nhiên --}}
                                <img src="https://i.pravatar.cc/80?u={{ $review->user->id }}" class="rounded-circle mb-3"
                                    alt="avatar">

                                {{-- Sao đánh giá --}}
                                <div class="rating-stars mb-3 text-warning fs-5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                    @endfor
                                </div>

                                {{-- Bình luận --}}
                                <p class="mb-3 text-secondary fst-italic">"{{ $review->comment }}"</p>

                                {{-- Tên người dùng --}}
                                <strong class="text-primary d-block">— {{ $review->user->name }}</strong>

                                {{-- Tuỳ chọn quản lý (nếu cần) --}}
                                @auth
                                    @if(Auth::user()->is_admin)
                                        <form action="{{ route('reviews.destroy', $review) }}" method="POST"
                                            onsubmit="return confirm('Xoá đánh giá này?')" class="mt-3">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">🗑️ Xoá</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p class="text-muted">Chưa có đánh giá nào từ người dùng.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>



    </div>



    <div id="bookDetail" class="page">
        <div class="container py-5" id="bookDetailContent"></div>
    </div>

    <div id="authors" class="page">
        <div class="container py-5">
            <h2 class="text-center mb-5 fw-bold">Tác Giả Nổi Tiếng</h2>
            <div class="row row-cols-2 row-cols-md-4 g-4" id="authorsList"></div>
        </div>
    </div>

    <div id="contact" class="page">
        <div class="container py-5">
            <h2 class="text-center mb-5 fw-bold">Liên Hệ Với Chúng Tôi</h2>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3>Thông tin liên lạc</h3>
                            <p>Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với chúng tôi.</p>
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="fas fa-map-marker-alt fa-fw me-2 text-primary"></i>123 Đường
                                    Sách, Quận Tri Thức, Hà Nội</li>
                                <li class="mb-3"><i class="fas fa-phone fa-fw me-2 text-primary"></i>(+84) 123 456 789</li>
                                <li class="mb-3"><i
                                        class="fas fa-envelope fa-fw me-2 text-primary"></i>support@bookstore.com</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3>Gửi tin nhắn</h3>
                            <form
                                onsubmit="event.preventDefault(); showToast('Đã gửi tin nhắn thành công!'); this.reset();">
                                <div class="mb-3"><input type="text" class="form-control" placeholder="Họ và Tên" required>
                                </div>
                                <div class="mb-3"><input type="email" class="form-control" placeholder="Email của bạn"
                                        required></div>
                                <div class="mb-3"><textarea class="form-control" rows="5" placeholder="Nội dung tin nhắn"
                                        required></textarea></div>
                                <button type="submit" class="btn btn-primary w-100">Gửi Tin Nhắn</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="cart" class="page">
        <div class="container py-5">
            <h2 class="mb-4"><i class="fas fa-shopping-cart me-2"></i>Giỏ Hàng Của Bạn</h2>
            <div class="row">
                <div class="col-lg-8" id="cartItemsContainer"></div>
                <div class="col-lg-4">
                    <div class="card position-sticky" style="top: 80px;">
                        <div class="card-header">
                            <h5 class="mb-0">Tổng Kết Đơn Hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Mã Giảm Giá</label>
                                <div class="input-group">
                                    <input type="text" class="form-control voucher-input" id="voucherCode"
                                        placeholder="Nhập mã voucher">
                                    <button class="btn btn-outline-primary" onclick="applyVoucher()">Áp Dụng</button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span><span id="subtotal">0đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Giảm giá:</span><span class="text-success" id="discount">-0đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span><span id="shippingFee">0đ</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <strong>Tổng cộng:</strong><strong class="text-danger fs-5" id="total">0đ</strong>
                            </div>
                            <button class="btn btn-success w-100 mb-2" onclick="proceedToCheckout()" id="checkoutBtn"
                                disabled>
                                <i class="fas fa-credit-card me-2"></i>Thanh Toán
                            </button>
                            <button class="btn btn-outline-primary w-100" onclick="showPage('books')">
                                <i class="fas fa-arrow-left me-2"></i>Tiếp Tục Mua Sắm
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="checkout" class="page">
        <div class="container py-5">
            <h2 class="mb-4"><i class="fas fa-credit-card me-2"></i>Thanh Toán & Giao Hàng</h2>
            <div class="row">
                <div class="col-lg-7">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Thông Tin Giao Hàng</h5>
                        </div>
                        <div class="card-body">
                            <form id="checkoutForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3"><label class="form-label">Họ và Tên *</label><input
                                            type="text" class="form-control" id="customerName" required></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Số Điện Thoại *</label><input
                                            type="tel" class="form-control" id="phoneNumber" required></div>
                                </div>
                                <div class="mb-3"><label class="form-label">Email</label><input type="email"
                                        class="form-control" id="email"></div>
                                <div class="mb-3"><label class="form-label">Địa Chỉ Giao Hàng *</label><textarea
                                        class="form-control" id="shippingAddress" rows="3" required></textarea></div>
                                <div class="mb-3"><label class="form-label">Ghi Chú</label><textarea class="form-control"
                                        id="orderNote" rows="2"></textarea></div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Phương Thức Thanh Toán</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3"><input class="form-check-input" type="radio" name="paymentMethod"
                                    id="cod" value="COD" checked><label class="form-check-label" for="cod"><i
                                        class="fas fa-money-bill-wave me-2"></i>Thanh toán khi nhận hàng (COD)</label></div>
                            <div class="form-check mb-3"><input class="form-check-input" type="radio" name="paymentMethod"
                                    id="qrpay" value="QR_PAY"><label class="form-check-label" for="qrpay"><i
                                        class="fas fa-qrcode me-2"></i>Thanh toán QR Code</label></div>
                            <div class="form-check"><input class="form-check-input" type="radio" name="paymentMethod"
                                    id="bank" value="BANK"><label class="form-check-label" for="bank"><i
                                        class="fas fa-university me-2"></i>Chuyển khoản ngân hàng</label></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card position-sticky" style="top: 80px;">
                        <div class="card-header">
                            <h5 class="mb-0">Đơn Hàng Của Bạn</h5>
                        </div>
                        <div class="card-body">
                            <div id="checkoutSummary" style="max-height: 250px; overflow-y: auto; padding-right: 15px;">
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-2"><span>Tạm tính:</span><span
                                    id="checkoutSubtotal">0đ</span></div>
                            <div class="d-flex justify-content-between mb-2"><span>Giảm giá:</span><span
                                    class="text-success" id="checkoutDiscount">-0đ</span></div>
                            <div class="d-flex justify-content-between mb-2"><span>Phí vận chuyển:</span><span
                                    id="checkoutShipping">0đ</span></div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4"><strong>Tổng cộng:</strong><strong
                                    class="text-danger fs-5" id="checkoutTotal">0đ</strong></div>
                            <button class="btn btn-success w-100 btn-lg" onclick="placeOrder()"><i
                                    class="fas fa-check-circle me-2"></i>XÁC NHẬN ĐẶT HÀNG</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="profile" class="page">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card position-sticky" style="top: 80px;">
                        <div class="card-body text-center">
                            <img src="https://i.pravatar.cc/150?u=4" class="rounded-circle mb-3" alt="avatar"
                                id="profileAvatar">
                            <h5 id="profileName"></h5>
                            <p class="text-muted" id="profileEmail"></p>
                        </div>
                        <div class="list-group list-group-flush" id="profile-tabs" role="tablist">
                            <a class="list-group-item list-group-item-action active" data-bs-toggle="tab"
                                href="#tab-profile" role="tab">Thông tin cá nhân</a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="tab" href="#tab-orders"
                                role="tab">Lịch sử đơn hàng</a>
                            <a class="list-group-item list-group-item-action" data-bs-toggle="tab" href="#tab-address"
                                role="tab">Địa chỉ của tôi</a>
                            <a class="list-group-item list-group-item-action text-danger" href="#" onclick="logout()">Đăng
                                xuất</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab-profile" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Thông Tin Tài Khoản</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-6 mb-3"><label class="form-label">Họ và Tên</label><input
                                                    type="text" class="form-control" id="profileFormName"></div>
                                            <div class="col-md-6 mb-3"><label class="form-label">Email</label><input
                                                    type="email" class="form-control" id="profileFormEmail" disabled></div>
                                        </div>
                                        <button class="btn btn-primary">Lưu thay đổi</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-orders" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Lịch Sử Đơn Hàng</h5>
                                </div>
                                <div class="card-body" id="orderHistory"></div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab-address" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Sổ Địa Chỉ</h5>
                                </div>
                                <div class="card-body">
                                    <p>Bạn chưa có địa chỉ nào.</p>
                                    <button class="btn btn-primary">Thêm địa chỉ mới</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
