@extends('admin.layouts.app')

@section('content')
    <h1 class="mb-4">Sửa Nhà Sản Xuất</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.publishers.update', $publisher->id) }}" method="POST" class="w-50">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Tên nhà sản xuất:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $publisher->name) }}" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-warning">Cập nhật</button>
        <a href="{{ route('admin.publishers.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
@endsection
