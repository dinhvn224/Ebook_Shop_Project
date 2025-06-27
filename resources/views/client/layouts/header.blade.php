<div class="header group">
    <div class="logo">
        <a href="{{ route('home') }}">
            <img src="{{ asset('client/img/trangchu.png') }}" alt="Trang chủ Smartphone Store" title="Trang chủ Smartphone Store">
        </a>
    </div> <!-- End Logo -->

    <div class="content">
        <div class="search-header" style="position: relative; left: 162px; top: 1px;">
            <form class="input-search" method="get" action="{{ route('home') }}">
                <div class="autocomplete">
                    <input id="search-box" name="search" autocomplete="off" type="text" placeholder="Nhập từ khóa tìm kiếm...">
                    <button type="submit">
                        <i class="fa fa-search"></i>
                        Tìm kiếm
                    </button>
                </div>
            </form> <!-- End Form search -->
            <div class="tags"></div>
        </div> <!-- End Search header -->

        <div class="tools-member">
            <!-- PHẦN TÀI KHOẢN DÙNG BLADE -->
            <div class="member">
                @if(Auth::check())
                    <a href="#">
                        <i class="fa fa-user"></i>
                        {{ Auth::user()->name }}
                    </a>
                    <div class="menuMember">
                        <a href="{{ route('home.user') }}">Trang người dùng</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" onclick="return confirm('Xác nhận đăng xuất?')">Đăng xuất</button>
                        </form>
                    </div>
                @else
                    <a href="#">
                        <i class="fa fa-user"></i>
                        Tài khoản
                    </a>
                    <div class="menuMember">
                        <a href="{{ route('login') }}">Đăng nhập</a>
                        <a href="{{ route('register') }}">Đăng ký</a>
                    </div>
                @endif
            </div>
            <!-- End Member -->

            <div class="cart">
                <a href="giohang.html">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Giỏ hàng</span>
                    <span class="cart-number"></span>
                </a>
            </div> <!-- End Cart -->

            <!--
            <div class="check-order">
                <a>
                    <i class="fa fa-truck"></i>
                    <span>Đơn hàng</span>
                </a>
            </div>
            -->
        </div><!-- End Tools Member -->
    </div> <!-- End Content -->
</div> <!-- End Header -->