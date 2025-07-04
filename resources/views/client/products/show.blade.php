@extends('client.layouts.app')

@section('content')
    <div class="container py-5">
        <style>
            .book-cover {
                border-radius: 10px;
                object-fit: contain;
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
                width: 100%;
                max-width: 260px;
                height: auto;
                aspect-ratio: 3/4;
                background: #fff;
                display: block;
                margin-left: auto;
                margin-right: auto;
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
            }

            .related-product:hover {
                transform: translateY(-6px);
                box-shadow: 0 10px 24px rgba(0, 0, 0, 0.09);
            }

            .related-product img {
                width: 100%;
                height: 200px;
                object-fit: cover;
            }

            .related-title {
                font-weight: 600;
                font-size: 0.95rem;
                margin-bottom: 2px;
                color: #212529;
            }

            .related-author {
                font-size: 0.85rem;
                color: #6c757d;
            }

            .related-price {
                font-size: 1rem;
                font-weight: 600;
                color: #0d6efd;
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
                bottom: 12px;
                right: 12px;
                width: 34px;
                height: 34px;
                background: #0d6efd;
                color: white;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 50%;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                font-size: 0.85rem;
            }

            .tab-content {
                border: 1px solid #dee2e6;
                border-top: none;
                padding: 1.5rem;
                background: #fff;
                border-radius: 0 0 6px 6px;
            }
            .discount-badge {
                display: inline-block;
                margin-left: 8px;
                background-color: #dc3545;
                color: white;
                padding: 2px 10px;
                font-size: 0.75rem;
                min-width: 0;
                text-align: center;
                border-radius: 999px;
                z-index: 1;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
                font-weight: bold;
                letter-spacing: 0.5px;
            }
            .add-btn {
                position: relative;
                width: 34px;
                height: 34px;
                background: #0d6efd;
                color: white;
                display: flex;
                justify-content: center;
                align-items: center;
                border-radius: 50%;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                font-size: 1rem;
                border: none;
                outline: none;
                transition: background 0.2s;
            }
            .add-btn:hover {
                background: #0056b3;
                color: #fff;
            }
        </style>

        {{-- Chi tiết sản phẩm --}}
            <div class="row g-5">
                <div class="col-md-4">
                    @php
                        $mainImage = optional($book->images)->firstWhere('is_main', true)
                            ?? optional($book->images)->where('deleted', 0)->first();

                        $fallbackImage = 'storage/client/img/products/uHSgfoff1LYGatU5hE38DZEA6101DTziZCDqMp2t.png';
                        $noImage = 'client/img/products/noimage.png';

                        $imageUrl = $noImage;

                        if ($mainImage && !empty($mainImage->url)) {
                            $imageUrl = 'storage/' . $mainImage->url;
                        }
                    @endphp

                    <img src="{{ asset($imageUrl) }}" class="book-cover w-100 rounded shadow-sm" alt="{{ $book->name }}">
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
                <div class="row g-4">
                    @foreach($relatedBooks as $item)
                        @php
                            $rel = $item->details->first();
                            $mainImage = optional($item->images)->firstWhere('is_main', true)
                                ?? optional($item->images)->where('deleted', 0)->first();
                            $imageUrl = 'client/img/products/noimage.png';
                            if ($mainImage && !empty($mainImage->url)) {
                                $imageUrl = 'storage/' . $mainImage->url;
                            }
                            $hasPromotion = $rel && $rel->promotion_price && $rel->promotion_price < $rel->price;
                        @endphp

                        <div class="col-md-3 col-6">
                            <div class="related-product position-relative p-1">
                                <a href="{{ route('books.show', $item->id) }}">
                                    <div class="position-relative text-center">
                                        <img src="{{ asset($imageUrl) }}" alt="{{ $item->name }}" style="width:100%;max-width:100px;height:auto;aspect-ratio:3/4;object-fit:contain;background:#fff;border-radius:8px;display:block;margin:auto;">
                                    </div>
                                </a>
                                <div class="p-2">
                                    <p class="related-title mb-1" style="font-weight:bold;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:0.97rem;">{{ $item->name }}</p>
                                    <p class="related-author text-muted small" style="font-size:0.9rem;">{{ $item->author->name ?? 'Ẩn danh' }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="related-price mb-0" style="font-size:0.98rem;">
                                            @if($hasPromotion)
                                                {{ number_format($rel->promotion_price, 0, '', '.') }}₫
                                                <span class="old">{{ number_format($rel->price, 0, '', '.') }}₫</span>
                                                <span class="discount-badge align-middle">-{{ round((($rel->price - $rel->promotion_price) / $rel->price) * 100) }}%</span>
                                            @else
                                                {{ number_format($rel->price, 0, '', '.') }}₫
                                            @endif
                                        </span>
                                        <button class="add-btn" title="Thêm vào giỏ" style="width:28px;height:28px;font-size:0.95rem;">
                                            <i class="fas fa-cart-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection
