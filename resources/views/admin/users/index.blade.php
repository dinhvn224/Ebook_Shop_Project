@extends('admin.layouts.app')

@section('content')
<h1>Danh sách Người Dùng</h1>

<a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Thêm mới người dùng</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Thanh tìm kiếm -->
<div class="mb-3">
    <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2">
        <div class="col-auto">
            <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên hoặc email..."
                   value="{{ request('keyword') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </div>
        @if(request('keyword'))
            <div class="col-auto">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Xóa lọc
                </a>
            </div>
        @endif
    </form>
</div>

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
        @forelse($users as $user)
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
        @empty
        <tr>
            <td colspan="7" class="text-muted text-center py-4">
                <i class="fas fa-user-slash fa-2x mb-2"></i><br>
                Không tìm thấy người dùng nào.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($users->hasPages())
<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
    <div class="text-muted small">
        Hiển thị từ {{ $users->firstItem() ?? 0 }} đến {{ $users->lastItem() ?? 0 }} / {{ $users->total() }} người dùng
    </div>
</div>
@endif

@endsection
