<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <meta name="description" content="POS - Bootstrap Admin Template">
  <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
  <meta name="author" content="Dreamguys - Bootstrap Admin Template">
  <meta name="robots" content="noindex, nofollow">
  <title>Đăng ký - Pos admin template</title>

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
            <img src="{{ asset('assets/img/logo.png') }}" alt="logo">
          </div>
          <div class="login-userheading">
            <h3>Tạo tài khoản</h3>
            <h4>Tiếp tục để sử dụng hệ thống</h4>
          </div>

          {{-- Laravel Register Form --}}
          <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-login">
              <label>Họ và tên</label>
              <div class="form-addons">
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Nhập họ và tên" required>
                <img src="{{ asset('assets/img/icons/users1.svg') }}" alt="user icon">
              </div>
              @error('name')
                <span class="text-danger small">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-login">
              <label>Email</label>
              <div class="form-addons">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Nhập email" required>
                <img src="{{ asset('assets/img/icons/mail.svg') }}" alt="mail icon">
              </div>
              @error('email')
                <span class="text-danger small">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-login">
              <label>Mật khẩu</label>
              <div class="pass-group">
                <input type="password" name="password" class="pass-input" placeholder="Nhập mật khẩu" required>
                <span class="fas toggle-password fa-eye-slash"></span>
              </div>
              @error('password')
                <span class="text-danger small">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-login">
              <label>Nhập lại mật khẩu</label>
              <div class="pass-group">
                <input type="password" name="password_confirmation" class="pass-input" placeholder="Nhập lại mật khẩu" required>
              </div>
            </div>

            <div class="form-login">
              <button type="submit" class="btn btn-login">Đăng ký</button>
            </div>
          </form>

          <div class="signinform text-center">
            <h4>Đã có tài khoản? <a href="{{ route('login') }}" class="hover-a">Đăng nhập</a></h4>
          </div>
        </div>
      </div>

      <div class="login-img">
        <img src="{{ asset('assets/img/login.jpg') }}" alt="img">
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

