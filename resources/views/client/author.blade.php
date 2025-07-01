@extends('client.layouts.app')
@section('title', 'Tác giả')
@section('content')
<div class="container py-5">
    <h2 class="fw-bold mb-4">Tác giả nổi bật</h2>
    <div class="row row-cols-2 row-cols-md-4 g-4">
        @foreach($authors as $author)
            <div class="col">
                <div class="card text-center book-card p-3">
                    <img src="{{ $author->image ?? 'https://i.pravatar.cc/150?u=author'.$author->id }}" class="rounded-circle mb-3 mx-auto" alt="{{ $author->name }}" style="width: 100px; height: 100px;">
                    <h5 class="card-title">{{ $author->name }}</h5>
                    <a href="{{ route('author.show', $author->id) }}" class="btn btn-sm btn-outline-primary mt-2">Xem sách</a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
