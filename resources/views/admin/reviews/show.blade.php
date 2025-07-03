@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h3>🗨 Chi tiết bình luận #{{ $review->id }}</h3>

    <div class="card my-3">
        <div class="card-body">
            <p><strong>Người dùng:</strong> {{ $review->user->name ?? 'Ẩn danh' }}</p>
            <p><strong>Sản phẩm:</strong> {{ $review->bookDetail->book->name ?? 'Không rõ' }}</p>
            <p><strong>Rating:</strong> {{ $review->rating }} ⭐</p>
            <p><strong>Nội dung:</strong><br>{{ $review->comment }}</p>
            <p><strong>Ngày tạo:</strong> {{ $review->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.reviews.reply', $review->id) }}">
        @csrf
        <div class="form-group">
            <label for="admin_reply">✍️ Phản hồi của quản trị viên:</label>
            <textarea name="admin_reply" id="admin_reply" rows="5" class="form-control" required>{{ old('admin_reply', $review->admin_reply) }}</textarea>
        </div>
        <button class="btn btn-primary mt-2">Gửi phản hồi</button>
    </form>

    @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif
</div>
@endsection
