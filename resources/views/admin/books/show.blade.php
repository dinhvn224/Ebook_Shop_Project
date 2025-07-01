@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold">Chi tiết sách: {{ $book->name }}</h3>

    <ul class="list-group mb-4">
        <li class="list-group-item"><strong>Tác giả:</strong> {{ $book->author->name ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Nhà xuất bản:</strong> {{ $book->publisher->name ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Thể loại:</strong> {{ $book->category->name ?? 'N/A' }}</li>
        <li class="list-group-item"><strong>Mô tả:</strong> {{ $book->description ?? 'Không có' }}</li>
    </ul>

    <h5 class="fw-bold">Chi tiết sách:</h5>
    <ul class="list-group">
        @forelse ($book->details as $detail)
            <li class="list-group-item">
                <strong>Năm XB:</strong> {{ $detail->publish_year }},
                <strong>Trang:</strong> {{ $detail->total_pages }},
                <strong>Giá:</strong> {{ number_format($detail->price, 0, '', '.') }} đ,
                <strong>KM:</strong> {{ number_format($detail->promotion_price ?? 0, 0, '', '.') }} đ
            </li>
        @empty
            <li class="list-group-item text-muted">Chưa có chi tiết sách.</li>
        @endforelse
    </ul>

    <a href="{{ route('admin.books.index') }}" class="btn btn-secondary mt-4">⬅ Quay lại danh sách</a>
</div>
@endsection
