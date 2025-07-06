@extends('client.layouts.app')
@section('title', 'Kết quả tìm kiếm: ' . $keyword)

@section('content')
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
                height: 250px;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .product-card:hover img {
                transform: scale(1.03);
            }

            .discount-badge {
                position: absolute;
                top: 10px;
                left: 10px;
                background-color: #dc3545;
                color: white;
                padding: 5px 8px;
                font-size: 0.75rem;
                border-radius: 4px;
                z-index: 1;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
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
            <h4 class="fw-bold mb-4">
                🔍 Kết quả cho: <span class="text-primary">"{{ $keyword }}"</span>
                <span class="text-muted fs-6">({{ $books->total() }} kết quả)</span>
            </h4>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                @forelse($books as $book)
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
                                        <a href="{{ route('product.show', $book->id) }}" class="text-dark text-decoration-none">
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
                                        <a href="{{ route('product.show', $book->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">Không tìm thấy kết quả phù hợp.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $books->appends(request()->all())->links() }}
            </div>
        </div>
    </section>

@endsection
