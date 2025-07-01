@extends('client.layouts.app')

@section('content')

{{-- Nhúng Bootstrap và Icons nếu layout gốc chưa có --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <h3 class="mb-4 text-center">Thông tin cá nhân</h3>

            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body text-center p-4">

                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $user->avatar_url) }}" alt="Avatar"
                             class="rounded-circle shadow" width="120" height="120">
                    </div>

                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    <ul class="list-group list-group-flush text-start mb-3">
                        <li class="list-group-item"><strong>Số điện thoại:</strong> {{ $user->phone_number ?? '—' }}</li>
                        <li class="list-group-item"><strong>Địa chỉ:</strong> {{ $user->address ?? '—' }}</li>
                        <li class="list-group-item"><strong>Ngày sinh:</strong> {{ $user->birth_date ? $user->birth_date->format('d/m/Y') : '—' }}</li>
                    </ul>

                    <a href="{{ route('profile.edit') }}" class="btn btn-primary px-4 mt-3 rounded-pill">
                        <i class="bi bi-pencil-square me-1"></i> Chỉnh sửa
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection
