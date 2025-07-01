@extends('admin.layouts.app')

@section('content')
    <h1>Sửa Danh Mục</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="w-50">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục:</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
        </div>
        <button type="submit" class="btn btn-warning">Cập nhật</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
@endsection
