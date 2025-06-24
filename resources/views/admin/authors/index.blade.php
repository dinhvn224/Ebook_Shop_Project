@extends('admin.layouts.app')

@section('content')
    <h1>Danh sách Tác giả</h1>
    <a href="{{ route('admin.authors.create') }}" class="btn btn-primary mb-3">+ Thêm mới</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($authors as $author)
                <tr>
                    <td>{{ $author->id }}</td>
                    <td>{{ $author->name }}</td>
                    <td>{{ $author->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.authors.edit', $author->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('admin.authors.destroy', $author->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Bạn có chắc muốn ẩn tác giả này?')" class="btn btn-sm btn-danger">Ẩn</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $authors->links() }}
@endsection
