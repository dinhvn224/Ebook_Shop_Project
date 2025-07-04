@extends('client.layouts.app')
@section('title', 'Danh mục: ' . $category->name)

@section('content')
<div class="container py-5">
    <style>
        .category-card {
            border: none;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s cubic-bezier(.4,2,.6,1), box-shadow 0.3s;
            overflow: hidden;
            background-color: #fff;
        }
        .category-card:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        .category-card img {
            width: 100%;
            max-width: 160px;
            height: auto;
            aspect-ratio: 3/4;
            object-fit: contain;
            background: #fff;
            display: block;
            margin-left: auto;
            margin-right: auto;
            border-radius: 10px;
            transition: transform 0.3s ease;
        }
        .category-card:hover img {
            transform: scale(1.03);
        }

        .category-card .card-title {
            font-size: 0.95rem;
            font-weight: 500;
            color: #212529;
        }

        .category-card .price-text {
            font-weight: 600;
            font-size: 1rem;
            color: #0d6efd;
        }

        .category-card .price-text .old-price {
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
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
                <h3 class="mb-0 fw-bold">
                    Danh mục: {{ $category->name }}
                    <span class="text-muted fw-normal fs-6">({{ $books->total() }} cuốn)</span>
                </h3>
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

            <div class="row">
                @forelse ($books as $book)
                    @php
                        $detail = optional($book->details->first());
                        $mainImage = optional($book->images)->firstWhere('is_main', true)
                            ?? optional($book->images)->where('deleted', 0)->first();
                        $imageUrl = 'client/img/products/noimage.png';
                        if ($mainImage && !empty($mainImage->url)) {
                            $imageUrl = 'storage/' . $mainImage->url;
                        }
                        $hasPromotion = $detail && $detail->promotion_price && $detail->promotion_price < $detail->price;
                    @endphp
                    @if($detail)
                        <div class="col-md-4 col-6 mb-4">
                            <a href="{{ route('books.show', $book->id) }}" class="text-decoration-none text-dark">
                                <div class="card h-100 category-card p-2">
                                    <div class="position-relative text-center">
                                        <img src="{{ asset($imageUrl) }}" alt="{{ $book->name }}" class="card-img-top mx-auto">
                                    </div>
                                    <div class="card-body text-center">
                                        <h6 class="flex-grow-1 fs-6 card-title mb-1" style="font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $book->name }}</h6>
                                        <p class="text-muted small mb-1" style="font-size: 0.95rem;">{{ $book->author->name ?? 'Ẩn danh' }}</p>
                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <p class="price-text mb-0">
                                                @if($hasPromotion)
                                                    {{ number_format($detail->promotion_price, 0, '', '.') }}₫
                                                    <span class="old-price">{{ number_format($detail->price, 0, '', '.') }}₫</span>
                                                    <span class="discount-badge align-middle">-{{ round((($detail->price - $detail->promotion_price) / $detail->price) * 100) }}%</span>
                                                @else
                                                    {{ number_format($detail->price, 0, '', '.') }}₫
                                                @endif
                                            </p>
                                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </a>
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
                <div class="d-flex justify-content-center mt-4">
                    {{ $books->appends(request()->all())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
