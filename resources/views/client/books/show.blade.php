@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $book->name }}</h1>
    <p><strong>Tác giả:</strong> {{ $book->author->name ?? 'Không rõ' }}</p>
    <p><strong>Thể loại:</strong> {{ $book->category->name ?? 'Không rõ' }}</p>
    <p><strong>Nhà xuất bản:</strong> {{ $book->publisher->name ?? 'Không rõ' }}</p>

    <hr>

    <h4>Sách liên quan</h4>
    <ul>
        @foreach($relatedBooks as $related)
            <li>
                <a href="{{ route('client.books.show', $related->id) }}">{{ $related->name }}</a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
