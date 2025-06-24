@extends('admin.layouts.app')

@section('content')
<h1>Thêm Tác giả</h1>

@if($errors->any())
    <div style="color: red;">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('admin.authors.store') }}" method="POST">
    @csrf
    <label for="name">Tên:</label>
    <input type="text" name="name" value="{{ old('name') }}" required>
    <button type="submit">Lưu</button>
</form>
@endsection
