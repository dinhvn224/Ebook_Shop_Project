<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Thế giới sách')</title>
    <link rel="shortcut icon" href="{{ asset('client/img/favicon.ico') }}" />

    <!-- Load font awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

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