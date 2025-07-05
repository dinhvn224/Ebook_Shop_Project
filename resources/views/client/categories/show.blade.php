@extends('client.layouts.app')
@section('title', 'Danh mục: ' . $category->name)

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

        .filter-card {
            top: 80px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>

    <div class="row">
        <!-- Bộ Lọc -->
        <div class="col-lg-3 mb-4">
            <div class="card position-sticky filter-card" style="top: 80px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Bộ Lọc Sản Phẩm</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('category.show', $category->id) }}">
                        {{-- Giá --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Khoảng Giá</label>
                            <select name="price" class="form-select" onchange="this.form.submit()">
                                <option value="">Tất cả</option>
                                <option value="0-100000" {{ request('price') == '0-100000' ? 'selected' : '' }}>Dưới 100.000đ</option>
                                <option value="100000-200000" {{ request('price') == '100000-200000' ? 'selected' : '' }}>100.000 - 200.000đ</option>
                                <option value="200000-Infinity" {{ request('price') == '200000-Infinity' ? 'selected' : '' }}>Trên 200.000đ</option>
                            </select>
                        </div>

                        {{-- Nhà xuất bản --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nhà Xuất Bản</label>
                            <select name="publisher" class="form-select" onchange="this.form.submit()">
                                <option value="">Tất cả</option>
                                @foreach($publishers as $pub)
                                    <option value="{{ $pub->id }}" {{ request('publisher') == $pub->id ? 'selected' : '' }}>
                                        {{ $pub->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Xoá lọc --}}
                        @if(request()->hasAny(['price', 'publisher']))
                            <a href="{{ route('category.show', $category->id) }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-2"></i>Xoá Bộ Lọc
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Danh sách Sách -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="fw-bold fs-2">
                    Danh mục: {{ $category->name }}
                    <span class="text-muted fw-normal fs-6">({{ $books->total() }} cuốn)</span>
                </h2>
                <form method="GET" action="" class="d-flex align-items-center">
                    @foreach(request()->except('sort') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="sort" class="form-select" style="width: auto;" onchange="this.form.submit()">
                        <option value="">Sắp xếp</option>
                        <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Giá thấp → cao</option>
                        <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Giá cao → thấp</option>
                        <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                    </select>
                </form>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                @forelse ($books as $book)
                    @php
                        $detail = optional($book->details->first());
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
                                        <p class="text-muted small mb-2">{{ $book->author->name ?? 'Ẩn danh' }}</p>

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
                            <h5 class="mb-2"><i class="fas fa-box-open me-2 text-warning"></i>Không có sách nào phù hợp.</h5>
                            <p class="text-muted">Hãy thử thay đổi bộ lọc hoặc chọn danh mục khác nhé.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            @if($books->count())
                <div class="d-flex justify-content-center mt-5">
                    {{ $books->appends(request()->all())->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
