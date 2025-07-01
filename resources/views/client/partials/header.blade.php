<div class="promotion-banner">
    <i class="fas fa-fire me-2"></i>
    Miễn phí vận chuyển cho đơn hàng từ 200.000đ - Giảm giá 20% cho khách hàng mới!
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-book-open me-2"></i>BookStore
        </a>

        <div class="d-flex align-items-center order-lg-3">
            <div class="position-relative me-3">
                {{-- Laravel sẽ xử lý logic giỏ hàng ở đây, tạm thời dùng onclick --}}
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-primary">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count" id="cartCount">0</span>
                    </button>
                </form>
            </div>

            {{-- Phần menu người dùng sẽ được JavaScript render, hoặc dùng Blade --}}
            <div class="user-menu" id="userMenu">
                {{-- @if(Auth::check()) ... @else ... @endif --}}
            </div>

            <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>


        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="search-box mx-lg-auto my-2 my-lg-0">
                <form action="{{ route('search') }}" method="GET" class="search-box mx-lg-auto my-2 my-lg-0 w-100">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control" placeholder="Tìm kiếm sách, tác giả..."
                            value="{{ request('q') }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

            </div>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/" onclick="showPage('home')">Trang Chủ</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Danh Mục</a>
                    <ul class="dropdown-menu">
                        @foreach($categories as $category)
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('category.show', $category->id) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('authors.index') }}">Tác Giả</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="showPage('contact')">Liên Hệ</a>
                </li>
            </ul>
        </div>

        <div class="user-menu me-2">
            @auth
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="fas fa-id-badge me-1"></i> Thông tin tài khoản
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="">
                                <i class="fas fa-box me-1"></i> Đơn hàng của tôi
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="px-3">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                    <i class="fas fa-sign-out-alt me-1"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-user me-1"></i> Đăng nhập
                </a>
            @endauth
        </div>



    </div>
</nav>