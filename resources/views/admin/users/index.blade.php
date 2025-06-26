@extends('admin.layouts.app')

@section('content')
<h1>Danh sách Người Dùng</h1>

<a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Thêm mới người dùng</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Ảnh đại diện</th>
            <th>Ngày sinh</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                @if($user->avatar_url)
                    <img src="{{ asset('storage/' . $user->avatar_url) }}" alt="Avatar" width="50" height="50">
                @else
                    <span>Chưa có ảnh</span>
                @endif
            </td>
            <td>{{ $user->birth_date ? $user->birth_date->format('d-m-Y') : 'Chưa cập nhật' }}</td>
            <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
            <td>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $users->links() }}  <!-- Phân trang -->

@endsection
