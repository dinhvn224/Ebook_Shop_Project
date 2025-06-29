@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Sửa ảnh</h2>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.images.update', $image->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="book_id" class="form-label">Book</label>
            <select name="book_id" id="book_id" class="form-control" required>
                <option value="">-- Chọn --</option>
                @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ (old('book_id', $image->book_id) == $book->id) ? 'selected' : '' }}>
                        {{ $book->id }} - {{ $book->name ?? '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Ảnh hiện tại:</label><br>
            <img src="{{ asset('storage/' . $image->url) }}" alt="Ảnh hiện tại" width="120">
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Chọn ảnh mới (nếu muốn thay)</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <div class="mb-3">
            <label for="caption" class="form-label">Caption</label>
            <input type="text" name="caption" id="caption" class="form-control" value="{{ old('caption', $image->caption) }}">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_main" id="is_main" class="form-check-input" value="1" {{ $image->is_main ? 'checked' : '' }}>
            <label for="is_main" class="form-check-label">Đặt làm ảnh chính</label>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.images.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
