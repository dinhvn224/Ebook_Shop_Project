@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Thêm ảnh mới</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.images.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="book_id" class="form-label">Book</label>
            <select name="book_id" id="book_id" class="form-control" required>
                <option value="">-- Chọn --</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ (old('book_id', $bookId ?? '') == $book->id) ? 'selected' : '' }}>
                        {{ $book->id }} - {{ $book->name ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Ảnh</label>
            <input type="file" name="image" id="image" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="caption" class="form-label">Caption</label>
            <input type="text" name="caption" id="caption" class="form-control" value="{{ old('caption') }}">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_main" id="is_main" class="form-check-input" value="1" {{ old('is_main') ? 'checked' : '' }}>
            <label for="is_main" class="form-check-label">Đặt làm ảnh chính</label>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.images.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
