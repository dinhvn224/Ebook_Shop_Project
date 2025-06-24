<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Trang chủ')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- css --}}
</head>
<body>

    @include('client.layouts.header')

    @yield('content')

    @include('client.layouts.footer')
    {{-- Js --}}
</body>
</html>
