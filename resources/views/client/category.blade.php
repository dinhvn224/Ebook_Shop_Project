@extends('client.layouts.app')
@section('title', 'Danh mục: ' . $category->name)
@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Danh mục: {{ $category->name }}</h2>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
        @forelse($books as $book)
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
        @empty
            <div class="col-12 text-center text-muted">Không có sách nào trong danh mục này.</div>
        @endforelse
    </div>
</div>
@endsection
