<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản trị - @yield('title', 'Trang quản trị')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .alert-success {
            background-color: #e0f9e0;
            color: #2e7d32;
            padding: 10px;
            border: 1px solid #a5d6a7;
            margin-bottom: 15px;
        }

        .alert-error {
            background-color: #fdecea;
            color: #c62828;
            padding: 10px;
            border: 1px solid #f5c6cb;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 8px;
        }

        a {
            margin-right: 10px;
        }

        form {
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Thông báo thành công --}}
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Thông báo lỗi session --}}
        @if(session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        {{-- Thông báo lỗi validate --}}
        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Nội dung cụ thể của từng trang --}}
        @yield('content')
    </div>
</body>
</html>
