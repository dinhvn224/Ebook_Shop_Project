@extends('client.layouts.app')

@section('content')
<div class="container">
    <h2>{{ $bookDetail->name }}</h2>
    <img src="{{ asset('storage/' . $bookDetail->image) }}" alt="{{ $bookDetail->name }}" style="width:200px;height:auto;">
    <p>Giá: {{ number_format($bookDetail->price, 0, ',', '.') }} ₫</p>
    <p>{{ $bookDetail->description }}</p>
</div>
@endsection
