@extends('admin.layouts.app')

@section('content')
<h1>Thêm mới Người Dùng</h1>

<!-- Hiển thị các lỗi validation nếu có -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label for="name">Tên người dùng</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
    </div>

    <div class="form-group">
        <label for="password">Mật khẩu</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="password_confirmation">Xác nhận mật khẩu</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="phone_number">Số điện thoại</label>
        <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number') }}">
    </div>

    <div class="form-group">
        <label for="birth_date">Ngày sinh</label>
        <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date') }}">
    </div>

    <div class="form-group">
        <label for="avatar">Ảnh đại diện</label>
        <input type="file" name="avatar" id="avatar" class="form-control-file">
    </div>

    <div class="form-group">
        <label for="role">Vai trò</label>
        <select name="role" id="role" class="form-control" required>
            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
    </div>

    <div class="form-group">
        <label for="is_active">Trạng thái</label>
        <select name="is_active" id="is_active" class="form-control" required>
            <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Thêm người dùng</button>
</form>

@endsection
