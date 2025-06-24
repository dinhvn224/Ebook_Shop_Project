@extends('client.layouts.app')

@section('content')
    <h1>Trang người dùng</h1>
    <p>Xin chào {{ Auth::user()->name }}! (ID: {{ Auth::user()->id }})</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Đăng xuất</button>
    </form>
@endsection
