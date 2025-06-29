@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Chi tiết ảnh</h2>
    <div class="card" style="max-width: 400px;">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $image->id }}</p>
            <p><strong>Book Detail:</strong> {{ $image->book_detail_id }}</p>
            <p><strong>Ảnh:</strong><br>
                <img src="{{ asset('storage/' . $image->url) }}" alt="Ảnh" width="200">
            </p>
            <p><strong>Caption:</strong> {{ $image->caption }}</p>
            <p><strong>Ảnh chính:</strong> {!! $image->is_main ? '<span class="badge bg-success">Chính</span>' : '' !!}</p>
            <p><strong>Ngày tạo:</strong> {{ $image->created_at }}</p>
            <p><strong>Ngày cập nhật:</strong> {{ $image->updated_at }}</p>
            <a href="{{ route('admin.images.edit', $image->id) }}" class="btn btn-warning">Sửa</a>
            <a href="{{ route('admin.images.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>
@endsection
