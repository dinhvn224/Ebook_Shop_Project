<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Mẫu quản trị Dreams Pos</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.jpg') }}">

<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<style>
    .page-wrapper {
        margin-left: 250px; /* Đảm bảo bằng chiều rộng sidebar */
        min-height: 100vh;
        background: #f8f9fa;
        padding: 30px 20px 20px 20px;
    }
    @media (max-width: 991.98px) {
        .page-wrapper {
            margin-left: 0;
            padding: 15px 5px;
        }
    }
</style>
</head>
<body>
    <div class="main-wrapper">
        {{-- Sidebar --}}
        @include('admin.layouts.sidebar')

        <div class="page-wrapper">
            {{-- Header --}}
            @include('admin.layouts.header')

            {{-- Main Content --}}
            <div class="content" style="margin-top: 30px;">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>

</body>
