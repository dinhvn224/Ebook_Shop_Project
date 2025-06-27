@extends('admin.layouts.app')

@section('content')
    <h1>Danh sách Nhà Sản Xuất</h1>
    <a href="{{ route('admin.publishers.create') }}" class="btn btn-primary mb-3">Thêm mới nhà sản xuất</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Ngày tạo</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($publishers as $publisher)
                <tr>
                    <td>{{ $publisher->id }}</td>
                    <td>{{ $publisher->name }}</td>
                    <td>{{ $publisher->created_at }}</td>
                    <td>
                        <span class="badge {{ $publisher->deleted ? 'bg-secondary' : 'bg-success' }}">
                            {{ $publisher->deleted ? 'Ẩn' : 'Hiển thị' }}
                        </span>
                    </td>
                    <td>
                        @if(!$publisher->deleted)
                            <a href="{{ route('admin.publishers.edit', $publisher->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('admin.publishers.destroy', $publisher->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Bạn có chắc muốn ẩn nhà sản xuất này?')" class="btn btn-sm btn-danger">Xóa</button>
                            </form>
                        @else
                            <form action="{{ route('admin.publishers.restore', $publisher->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" onclick="return confirm('Khôi phục nhà sản xuất này?')" class="btn btn-sm btn-info">Khôi phục</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $publishers->links() }}
@endsection
