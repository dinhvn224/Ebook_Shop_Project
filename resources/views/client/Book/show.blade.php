@extends('client.layouts.app')
@section('title', $book->name)
@section('content')
<div class="container py-5">
    <div class="row g-4">
        @php
            $detail = $book->details->first();
            $img = $detail && $detail->images->first() ? $detail->images->first() : null;
        @endphp
        <div class="col-md-5 text-center">
            <img src="{{ $img ? asset($img->url) : asset('client/img/products/noimage.png') }}" class="img-fluid rounded product-detail-img" alt="{{ $book->name }}">
        </div>
        <div class="col-md-7">
            <h2>{{ $book->name }}</h2>
            <p class="text-muted mb-1">
                <strong>ISBN:</strong> {{ $book->isbn ?? 'Không rõ' }}
            </p>
            <p class="mb-1">
                <strong>Tác giả:</strong>
                @if($book->author)
                    <a href="{{ route('author.show', $book->author->id) }}">{{ $book->author->name }}</a>
                @else
                    <span>Không rõ</span>
                @endif
            </p>
            @if($book->author && $book->author->bio)
                <p class="mb-1"><em>{{ $book->author->bio }}</em></p>
            @endif
            <p class="mb-1">
                <strong>Nhà xuất bản:</strong> {{ $book->publisher->name ?? 'Không rõ' }}
            </p>
            <p class="mb-1">
                <strong>Thể loại:</strong> {{ $book->category->name ?? 'Không rõ' }}
            </p>
            @if($detail)
                <p class="mb-1"><strong>Giá:</strong> {{ number_format($detail->promotion_price && $detail->promotion_price < $detail->price ? $detail->promotion_price : $detail->price, 0, ',', '.') }}₫
                    @if($detail->promotion_price && $detail->promotion_price < $detail->price)
                        <span class="old-price ms-2">{{ number_format($detail->price, 0, ',', '.') }}₫</span>
                    @endif
                </p>
                <p class="mb-1"><strong>Số lượng tồn:</strong> {{ $detail->quantity }}</p>
                <p class="mb-1"><strong>Kích thước:</strong> {{ $detail->size ?? 'Không rõ' }}</p>
                <p class="mb-1"><strong>Năm xuất bản:</strong> {{ $detail->publish_year ?? 'Không rõ' }}</p>
            @endif
            <p class="mt-3">{{ $book->description }}</p>
            <div class="d-flex align-items-center gap-3 mt-4">
                <form action="#" method="POST" class="d-flex align-items-center">
                    <input type="number" name="quantity" value="1" min="1" class="form-control text-center me-2" style="width: 80px;">
                    <button type="submit" class="btn btn-primary btn-lg">Thêm vào giỏ</button>
                </form>
            </div>
        </div>
    </div>
    <div class="mt-5">
        <h4 class="mb-4 fw-bold">Sách liên quan</h4>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($relatedBooks as $rel)
                <div class="col">
                    <div class="card mb-2">
                        <div class="row g-0 align-items-center">
                            <div class="col-4">
                                @php
                                    $relDetail = $rel->details->first();
                                    $relImg = $relDetail && $relDetail->images->first() ? $relDetail->images->first() : null;
                                @endphp
                                <img src="{{ $relImg ? asset($relImg->url) : asset('client/img/products/noimage.png') }}" class="img-fluid" alt="{{ $rel->name }}">
                            </div>
                            <div class="col-8">
                                <div class="card-body p-2">
                                    <a href="{{ route('book.show', $rel->id) }}" class="fw-bold">{{ $rel->name }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
