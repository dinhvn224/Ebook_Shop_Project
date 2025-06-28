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
    <title>@yield('title', 'Admin')</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.jpg') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js for dashboard charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .page-wrapper {
            margin-left: 250px;
            /* Đảm bảo bằng chiều rộng sidebar */
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
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert/sweetalerts.min.js') }}"></script>

    <!-- Scripts pushed from child views -->
    @stack('scripts')
</body>
</html>