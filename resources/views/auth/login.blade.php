<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Login - Pos admin template</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.jpg') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="account-page">

    <div class="main-wrapper">
        <div class="account-content">
            <div class="login-wrapper">
                <div class="login-content">
                    <div class="login-userset">
                        <div class="login-logo">
                            <img src="{{ asset('client/img/trangchu.png') }}" alt="logo">
                        </div>
                        <div class="login-userheading">
                            <h3>Đăng nhập</h3>
                            <h4>Vui lòng đăng nhập để tiếp tục</h4>
                        </div>

                        {{-- Laravel Login Form --}}
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-login">
                                <label>Email</label>
                                <div class="form-addons">
                                    <input name="email" type="email" value="{{ old('email') }}" required autofocus
                                        placeholder="Nhập email của bạn">
                                    <img src="{{ asset('assets/img/icons/mail.svg') }}" alt="email icon">
                                </div>
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-login">
                                <label>Mật khẩu</label>
                                <div class="pass-group">
                                    <input name="password" type="password" class="pass-input" required
                                        placeholder="Nhập mật khẩu">
                                    <span class="fas toggle-password fa-eye-slash"></span>
                                </div>
                                @error('password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-login">
                                <div class="alreadyuser">
                                    <h4><a href="#" class="hover-a">Quên mật khẩu?</a></h4>
                                </div>
                            </div>

                            <div class="form-login">
                                <button type="submit" class="btn btn-login">Đăng nhập</button>
                            </div>
                        </form>

                        <div class="signinform text-center">
                            <h4>Chưa có tài khoản? <a href="{{ route('register') }}" class="hover-a">Đăng ký</a></h4>
                        </div>
                    </div>
                </div>

                <div class="login-img">
                    <img src="{{ asset('assets/img/login.jpg') }}" alt="Login image">
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>

</html>