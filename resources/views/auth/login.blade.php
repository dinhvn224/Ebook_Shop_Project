<form method="POST" action="{{ route('login') }}">
    @csrf
    <input name="email" placeholder="Email">
    <input name="password" type="password" placeholder="Mật khẩu">
    <button type="submit">Đăng nhập</button>
</form>
