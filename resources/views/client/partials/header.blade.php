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
                <button class="btn btn-outline-primary" onclick="showPage('cart')">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-count" id="cartCount">0</span>
                </button>
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
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Tìm kiếm sách, tác giả..." id="searchInput" onkeyup="handleSearch(event)">
                    <button class="btn btn-outline-primary" onclick="performSearch()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="showPage('home')">Trang Chủ</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Danh Mục</a>
                    <ul class="dropdown-menu">
                        @foreach($categories as $category)
                            <li>
                                <a class="dropdown-item" href="{{ route('category.show', $category->id) }}">{{ $category->name }}</a>
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
    </div>
</nav>
