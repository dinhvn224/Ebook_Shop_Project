@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif


@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>🎟 Tạo mã giảm giá mới</h4>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">← Quay lại danh sách</a>
    </div>

    @include('admin.vouchers._form', ['allProducts' => $allProducts])
@endsection