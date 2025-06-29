@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Danh sách ảnh sản phẩm</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('admin.images.create') }}" class="btn btn-primary mb-3">Thêm ảnh mới</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Book ID</th>
                <th>Ảnh</th>
                <th>Caption</th>
                <th>Ảnh chính</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($images as $image)
                <tr>
                    <td>{{ $image->id }}</td>
                    <td>{{ $image->book_id }}</td>
                    <td><img src="{{ asset('storage/' . $image->url) }}" alt="Ảnh" width="80"></td>
                    <td>{{ $image->caption }}</td>
                    <td>{!! $image->is_main ? '<span class="badge bg-success">Chính</span>' : '' !!}</td>
                    <td>
                        <a href="{{ route('admin.images.show', $image->id) }}" class="btn btn-info btn-sm">Xem</a>
                        <a href="{{ route('admin.images.edit', $image->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('admin.images.destroy', $image->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $images->links() }}
</div>
@endsection
