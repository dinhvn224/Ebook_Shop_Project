@extends('client.layouts.app')
@section('title', 'Sách của ' . $author->name)
@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Sách của {{ $author->name }}</h2>
    <div class="row row-cols-2 row-cols-md-4 g-4">
        @forelse($books as $book)
            <div class="col">
                <div class="card book-card h-100">
                    <img src="{{ $book->image_url ?? asset('client/img/products/noimage.png') }}" class="card-img-top book-image" alt="{{ $book->name }}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title flex-grow-1" style="font-size: 0.95rem;">
                            <a href="#" class="text-dark text-decoration-none">{{ $book->name }}</a>
                        </h6>
                        <p class="card-text text-muted small mb-2">{{ $author->name }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <p class="price mb-0" style="font-size: 1.1rem;">
                                {{ number_format($book->price, 0, ',', '.') }}₫
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center my-5">
                <p class="text-muted">Tác giả này chưa có sách nào.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
