@extends('client.layouts.app')

@section('content')
    <div class="container py-5">
        <style>
            .book-cover {
                width: 100%;
                height: 320px;
                object-fit: contain;
                border-radius: 12px;
                background: #fff;
                box-shadow: 0 2px 12px rgba(0,0,0,0.07);
                padding: 16px;
                margin-bottom: 8px;
                display: block;
                transition: transform 0.2s, box-shadow 0.2s;
            }
            .book-cover:hover {
                transform: scale(1.03);
                box-shadow: 0 4px 16px rgba(0,0,0,0.10);
            }
            .book-title {
                font-size: 1.75rem;
                font-weight: 700;
            }

            .book-meta {
                font-size: 0.95rem;
                color: #6c757d;
                margin-bottom: 0.25rem;
            }

            .rating i {
                color: #ffc107;
                font-size: 1rem;
            }

            .price {
                font-size: 1.4rem;
                font-weight: 600;
                color: #0d6efd;
            }

            .price .old {
                font-size: 0.95rem;
                text-decoration: line-through;
                color: #999;
                margin-left: 8px;
            }

            .out-stock {
                color: #dc3545;
                font-weight: 500;
            }

            .desc-text {
                font-size: 0.96rem;
                color: #444;
            }

            .related-product {
                transition: all 0.3s ease;
                border-radius: 10px;
                overflow: hidden;
                background: #fff;
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
                position: relative;
                min-width: 140px;
                max-width: 160px;
                margin: 0 auto;
                padding: 8px 4px;
            }

            .related-product:hover {
                transform: translateY(-6px);
                box-shadow: 0 10px 24px rgba(0, 0, 0, 0.09);
            }

            .related-product img {
                width: 100%;
                height: 120px;
                object-fit: contain;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 2px 8px rgba(0,0,0,0.04);
                padding: 6px;
                transition: transform 0.2s, box-shadow 0.2s;
            }
            .related-product img:hover {
                transform: scale(1.03);
                box-shadow: 0 4px 16px rgba(0,0,0,0.10);
            }

            .related-title {
                font-size: 0.95rem;
                font-weight: 500;
                margin-bottom: 0.25rem;
            }

            .related-author {
                font-size: 0.85rem;
            }

            .related-price {
                font-size: 1rem;
                font-weight: 600;
            }

            .related-price .old {
                font-size: 0.85rem;
                color: #999;
                text-decoration: line-through;
                margin-left: 6px;
            }

            .discount {
                position: absolute;
                top: 10px;
                left: 10px;
                background: #dc3545;
                color: white;
                padding: 2px 8px;
                font-size: 0.75rem;
                border-radius: 4px;
            }

            .add-btn {
                position: absolute;
                bottom: 10px;
                right: 10px;
                z-index: 2;
            }
            .add-btn .btn {
                padding: 4px 8px;
                font-size: 0.9rem;
                border-radius: 50%;
                min-width: 32px;
                min-height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .add-btn .btn i {
                font-size: 1.1rem;
                margin: 0;
            }

            .tab-content {
                border: 1px solid #dee2e6;
                border-top: none;
                padding: 1.5rem;
                background: #fff;
                border-radius: 0 0 6px 6px;
            }
            .row.g-2 {
                --bs-gutter-x: 0.5rem;
                --bs-gutter-y: 0.5rem;
            }
            .related-books-row {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                justify-content: flex-start;
            }
            .related-product {
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 2px 12px rgba(0,0,0,0.07);
                padding: 16px 12px 12px 12px;
                min-width: 160px;
                max-width: 180px;
                flex: 1 1 18%;
                display: flex;
                flex-direction: column;
                align-items: center;
                position: relative;
                margin: 0;
            }
            .related-product .related-img {
                width: 100%;
                height: 180px;
                object-fit: contain;
                border-radius: 8px;
                background: #fff;
                box-shadow: 0 1px 4px rgba(0,0,0,0.04);
                padding: 8px;
                margin-bottom: 8px;
            }
            .related-product .badge-tap {
                position: absolute;
                top: 12px;
                left: 12px;
                background: #222;
                color: #fff;
                font-size: 0.85rem;
                font-weight: 600;
                border-radius: 6px;
                padding: 2px 10px;
                z-index: 2;
                opacity: 0.92;
            }
            .related-product .discount-badge {
                position: absolute;
                top: 12px;
                right: 12px;
                background: #e53935;
                color: #fff;
                font-size: 0.85rem;
                font-weight: 600;
                border-radius: 6px;
                padding: 2px 10px;
                z-index: 2;
                opacity: 0.92;
            }
            .related-title {
                font-size: 1rem;
                font-weight: 600;
                margin-bottom: 0.25rem;
                color: #222;
                text-align: center;
                min-height: 40px;
            }
            .related-author {
                font-size: 0.9rem;
                color: #666;
                text-align: center;
                margin-bottom: 2px;
            }
            .related-price {
                font-size: 1.1rem;
                font-weight: 700;
                color: #e53935;
                margin-bottom: 2px;
                text-align: center;
            }
            .related-price .old {
                text-decoration: line-through;
                font-size: 0.95rem;
                color: #888;
                margin-left: 6px;
                font-weight: 400;
            }
        </style>

        {{-- Chi tiết sản phẩm --}}
            <div class="row g-5">
                <div class="col-md-4">
                    <img src="{{ $book->main_image_url }}" class="book-cover" alt="{{ $book->name }}">
                </div>

                <div class="col-md-8">
                    <h2 class="book-title">{{ $book->name }}</h2>
                    <p class="book-meta"><strong>Tác giả:</strong> {{ $book->author->name ?? 'N/A' }}</p>
                    <p class="book-meta"><strong>Nhà xuất bản:</strong> {{ $book->publisher->name ?? 'N/A' }}</p>

                    <div class="rating mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i
                                class="fas fa-star {{ $i <= round($book->details->first()->reviews->avg('rating') ?? 4) ? '' : 'text-muted' }}"></i>
                        @endfor
                        <span class="ms-1 text-muted">
                            ({{ number_format($book->details->first()->reviews->avg('rating') ?? 4, 1) }}/5)
                        </span>
                    </div>

                    @php $detail = $book->details->first(); @endphp
                    <div class="price mt-3">
                        {{ number_format($detail->promotion_price ?? $detail->price, 0, '', '.') }}₫
                        @if($detail->promotion_price && $detail->promotion_price < $detail->price)
                            <span class="old">{{ number_format($detail->price, 0, '', '.') }}₫</span>
                        @endif
                    </div>

                    <p class="out-stock mt-2">
                        {{ $detail->quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}
                    </p>

                    <div class="d-flex align-items-center gap-3 mt-3">
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" class="form-control" style="width: 90px;">
                            <input type="hidden" name="book_detail_id" value="{{ $detail->id }}">
                            <button type="submit" class="btn btn-primary" {{ $detail->quantity < 1 ? 'disabled' : '' }}>
                                <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ
                            </button>
                        </form>
                    </div>


                </div>
            </div>


        {{-- Tabs --}}
        <div class="mt-5">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc">Mô
                        Tả</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews">Đánh
                        Giá</button></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="desc">
                    {!! $book->description ?? '<p class="text-muted">Chưa có mô tả chi tiết.</p>' !!}
                </div>

                <div class="tab-pane fade" id="reviews">
                    @forelse($detail->reviews->where('status', 'visible') as $review)
                        <div class="mb-4 border-bottom pb-2">
                            <strong>{{ $review->user->name ?? 'Ẩn danh' }}</strong>
                            <span class="text-muted small">({{ $review->created_at->format('d/m/Y') }})</span>
                            <p class="desc-text">{{ $review->comment }}</p>
                            <span class="text-warning">
                                @for ($i = 1; $i <= $review->rating; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                            </span>
                        </div>
                    @empty
                        <p class="text-muted">Chưa có đánh giá nào cho sách này.</p>
                    @endforelse

                    @auth
                        <form action="{{ route('reviews.store') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="book_detail_id" value="{{ $detail->id }}">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Đánh giá của bạn</label>
                                <textarea name="comment" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Chấm sao</label>
                                <select name="rating" class="form-select" required>
                                    <option value="">Chọn số sao</option>
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ $i }} sao</option>
                                    @endfor
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Gửi đánh giá
                            </button>
                        </form>
                    @else
                        <div class="alert alert-light border mt
                                                                                        <div class=" alert alert-light border
                            mt-3">
                            Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để gửi đánh giá của bạn.
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Sản phẩm liên quan --}}
        @if($relatedBooks->count())
            <div class="mt-5">
                <h5 class="fw-bold mb-3">📚 Sách Cùng Thể Loại</h5>
                <div class="related-books-row">
                    @foreach($relatedBooks as $item)
                        @php
                            $rel = $item->details->first();
                            // Lấy tập sách nếu có
                            $tap = $rel && $rel->volume ? 'Tập ' . $rel->volume : null;
                        @endphp
                        <div class="related-product">
                            @if($tap)
                                <span class="badge-tap">{{ $tap }}</span>
                            @endif
                            @if($rel && $rel->promotion_price && $rel->promotion_price < $rel->price)
                                <span class="discount-badge">-{{ round(100 - ($rel->promotion_price / $rel->price) * 100) }}%</span>
                            @endif
                            <a href="{{ route('books.show', $item->id) }}">
                                <img src="{{ $item->main_image_url }}" alt="{{ $item->name }}" class="related-img">
                            </a>
                            <p class="related-title mb-1">{{ $item->name }}</p>
                            <p class="related-author text-muted small">{{ $item->author->name ?? 'Ẩn danh' }}</p>
                            <p class="related-price mb-0">
                                {{ number_format($rel->promotion_price ?? $rel->price, 0, '', '.') }}₫
                                @if($rel->promotion_price && $rel->promotion_price < $rel->price)
                                    <span class="old">{{ number_format($rel->price, 0, '', '.') }}₫</span>
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection
