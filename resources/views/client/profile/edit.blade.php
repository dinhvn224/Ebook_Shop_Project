@extends('client.layouts.app')

@section('content')

{{-- Nếu layout gốc chưa có Bootstrap thì có thể nhúng ở đây --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <h3 class="mb-4 text-center">Chỉnh sửa thông tin</h3>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="card shadow-lg border-0 rounded-4 p-4">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Họ tên</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ngày sinh</label>
                    <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Ảnh đại diện</label>
                    <input type="file" name="avatar" class="form-control">
                    @if($user->avatar_url)
                        <img src="{{ asset('storage/' . $user->avatar_url) }}" alt="Avatar" class="mt-2 rounded-circle shadow-sm" width="80">
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Mật khẩu mới (nếu đổi)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Xác nhận mật khẩu mới</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left-circle me-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save me-1"></i> Cập nhật
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection
