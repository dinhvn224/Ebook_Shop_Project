<form method="POST" action="{{ route('register') }}">
    @csrf
    <input name="name" placeholder="Tên">
    <input name="email" placeholder="Email">
    <input name="password" type="password" placeholder="Mật khẩu">
    <input name="password_confirmation" type="password" placeholder="Nhập lại mật khẩu">
    <button type="submit">Đăng ký</button>
</form>
