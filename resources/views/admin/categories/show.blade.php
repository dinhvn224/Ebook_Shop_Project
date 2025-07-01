@extends('admin.layouts.app') {{-- tùy thuộc vào layout admin anh đang dùng --}}

@section('content')
<div class="container py-4">
    <h3 class="mb-4 fw-bold">Thông tin danh mục: {{ $category->name }}</h3>

    <div class="mb-3">
        <strong>Slug:</strong> {{ $category->slug }}
    </div>

    <div class="mb-3">
        <strong>Icon:</strong> <i class="fas {{ $category->icon ?? 'fa-book' }}"></i>
    </div>

    <div class="mb-3">
        <strong>Danh sách sách thuộc danh mục:</strong>
        <ul>
            @forelse ($category->books as $book)
                <li>{{ $book->name }}</li>
            @empty
                <li>Chưa có sách nào trong danh mục này.</li>
            @endforelse
        </ul>
    </div>

    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">⬅ Quay lại danh sách</a>
</div>
@endsection
