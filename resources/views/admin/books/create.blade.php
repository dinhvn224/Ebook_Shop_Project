@extends('admin.layouts.app')
@section('content')
<div class="container">
    <h1>Thêm Sách Mới</h1>
    <form action="{{ route('admin.books.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Tên sách</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="author_id" class="form-label">Tác giả</label>
            <select name="author_id" class="form-control" required>
                <option value="">-- Chọn tác giả --</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="publisher_id" class="form-label">Nhà xuất bản</label>
            <select name="publisher_id" class="form-control" required>
                <option value="">-- Chọn NXB --</option>
                @foreach($publishers as $publisher)
                    <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>{{ $publisher->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Thể loại</label>
            <select name="category_id" class="form-control" required>
                <option value="">-- Chọn thể loại --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
