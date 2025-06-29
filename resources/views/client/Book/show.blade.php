@extends('client.layouts.app')

@section('content')
<div class="book-detail-wrapper">
    <!-- Breadcrumb -->
    <div class="container">
        <nav aria-label="breadcrumb" class="my-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('books.index') }}">Sách</a></li>
                <li class="breadcrumb-item active">{{ $book->name }}</li>
            </ol>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="book-detail-container">
            <div class="row g-5">
                <!-- Book Image Gallery -->
                <div class="col-lg-5">
                    <div class="image-gallery">
                        <div class="main-image">
                            @if($book->image)
                                <img src="{{ asset('storage/' . $book->image) }}"
                                     alt="{{ $book->name }}"
                                     class="img-fluid main-book-image">
                            @else
                                <div class="placeholder-image">
                                    <i class="fas fa-book"></i>
                                    <p>Chưa có ảnh</p>
                                </div>
                            @endif
                            <div class="image-overlay">
                                <button class="btn btn-light btn-sm zoom-btn">
                                    <i class="fas fa-search-plus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Thumbnail images would go here -->
                    </div>
                </div>

                <!-- Book Information -->
                <div class="col-lg-7">
                    <div class="book-info">
                        <!-- Title & Rating -->
                        <div class="book-header">
                            <h1 class="book-title">{{ $book->name }}</h1>
                            <div class="rating-section">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= 4 ? 'active' : '' }}"></i>
                                    @endfor
                                </div>
                                <span class="rating-count">(4.2) 256 đánh giá</span>
                                <span class="sold-count">• 1.2k đã bán</span>
                            </div>
                        </div>

                        <!-- Price Section -->
                        <div class="price-section">
                            @foreach ($book->details as $detail)
                                <div class="price-container">
                                    @if ($detail->promotion_price && $detail->promotion_price < $detail->price)
                                        <div class="price-row">
                                            <span class="current-price">{{ number_format($detail->promotion_price) }}₫</span>
                                            <span class="original-price">{{ number_format($detail->price) }}₫</span>
                                        </div>
                                        <div class="discount-info">
                                            <span class="discount-percent">
                                                -{{ round((($detail->price - $detail->promotion_price) / $detail->price) * 100) }}%
                                            </span>
                                            <span class="save-amount">
                                                Tiết kiệm {{ number_format($detail->price - $detail->promotion_price) }}₫
                                            </span>
                                        </div>
                                    @else
                                        <span class="current-price">{{ number_format($detail->price) }}₫</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Book Details -->
                        <div class="book-details">
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="detail-label">Tác giả:</span>
                                    <span class="detail-value author-link">{{ $book->author->name ?? 'Chưa rõ' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Nhà xuất bản:</span>
                                    <span class="detail-value">{{ $book->publisher->name ?? 'Chưa rõ' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Thể loại:</span>
                                    <span class="detail-value">
                                        <span class="category-tag">{{ $book->category->name ?? 'Chưa rõ' }}</span>
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Tình trạng:</span>
                                    <span class="detail-value">
                                        <span class="stock-badge in-stock">
                                            <i class="fas fa-check-circle"></i> Còn hàng
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Info -->
                        <div class="shipping-info">
                            <div class="shipping-item">
                                <i class="fas fa-shipping-fast"></i>
                                <div class="shipping-text">
                                    <strong>Miễn phí vận chuyển</strong>
                                    <span>Đơn hàng từ 150.000₫</span>
                                </div>
                            </div>
                            <div class="shipping-item">
                                <i class="fas fa-undo-alt"></i>
                                <div class="shipping-text">
                                    <strong>Đổi trả miễn phí</strong>
                                    <span>Trong vòng 15 ngày</span>
                                </div>
                            </div>
                        </div>

                        <!-- Purchase Actions -->
                        <div class="purchase-section">
                            <div class="quantity-section">
                                <label class="quantity-label">Số lượng:</label>
                                <div class="quantity-controls">
                                    <button type="button" class="qty-btn minus" onclick="decreaseQuantity()">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" id="quantity" value="1" min="1" max="99" class="qty-input">
                                    <button type="button" class="qty-btn plus" onclick="increaseQuantity()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="action-buttons">
                                <button class="btn btn-primary add-to-cart" onclick="addToCart('{{ $detail->id }}')">
                                    <i class="fas fa-shopping-cart"></i>
                                    Thêm vào giỏ hàng
                                </button>
                                <button class="btn btn-success buy-now">
                                    <i class="fas fa-bolt"></i>
                                    Mua ngay
                                </button>
                                <button class="btn btn-outline wishlist-btn">
                                    <i class="far fa-heart"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Trust Badges -->
                        <div class="trust-badges">
                            <div class="badge-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>Hàng chính hãng</span>
                            </div>
                            <div class="badge-item">
                                <i class="fas fa-medal"></i>
                                <span>Chất lượng cao</span>
                            </div>
                            <div class="badge-item">
                                <i class="fas fa-headset"></i>
                                <span>Hỗ trợ 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tabs -->
    <div class="container mt-5">
        <div class="product-tabs">
            <div class="tab-navigation">
                <button class="tab-btn active" data-tab="description">Mô tả sản phẩm</button>
                <button class="tab-btn" data-tab="specifications">Thông số kỹ thuật</button>
                <button class="tab-btn" data-tab="reviews">Đánh giá (256)</button>
                <button class="tab-btn" data-tab="shipping">Vận chuyển</button>
            </div>

            <div class="tab-content-container">
                <div class="tab-content active" id="description">
                    <div class="description-content">
                        <h4>Mô tả sản phẩm</h4>
                        <div class="content-text">
                            {!! nl2br(e($book->description)) !!}
                        </div>
                    </div>
                </div>

                <div class="tab-content" id="specifications">
                    <div class="specifications-table">
                        <h4>Thông số kỹ thuật</h4>
                        <table class="spec-table">
                            <tr>
                                <td>Tác giả</td>
                                <td>{{ $book->author->name ?? 'Chưa rõ' }}</td>
                            </tr>
                            <tr>
                                <td>Nhà xuất bản</td>
                                <td>{{ $book->publisher->name ?? 'Chưa rõ' }}</td>
                            </tr>
                            <tr>
                                <td>Thể loại</td>
                                <td>{{ $book->category->name ?? 'Chưa rõ' }}</td>
                            </tr>
                            <tr>
                                <td>Ngôn ngữ</td>
                                <td>Tiếng Việt</td>
                            </tr>
                            <tr>
                                <td>Kích thước</td>
                                <td>{{ $book->dimensions ?? '20.5 x 14.5 cm' }}</td>
                            </tr>
                            <tr>
                                <td>Số trang</td>
                                <td>{{ $book->pages ?? 'Chưa rõ' }}</td>
                            </tr>
                            <tr>
                                <td>Trọng lượng</td>
                                <td>{{ $book->weight ?? '300g' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="tab-content" id="reviews">
                    <div class="reviews-section">
                        <h4>Đánh giá từ khách hàng</h4>
                        <div class="review-summary">
                            <div class="overall-rating">
                                <span class="rating-number">4.2</span>
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= 4 ? 'active' : '' }}"></i>
                                    @endfor
                                </div>
                                <span class="total-reviews">256 đánh giá</span>
                            </div>
                        </div>
                        <p class="text-muted">Chức năng đánh giá chi tiết sẽ được cập nhật sớm...</p>
                    </div>
                </div>

                <div class="tab-content" id="shipping">
                    <div class="shipping-policy">
                        <h4>Chính sách vận chuyển</h4>
                        <div class="policy-content">
                            <div class="policy-item">
                                <h6><i class="fas fa-shipping-fast"></i> Miễn phí vận chuyển</h6>
                                <p>Áp dụng cho đơn hàng từ 150.000₫ trở lên</p>
                            </div>
                            <div class="policy-item">
                                <h6><i class="fas fa-clock"></i> Thời gian giao hàng</h6>
                                <p>1-2 ngày làm việc tại Hà Nội và TP.HCM<br>3-5 ngày làm việc tại các tỉnh thành khác</p>
                            </div>
                            <div class="policy-item">
                                <h6><i class="fas fa-undo-alt"></i> Đổi trả</h6>
                                <p>Đổi trả miễn phí trong vòng 15 ngày kể từ ngày nhận hàng</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function addToCart(bookDetailId) {
        fetch("{{ route('cart.add') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                book_detail_id: bookDetailId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Optional: cập nhật icon giỏ hàng trên header
            } else {
                alert('Thêm vào giỏ hàng thất bại!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Đã xảy ra lỗi!');
        });
    }
</script>



<style>
/* Modern Design System */
:root {
    --primary-color: #2563eb;
    --secondary-color: #64748b;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --border-color: #e2e8f0;
    --background-light: #f8fafc;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
    --radius: 8px;
    --radius-lg: 12px;
}

.book-detail-wrapper {
    background: #ffffff;
    min-height: 100vh;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
    font-size: 0.875rem;
}

.breadcrumb-item a {
    color: var(--text-secondary);
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb-item a:hover {
    color: var(--primary-color);
}

.book-detail-container {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    padding: 2rem;
    margin-bottom: 2rem;
}

/* Image Gallery */
.image-gallery {
    position: relative;
}

.main-image {
    position: relative;
    background: var(--background-light);
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 1px solid var(--border-color);
}

.main-book-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.main-book-image:hover {
    transform: scale(1.05);
}

.placeholder-image {
    height: 500px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    background: var(--background-light);
}

.placeholder-image i {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.image-overlay {
    position: absolute;
    top: 1rem;
    right: 1rem;
    opacity: 0;
    transition: opacity 0.3s;
}

.main-image:hover .image-overlay {
    opacity: 1;
}

/* Book Information */
.book-info {
    height: 100%;
}

.book-header {
    margin-bottom: 1.5rem;
}

.book-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary);
    line-height: 1.3;
    margin-bottom: 0.75rem;
}

.rating-section {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.rating-stars {
    display: flex;
    gap: 2px;
}

.rating-stars i {
    font-size: 1rem;
    color: #d1d5db;
    transition: color 0.2s;
}

.rating-stars i.active {
    color: var(--warning-color);
}

.rating-count {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.sold-count {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

/* Price Section */
.price-section {
    background: linear-gradient(135deg, #fef7f0 0%, #fef3ec 100%);
    border: 1px solid #fed7aa;
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.price-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.current-price {
    font-size: 2rem;
    font-weight: 700;
    color: var(--danger-color);
}

.original-price {
    font-size: 1.125rem;
    color: var(--text-secondary);
    text-decoration: line-through;
}

.discount-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.discount-percent {
    background: var(--danger-color);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.save-amount {
    color: var(--success-color);
    font-size: 0.875rem;
    font-weight: 500;
}

/* Book Details */
.book-details {
    margin-bottom: 1.5rem;
}

.detail-grid {
    display: grid;
    gap: 0.75rem;
}

.detail-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 500;
    color: var(--text-secondary);
    min-width: 120px;
}

.detail-value {
    color: var(--text-primary);
    font-weight: 500;
}

.author-link {
    color: var(--primary-color);
    cursor: pointer;
    transition: color 0.2s;
}

.author-link:hover {
    color: var(--primary-color);
    text-decoration: underline;
}

.category-tag {
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    color: var(--success-color);
    font-weight: 500;
}

/* Shipping Info */
.shipping-info {
    background: var(--background-light);
    border-radius: var(--radius);
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    gap: 1.5rem;
}

.shipping-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.shipping-item i {
    color: var(--primary-color);
    font-size: 1.25rem;
}

.shipping-text strong {
    display: block;
    font-size: 0.875rem;
    color: var(--text-primary);
}

.shipping-text span {
    font-size: 0.75rem;
    color: var(--text-secondary);
}

/* Purchase Section */
.purchase-section {
    margin-bottom: 1.5rem;
}

.quantity-section {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.quantity-label {
    font-weight: 500;
    color: var(--text-primary);
}

.quantity-controls {
    display: flex;
    align-items: center;
    border: 1px solid var(--border-color);
    border-radius: var(--radius);
    overflow: hidden;
}

.qty-btn {
    background: white;
    border: none;
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    color: var(--text-secondary);
    transition: all 0.2s;
}

.qty-btn:hover {
    background: var(--background-light);
    color: var(--text-primary);
}

.qty-input {
    border: none;
    padding: 0.5rem;
    width: 60px;
    text-align: center;
    font-weight: 500;
    outline: none;
    border-left: 1px solid var(--border-color);
    border-right: 1px solid var(--border-color);
}

.action-buttons {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius);
    font-weight: 500;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    font-size: 0.875rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-success {
    background: var(--success-color);
    color: white;
}

.btn-success:hover {
    background: #059669;
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-outline {
    background: white;
    color: var(--text-secondary);
    border: 1px solid var(--border-color);
}

.btn-outline:hover {
    background: var(--background-light);
    color: var(--danger-color);
    border-color: var(--danger-color);
}

/* Trust Badges */
.trust-badges {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.badge-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.badge-item i {
    color: var(--success-color);
}

/* Product Tabs */
.product-tabs {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.tab-navigation {
    display: flex;
    background: var(--background-light);
    border-bottom: 1px solid var(--border-color);
}

.tab-btn {
    flex: 1;
    padding: 1rem 1.5rem;
    background: transparent;
    border: none;
    cursor: pointer;
    font-weight: 500;
    color: var(--text-secondary);
    transition: all 0.2s;
    position: relative;
}

.tab-btn:hover {
    color: var(--primary-color);
    background: rgba(37, 99, 235, 0.05);
}

.tab-btn.active {
    color: var(--primary-color);
    background: white;
}

.tab-btn.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--primary-color);
}

.tab-content-container {
    padding: 2rem;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.tab-content h4 {
    color: var(--text-primary);
    margin-bottom: 1rem;
    font-weight: 600;
}

.content-text {
    line-height: 1.7;
    color: var(--text-secondary);
}

.spec-table {
    width: 100%;
    border-collapse: collapse;
}

.spec-table tr {
    border-bottom: 1px solid var(--border-color);
}

.spec-table td {
    padding: 0.75rem 0;
    vertical-align: top;
}

.spec-table td:first-child {
    font-weight: 500;
    color: var(--text-secondary);
    width: 150px;
}

.spec-table td:last-child {
    color: var(--text-primary);
}

/* Reviews */
.review-summary {
    background: var(--background-light);
    border-radius: var(--radius);
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.overall-rating {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.rating-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--warning-color);
}

.total-reviews {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

/* Policy Content */
.policy-content {
    display: grid;
    gap: 1.5rem;
}

.policy-item h6 {
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.policy-item h6 i {
    color: var(--primary-color);
}

.policy-item p {
    color: var(--text-secondary);
    line-height: 1.6;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .book-detail-container {
        padding: 1rem;
    }

    .book-title {
        font-size: 1.5rem;
    }

    .current-price {
        font-size: 1.5rem;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn {
        justify-content: center;
    }

    .shipping-info {
        flex-direction: column;
        gap: 1rem;
    }

    .trust-badges {
        justify-content: center;
    }

    .tab-navigation {
        flex-wrap: wrap;
    }

    .tab-btn {
        flex: none;
        min-width: 50%;
    }
}

@media (max-width: 576px) {
    .main-book-image {
        height: 300px;
    }

    .placeholder-image {
        height: 300px;
    }

    .tab-btn {
        min-width: 100%;
    }
}
</style>

<script>
// Quantity Controls
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue < 99) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

// Tab Functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all tabs and contents
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });
});

// Wishlist Toggle
document.addEventListener('DOMContentLoaded', function() {
    const wishlistBtn = document.querySelector('.wishlist-btn');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                this.style.color = '#ef4444';
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                this.style.color = '';
            }
        });
    }
});
</script>
@endsection
