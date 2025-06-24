@extends('admin.layouts.app')

@section('content')
<h1>Sửa Tác giả</h1>

@if($errors->any())
    <div style="color: red;">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('admin.authors.update', $author->id) }}" method="POST">
    @csrf
    @method('PUT')
    <label for="name">Tên:</label>
    <input type="text" name="name" value="{{ old('name', $author->name) }}" required>
    <button type="submit">Cập nhật</button>
</form>
@endsection
