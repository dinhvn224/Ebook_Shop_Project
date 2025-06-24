<<<<<<< HEAD
@extends('layouts.app')
=======
@extends('admin.layouts.app')
>>>>>>> origin/pham-tien-duc

@section('content')
<h1>Thêm Tác giả</h1>

<<<<<<< HEAD
{{-- Hiển thị thông báo --}}
@if(session('success'))
    <div style="color: green;">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div style="color: red;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
=======
@if($errors->any())
    <div style="color: red;">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
>>>>>>> origin/pham-tien-duc
    </div>
@endif

<form action="{{ route('admin.authors.store') }}" method="POST">
    @csrf
    <label for="name">Tên:</label>
    <input type="text" name="name" value="{{ old('name') }}" required>
    <button type="submit">Lưu</button>
</form>
@endsection
