@extends('admin.layouts.app')

@section('content')
<h1>Sửa Người Dùng</h1>

<form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="name">Tên người dùng</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>

    <div class="form-group">
        <label for="phone_number">Số điện thoại</label>
        <input type="text" name="phone_number" id="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}">
    </div>

    <div class="form-group">
        <label for="birth_date">Ngày sinh</label>
        <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}">
    </div>

    <div class="form-group">
        <label for="avatar">Ảnh đại diện</label>
        <input type="file" name="avatar" id="avatar" class="form-control-file">

        @if($user->avatar_url)
            <br>
            <strong>Ảnh hiện tại:</strong>
            <img src="{{ asset('storage/' . $user->avatar_url) }}" alt="Avatar" width="100" height="100">
        @else
            <p>No avatar uploaded</p>
        @endif
    </div>

    <div class="form-group">
        <label for="role">Vai trò</label>
        <select name="role" id="role" class="form-control" required>
            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
    </div>

    <div class="form-group">
        <label for="is_active">Trạng thái</label>
        <select name="is_active" id="is_active" class="form-control" required>
            <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
            <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật người dùng</button>
</form>

@endsection
