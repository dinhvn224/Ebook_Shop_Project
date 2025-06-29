@extends('client.layouts.app')

@section('content')
<div class="book-detail-wrapper">
    <!-- Breadcrumb -->
    <div class="breadcrumb-custom">
        <a href="/">TRANG CHỦ</a>
        {{-- <span class="divider">›</span>
        <a href="/books">SÁCH</a> --}}
        <span class="divider">›</span>
        <span>{{ strtoupper($book->category->name ?? 'CHI TIẾT') }}</span>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="book-detail-flex">
            <div class="left-image">
                <div class="main-image">
                    @php
                        $allImages = $book->images;
                        $mainImgUrl = $book->images->where('is_main', 1)->first()->url ?? $book->images->first()->url ?? null;
                    @endphp

                    @if($mainImgUrl)
                        <img src="{{ asset('storage/' . $mainImgUrl) }}"
                             alt="{{ $book->name }}"
                             class="img-fluid main-book-image" id="mainBookImage">
                    @else
                        <div class="placeholder-image">
                            <i class="fas fa-book"></i>
                            <p>Chưa có ảnh</p>
                        </div>
                    @endif
                </div>

            </div>
            <div class="right-info">
                <div class="book-info">
                    <!-- Title & Rating -->
                        <div class="book-header">
                            <h1 class="book-title">{{ $book->name }}</h1>
                            <div class="rating-section">
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= round($avgRating) ? 'active' : '' }}"></i>
                                    @endfor
                                </div>
                                <span class="rating-count">({{ number_format($avgRating, 1) }}) {{ $totalReviews }} đánh giá</span>
                                <span class="sold-count">• {{ $bookDetail->sold_count ?? '1.2k' }} đã bán</span>
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
                            <button class="btn btn-primary add-to-cart">
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

    <!-- Product Tabs -->
    <div class="container mt-5">
        <div class="product-tabs">
            <div class="tab-navigation">
                <button class="custom-tab-btn active" data-tab="description">Mô tả sản phẩm</button>
                <button class="custom-tab-btn" data-tab="specifications">Thông số kỹ thuật</button>
                <button class="custom-tab-btn" data-tab="reviews">Đánh giá ({{ $totalReviews }})</button>
                <button class="custom-tab-btn" data-tab="shipping">Vận chuyển</button>
            </div>

            <div class="custom-tab-content-container">
                <div class="custom-tab-content active" id="description">
                    <div class="description-content">
                        <h4>Mô tả sản phẩm</h4>
                        <div class="content-text">
                            {!! nl2br(e($book->description)) !!}
                        </div>
                    </div>
                </div>

                <div class="custom-tab-content" id="specifications">
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

                <div class="custom-tab-content" id="reviews">
                    <div class="reviews-section">
                        <h4>Đánh giá từ khách hàng</h4>
                        <!-- Tổng quan đánh giá -->
                        <div class="review-summary">
                            <div class="overall-rating">
                                <span class="rating-number">{{ number_format($avgRating, 1) }}</span>
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= round($avgRating) ? 'active' : '' }}"></i>
                                    @endfor
                                </div>
                                <span class="total-reviews">{{ $totalReviews }} đánh giá</span>
                            </div>
                        </div>

                        <!-- Form đánh giá hoặc hiển thị đánh giá đã có -->
                        @if ($existingReview)
                            <p>Bạn đã đánh giá sản phẩm này</p>
                            <div class="review-rating mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $existingReview->rating)
                                        <i class="fas fa-star rated"></i>
                                    @else
                                        <i class="fas fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p>Bình luận: "{{ $existingReview->comment }}"</p>
                            <span class="review-date">{{ $existingReview->created_at->format('d/m/Y H:i') }}</span>

                            @if ($existingReview->created_at->diffInHours(now()) <= 24)
                                <div class="mt-2">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editReviewModal">Chỉnh sửa</button>
                                </div>
                            @endif
                        @elseif ($hasPurchased)
                            <form action="{{ route('reviews.store') }}" method="POST" class="review-form mt-3">
                                @csrf
                                <input type="hidden" name="book_detail_id" value="{{ $bookDetail->id }}">
                                <div class="form-group">
                                    <label for="rating">Đánh giá (1-5 sao)</label>
                                    <div class="star-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required hidden>
                                            <label for="star{{ $i }}" class="star" data-value="{{ $i }}"><i class="fas fa-star"></i></label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label for="comment">Bình luận</label>
                                    <textarea name="comment" class="form-control" rows="4" maxlength="1000"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Gửi đánh giá</button>
                            </form>
                        @endif

                        <!-- Danh sách đánh giá -->
                        <div class="review-list mt-4">
                            @forelse($reviews as $review)
                                <div class="review-card">
                                    <div class="review-header">
                                        <strong>{{ $review->user->name }}</strong>
                                        <span class="review-date">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="review-rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'rated' : '' }}"></i>
                                        @endfor
                                    </div>
                                    <div class="review-comment">
                                        {{ $review->comment }}
                                    </div>
                                </div>
                            @empty
                                <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                            @endforelse
                        </div>

                        <!-- Modal chỉnh sửa -->
                        @if ($existingReview && $existingReview->created_at->diffInHours(now()) <= 24)
                            <div class="modal fade" id="editReviewModal" tabindex="-1" role="dialog" aria-labelledby="editReviewModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editReviewModalLabel">Chỉnh sửa đánh giá</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('reviews.update', $existingReview->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="book_detail_id" value="{{ $bookDetail->id }}">

                                                <div class="form-group">
                                                    <label for="rating">Đánh giá (1-5 sao)</label>
                                                    <div class="star-rating">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <input type="radio" id="edit-star{{ $i }}" name="rating" value="{{ $i }}"
                                                                {{ $existingReview->rating == $i ? 'checked' : '' }} required hidden>
                                                            <label for="edit-star{{ $i }}" class="star {{ $existingReview->rating >= $i ? 'selected' : '' }}" data-value="{{ $i }}">
                                                                <i class="fas fa-star"></i>
                                                            </label>
                                                        @endfor
                                                    </div>
                                                </div>

                                                <div class="form-group mt-3">
                                                    <label for="comment">Bình luận</label>
                                                    <textarea name="comment" class="form-control" rows="4" maxlength="1000">{{ $existingReview->comment }}</textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="custom-tab-content" id="shipping">
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
    --radius: 8px;
    --radius-lg: 12px;
}

.book-detail-wrapper {
    max-width: 1100px;
    margin: 32px auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.07);
    padding: 32px 40px;
}

.breadcrumb-custom {
    background: #f5f5f5;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 24px;
    font-weight: 500;
}
.breadcrumb-custom a {
    color: #2c3e50;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: color 0.2s;
}
.breadcrumb-custom a:hover {
    color: #e74c3c;
}
.breadcrumb-custom .divider {
    color: #888;
    font-size: 16px;
    margin: 0 2px;
}
.breadcrumb-custom span:last-child {
    color: #222;
    text-transform: uppercase;
}

.book-detail-flex {
    display: flex;
    gap: 32px;
    align-items: flex-start;
}
.left-image {
    flex: 0 0 350px;
    max-width: 350px;
}
.main-image {
    width: 100%;
    max-width: 350px;
    min-height: 200px;
    background: #f8fafd;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}
.right-info {
    flex: 1;
    min-width: 0;
}
@media (max-width: 991px) {
    .book-detail-flex {
        flex-direction: column;
        gap: 16px;
    }
    .left-image, .right-info {
        max-width: 100%;
        flex: 1 1 100%;
    }
    .book-detail-wrapper {
        padding: 16px 8px;
    }
}
.main-book-image {
    max-width: 100%;
    max-height: 400px;
    width: auto;
    height: auto;
    object-fit: contain;
    display: block;
    margin: 0 auto;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
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
.star-rating label.selected i {
    color: var(--warning-color) !important;
}

.rating-stars i.active, .rating-stars i.rated {
    color: var(--warning-color);
}
.fas.fa-star {
    color: #d1d5db; /* sao xám */
}

.fas.fa-star.rated {
    color: var(--warning-color); /* sao vàng */
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

.custom-tab-btn {
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

.custom-tab-btn:hover {
    color: var(--primary-color);
    background: rgba(37, 99, 235, 0.05);
}

.custom-tab-btn.active {
    color: var(--primary-color);
    background: white;
}

.custom-tab-btn.active::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--primary-color);
}

.custom-tab-content-container {
    padding: 2rem;
}

.custom-tab-content {
    display: none !important;;
}

.custom-tab-content.active {
    display: block !important;;
}

.custom-tab-content h4 {
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

.review-form-section {
    margin-bottom: 2rem;
}

.review-form {
    max-width: 500px;
}

.star-rating {
    display: flex;
    gap: 5px;
}

.star-rating label {
    cursor: pointer;
    font-size: 1.5rem;
    color: #d1d5db;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label,
.star-rating .selected {
    color: var(--warning-color);
}

.review-list {
    display: grid;
    gap: 1.5rem;
}

.review-card {
    border: 1px solid var(--border-color);
    border-radius: var(--radius);
    padding: 1rem;
    background: white;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.review-header strong {
    color: var(--text-primary);
    font-weight: 600;
}

.review-date {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.review-comment {
    color: var(--text-primary);
    line-height: 1.6;
}

/* Modal */
.modal-content {
    border-radius: var(--radius);
}

.modal-header {
    border-bottom: 1px solid var(--border-color);
}

.modal-title {
    color: var(--text-primary);
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
    
    .custom-tab-btn {
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
    
    .custom-tab-btn {
        min-width: 100%;
    }
}

.thumbnails-gallery-horizontal {
    width: 100%;
    margin-top: 8px;
}
.swiper {
    width: 100%;
    padding-bottom: 10px;
}
.swiper-wrapper {
    display: flex;
}
.swiper-slide {
    width: 60px !important;
    display: flex;
    justify-content: center;
}
.thumb-img {
    width: 60px;
    height: 80px;
    object-fit: cover;
    border-radius: 6px;
    border: 2px solid #eee;
    cursor: pointer;
    transition: border 0.2s, box-shadow 0.2s;
}
.thumb-img.active {
    border: 2px solid #e74c3c;
    box-shadow: 0 2px 8px rgba(231,76,60,0.15);
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
    const tabBtns = document.querySelectorAll('.custom-tab-btn');
    const tabContents = document.querySelectorAll('.custom-tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
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

document.addEventListener('DOMContentLoaded', function () {
    function setupStarRating(containerSelector, prefix) {
        const container = document.querySelector(containerSelector);
        if (!container) return;

        const stars = container.querySelectorAll('.star');

        stars.forEach(star => {
            star.addEventListener('click', function () {
                const value = parseInt(this.dataset.value);

                // Cập nhật class selected
                stars.forEach((s, i) => {
                    s.classList.toggle('selected', i < value);
                });

                // Chọn input radio
                const radio = document.getElementById(`${prefix}-star${value}`);
                if (radio) radio.checked = true;
            });

            star.addEventListener('mouseover', function () {
                const value = parseInt(this.dataset.value);
                stars.forEach((s, i) => s.classList.toggle('hovered', i < value));
            });

            star.addEventListener('mouseout', function () {
                stars.forEach(s => s.classList.remove('hovered'));
            });
        });
    }

    setupStarRating('.review-form .star-rating', ''); // Đánh giá mới
    setupStarRating('#editReviewModal .star-rating', 'edit'); // Chỉnh sửa
});

</script>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endsection