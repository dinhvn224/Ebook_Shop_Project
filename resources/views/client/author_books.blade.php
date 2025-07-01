@extends('client.layouts.app')
@section('title', 'Sách của ' . $author->name)
@section('content')
    <div class="container py-5">


        <div class="d-flex justify-content-between align-items-center mb-4">
            <form method="GET" action="" class="d-flex w-100 gap-3">
                <input type="text" name="search" class="form-control" placeholder="Tìm tên sách..."
                    value="{{ request('search') }}">

                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="">Sắp xếp</option>
                    <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Tên A-Z</option>
                    <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Giá thấp → cao</option>
                    <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Giá cao → thấp</option>
                </select>

                <button type="submit" class="btn btn-primary">Lọc</button>
            </form>
        </div>

        <h2 class="fw-bold mb-4">
            Sách của {{ $author->name }}
            <span class="text-muted fw-normal fs-6">({{ $books->count() }} cuốn)</span>
        </h2>

        <div class="row row-cols-2 row-cols-md-4 g-4">
            @forelse($books as $book)
                @php
                    $detail = optional($book->details)->first();
                    $mainImage = optional($book->images)->firstWhere('is_main', true)
                        ?? optional($book->images)->first();

                    $fallbackImage = 'storage/client/img/products/uHSgfoff1LYGatU5hE38DZEA6101DTziZCDqMp2t.png';
                    $noImage = 'client/img/products/noimage.png';

                    $imageUrl = $noImage;
                    if ($mainImage && !empty($mainImage->url)) {
                        $imagePath = public_path($mainImage->url);
                        if (file_exists($imagePath)) {
                            $imageUrl = $mainImage->url;
                        } elseif (file_exists(public_path($fallbackImage))) {
                            $imageUrl = $fallbackImage;
                        }
                    }
                @endphp

                @if($detail)
                    <div class="col">
                        <div class="card book-card h-100">
                            <img src="{{ asset($imageUrl) }}" class="card-img-top book-image" alt="{{ $book->name }}">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title flex-grow-1" style="font-size: 0.95rem;">
                                    <a href="{{ route('books.show', $book->id) }}" class="text-dark text-decoration-none">
                                        {{ $book->name }}
                                    </a>
                                </h6>
                                <p class="card-text text-muted small mb-2">{{ $author->name }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <p class="price mb-0" style="font-size: 1.1rem;">
                                        {{ number_format($detail->promotion_price ?? $detail->price, 0, ',', '.') }}₫
                                        @if($detail->promotion_price && $detail->promotion_price < $detail->price)
                                            <span class="text-muted text-decoration-line-through ms-1" style="font-size: 0.85rem;">
                                                {{ number_format($detail->price, 0, ',', '.') }}₫
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12 text-center my-5">
                    <p class="text-muted">Tác giả này chưa có sách nào.</p>
                </div>
            @endforelse
        </div>

    </div>
@endsection