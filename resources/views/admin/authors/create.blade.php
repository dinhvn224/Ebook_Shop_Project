@extends('admin.layouts.app')

@section('content')
<h1>Thêm Tác giả</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('admin.authors.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Tên tác giả:</label>
        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Lưu</button>
</form>
@endsection
