@extends('client.layouts.app')
@section('title', 'Sách của ' . $author->name)
@section('content')
<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
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

        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="fw-bold fs-2">
                Sách của {{ $author->name }}
                <span class="text-muted fw-normal fs-6">({{ $books->count() }} cuốn)</span>
            </h2>
            <form method="GET" action="" class="d-flex align-items-center gap-3">
                <input type="text" name="search" class="form-control" placeholder="Tìm tên sách..."
                    value="{{ request('search') }}" style="width: 200px;">
                <select name="sort" class="form-select" onchange="this.form.submit()" style="width: auto;">
                    <option value="">Sắp xếp</option>
                    <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Tên A-Z</option>
                    <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Giá thấp → cao</option>
                    <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Giá cao → thấp</option>
                </select>
                <button type="submit" class="btn btn-primary">Lọc</button>
            </form>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
            @forelse($books as $book)
                @php
                    $detail = optional($book->details)->first();
                    $hasPromotion = $detail && $detail->promotion_price && $detail->promotion_price < $detail->price;
                @endphp

                @if($detail)
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
                                        <a href="{{ route('books.show', $book->id) }}"
                                            class="text-dark text-decoration-none">
                                            {{ $book->name }}
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-2">{{ $author->name }}</p>

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
                                        <a href="{{ route('books.show', $book->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12 text-center">
                    <div class="alert alert-light border rounded p-4">
                        <h5 class="mb-2"><i class="fas fa-book-open me-2 text-warning"></i>Tác giả này chưa có sách nào.</h5>
                        <p class="text-muted">Hãy thử tìm kiếm tác giả khác hoặc quay lại trang chủ.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
