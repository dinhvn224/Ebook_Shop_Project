<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Thế giới sách')</title>
    <link rel="shortcut icon" href="{{ asset('client/img/favicon.ico') }}" />

    <!-- Load font awesome icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- owl carousel libraries -->
    <link rel="stylesheet" href="{{ asset('client/js/owlcarousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/js/owlcarousel/owl.theme.default.min.css') }}">
    <script src="{{ asset('client/js/Jquery/Jquery.min.js') }}"></script>
    <script src="{{ asset('client/js/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- our files -->
    <link rel="stylesheet" href="{{ asset('client/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/topnav.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/banner.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/taikhoan.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/trangchu.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/home_products.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/pagination_phantrang.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/footer.css') }}">
    <script>var list_products = [];</script>
    <script src="{{ asset('client/js/classes.js') }}"></script>
    <script src="{{ asset('client/js/dungchung.js') }}"></script>
    <script src="{{ asset('client/js/trangchu.js') }}"></script>
    @yield('head')
</head>
<body>
    <script>addTopNav();</script>
    @include('client.layouts.header')
    @yield('content')
    @include('client.layouts.footer')
    <i class="fa fa-arrow-up" id="goto-top-page" onclick="gotoTop()"></i>
    @yield('scripts')
    <div id="containTaiKhoan"></div>
    <div id="containGioHang"></div>
</body>
</html>
