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
                    <p class="lead mb-4">Hàng ngàn cuốn sách hay từ các tác giả nổi tiếng, từ văn học đến khoa học, từ tiểu thuyết đến sách giáo dục. Đặc biệt có nhiều chương trình khuyến mãi hấp dẫn!</p>
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
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="fw-bold">Sách Nổi Bật & Khuyến Mãi</h2>
                <a href="#" onclick="showPage('books')" class="btn btn-outline-primary">Xem Tất Cả <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
            {{-- Sản phẩm nổi bật & khuyến mãi từ DB --}}
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4" id="featuredBooks">
                @foreach($books->take(8) as $book)
                    @php $detail = $book->details->first(); @endphp
                    <div class="col">
                        <div class="card book-card h-100">
                            <div class="position-relative">
                                @if($detail && $detail->promotion_price && $detail->promotion_price < $detail->price)
                                    <div class="discount-badge">
                                        -{{ round((($detail->price - $detail->promotion_price) / $detail->price) * 100) }}%
                                    </div>
                                @endif
                                <img src="{{ asset('storage/' . ($book->images->first()->url ?? 'client/img/products/noimage.png')) }}"
                                     class="card-img-top book-image"
                                     alt="{{ $book->name }}">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title flex-grow-1" style="font-size: 0.95rem;">
                                    <a href="{{ route('book.show', $book->id) }}" class="text-dark text-decoration-none">{{ $book->name }}</a>
                                </h6>
                                <p class="card-text text-muted small mb-2">{{ $book->author->name ?? 'N/A' }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <p class="price mb-0" style="font-size: 1.1rem;">
                                        @if($detail && $detail->promotion_price && $detail->promotion_price < $detail->price)
                                            {{ number_format($detail->promotion_price, 0, '', '.') }}₫
                                            <span class="old-price">{{ number_format($detail->price, 0, '', '.') }}₫</span>
                                        @elseif($detail)
                                            {{ number_format($detail->price, 0, '', '.') }}₫
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                    <a href="{{ route('book.show', $book->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="py-5 bg-white">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Danh Mục Sách Phổ Biến</h2>
            <div class="row" id="popularCategories"></div>
        </div>
    </section>
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Khách Hàng Nói Gì Về BookStore</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="review-card text-center h-100">
                        <img src="https://i.pravatar.cc/80?u=1" class="rounded-circle mb-3" alt="avatar">
                        <div class="rating-stars mb-3">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p>"Sách chất lượng, giao hàng nhanh. Rất hài lòng với dịch vụ của BookStore!"</p>
                        <strong class="text-primary">- Nguyễn Thị Lan</strong>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="review-card text-center h-100">
                        <img src="https://i.pravatar.cc/80?u=2" class="rounded-circle mb-3" alt="avatar">
                        <div class="rating-stars mb-3">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p>"Giá cả phải chăng, nhiều chương trình khuyến mãi. Sẽ tiếp tục ủng hộ!"</p>
                        <strong class="text-primary">- Trần Văn Nam</strong>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="review-card text-center h-100">
                        <img src="https://i.pravatar.cc/80?u=3" class="rounded-circle mb-3" alt="avatar">
                        <div class="rating-stars mb-3">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
                        </div>
                        <p>"Tuyển chọn sách hay, đặc biệt là sách thiếu nhi cho con tôi rất phong phú."</p>
                        <strong class="text-primary">- Lê Thị Hoa</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div id="books" class="page">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3">
                <div class="card position-sticky" style="top: 80px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Bộ Lọc Sản Phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Danh Mục</label>
                            <select class="form-select" id="categoryFilter" onchange="applyFilters()"></select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Khoảng Giá</label>
                            <select class="form-select" id="priceFilter" onchange="applyFilters()">
                                <option value="">Tất cả</option>
                                <option value="0-100000">Dưới 100.000đ</option>
                                <option value="100000-200000">100.000đ - 200.000đ</option>
                                <option value="200000-Infinity">Trên 200.000đ</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nhà Xuất Bản</label>
                            <select class="form-select" id="publisherFilter" onchange="applyFilters()"></select>
                        </div>
                        <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                            <i class="fas fa-times me-2"></i>Xóa Bộ Lọc
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
                    <h2 class="h4 mb-0">Tất Cả Sách <span class="text-muted fw-normal fs-6" id="bookCount">(0 cuốn)</span></h2>
                    <div class="d-flex align-items-center">
                        <span class="me-2 d-none d-md-inline">Sắp xếp:</span>
                        <select class="form-select" style="width: auto;" id="sortFilter" onchange="applyFilters()">
                            <option value="newest">Mới nhất</option>
                            <option value="price-asc">Giá thấp đến cao</option>
                            <option value="price-desc">Giá cao đến thấp</option>
                            <option value="name-asc">Tên A-Z</option>
                            <option value="rating">Đánh giá cao</option>
                        </select>
                    </div>
                </div>
                <div class="row row-cols-2 row-cols-md-3 g-4" id="allBooks"></div>
                <nav class="mt-5"><ul class="pagination justify-content-center" id="pagination"></ul></nav>
            </div>
        </div>
    </div>
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
                            <li class="mb-3"><i class="fas fa-map-marker-alt fa-fw me-2 text-primary"></i>123 Đường Sách, Quận Tri Thức, Hà Nội</li>
                            <li class="mb-3"><i class="fas fa-phone fa-fw me-2 text-primary"></i>(+84) 123 456 789</li>
                            <li class="mb-3"><i class="fas fa-envelope fa-fw me-2 text-primary"></i>support@bookstore.com</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h3>Gửi tin nhắn</h3>
                        <form onsubmit="event.preventDefault(); showToast('Đã gửi tin nhắn thành công!'); this.reset();">
                            <div class="mb-3"><input type="text" class="form-control" placeholder="Họ và Tên" required></div>
                            <div class="mb-3"><input type="email" class="form-control" placeholder="Email của bạn" required></div>
                            <div class="mb-3"><textarea class="form-control" rows="5" placeholder="Nội dung tin nhắn" required></textarea></div>
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
                    <div class="card-header"><h5 class="mb-0">Tổng Kết Đơn Hàng</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Mã Giảm Giá</label>
                            <div class="input-group">
                                <input type="text" class="form-control voucher-input" id="voucherCode" placeholder="Nhập mã voucher">
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
                        <button class="btn btn-success w-100 mb-2" onclick="proceedToCheckout()" id="checkoutBtn" disabled>
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
                    <div class="card-header"><h5 class="mb-0">Thông Tin Giao Hàng</h5></div>
                    <div class="card-body">
                        <form id="checkoutForm">
                            <div class="row">
                                <div class="col-md-6 mb-3"><label class="form-label">Họ và Tên *</label><input type="text" class="form-control" id="customerName" required></div>
                                <div class="col-md-6 mb-3"><label class="form-label">Số Điện Thoại *</label><input type="tel" class="form-control" id="phoneNumber" required></div>
                            </div>
                            <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" id="email"></div>
                            <div class="mb-3"><label class="form-label">Địa Chỉ Giao Hàng *</label><textarea class="form-control" id="shippingAddress" rows="3" required></textarea></div>
                            <div class="mb-3"><label class="form-label">Ghi Chú</label><textarea class="form-control" id="orderNote" rows="2"></textarea></div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Phương Thức Thanh Toán</h5></div>
                    <div class="card-body">
                        <div class="form-check mb-3"><input class="form-check-input" type="radio" name="paymentMethod" id="cod" value="COD" checked><label class="form-check-label" for="cod"><i class="fas fa-money-bill-wave me-2"></i>Thanh toán khi nhận hàng (COD)</label></div>
                        <div class="form-check mb-3"><input class="form-check-input" type="radio" name="paymentMethod" id="qrpay" value="QR_PAY"><label class="form-check-label" for="qrpay"><i class="fas fa-qrcode me-2"></i>Thanh toán QR Code</label></div>
                        <div class="form-check"><input class="form-check-input" type="radio" name="paymentMethod" id="bank" value="BANK"><label class="form-check-label" for="bank"><i class="fas fa-university me-2"></i>Chuyển khoản ngân hàng</label></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card position-sticky" style="top: 80px;">
                    <div class="card-header"><h5 class="mb-0">Đơn Hàng Của Bạn</h5></div>
                    <div class="card-body">
                        <div id="checkoutSummary" style="max-height: 250px; overflow-y: auto; padding-right: 15px;"></div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2"><span>Tạm tính:</span><span id="checkoutSubtotal">0đ</span></div>
                        <div class="d-flex justify-content-between mb-2"><span>Giảm giá:</span><span class="text-success" id="checkoutDiscount">-0đ</span></div>
                        <div class="d-flex justify-content-between mb-2"><span>Phí vận chuyển:</span><span id="checkoutShipping">0đ</span></div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4"><strong>Tổng cộng:</strong><strong class="text-danger fs-5" id="checkoutTotal">0đ</strong></div>
                        <button class="btn btn-success w-100 btn-lg" onclick="placeOrder()"><i class="fas fa-check-circle me-2"></i>XÁC NHẬN ĐẶT HÀNG</button>
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
                        <img src="https://i.pravatar.cc/150?u=4" class="rounded-circle mb-3" alt="avatar" id="profileAvatar">
                        <h5 id="profileName"></h5>
                        <p class="text-muted" id="profileEmail"></p>
                    </div>
                    <div class="list-group list-group-flush" id="profile-tabs" role="tablist">
                        <a class="list-group-item list-group-item-action active" data-bs-toggle="tab" href="#tab-profile" role="tab">Thông tin cá nhân</a>
                        <a class="list-group-item list-group-item-action" data-bs-toggle="tab" href="#tab-orders" role="tab">Lịch sử đơn hàng</a>
                        <a class="list-group-item list-group-item-action" data-bs-toggle="tab" href="#tab-address" role="tab">Địa chỉ của tôi</a>
                        <a class="list-group-item list-group-item-action text-danger" href="#" onclick="logout()">Đăng xuất</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-profile" role="tabpanel">
                        <div class="card">
                            <div class="card-header"><h5 class="mb-0">Thông Tin Tài Khoản</h5></div>
                            <div class="card-body">
                                <form>
                                    <div class="row">
                                        <div class="col-md-6 mb-3"><label class="form-label">Họ và Tên</label><input type="text" class="form-control" id="profileFormName"></div>
                                        <div class="col-md-6 mb-3"><label class="form-label">Email</label><input type="email" class="form-control" id="profileFormEmail" disabled></div>
                                    </div>
                                    <button class="btn btn-primary">Lưu thay đổi</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-orders" role="tabpanel">
                        <div class="card">
                            <div class="card-header"><h5 class="mb-0">Lịch Sử Đơn Hàng</h5></div>
                            <div class="card-body" id="orderHistory"></div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-address" role="tabpanel">
                        <div class="card">
                            <div class="card-header"><h5 class="mb-0">Sổ Địa Chỉ</h5></div>
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
