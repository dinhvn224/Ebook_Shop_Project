@extends('layouts.app')

@section('content')
<h1>Danh sách Tác giả</h1>
<a href="{{ route('admin.authors.create') }}">+ Thêm mới</a>

@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($authors as $author)
        <tr>
            <td>{{ $author->id }}</td>
            <td>{{ $author->name }}</td>
            <td>
                <a href="{{ route('admin.authors.edit', $author->id) }}">Sửa</a>
                <form action="{{ route('admin.authors.destroy', $author->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Bạn có chắc muốn ẩn tác giả này?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $authors->links() }}
@endsection
