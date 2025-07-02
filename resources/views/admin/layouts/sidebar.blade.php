<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="active">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        <span>Bảng điều khiển</span>
                    </a>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="fas fa-th-large me-2"></i>
                        <span>Quản lý danh mục</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.categories.index') }}">Danh mục</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="fas fa-book me-2"></i>
                        <span>Quản lý sách</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.books.index') }}">Danh sách sách</a></li>
                        <li><a href="{{ route('admin.books.create') }}">Thêm sách mới</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('admin.images.index') }}">
                        <i class="fas fa-image me-2"></i>
                        <span>Quản lý ảnh</span>
                    </a>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="fas fa-user-edit me-2"></i>
                        <span>Quản lý tác giả</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.authors.index') }}">Danh sách tác giả</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="fas fa-users me-2"></i>
                        <span>Quản lý người dùng</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.users.index') }}">Danh sách người dùng</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="fas fa-industry me-2"></i>
                        <span>Quản lý nhà sản xuất</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.publishers.index') }}">Danh sách nhà sản xuất</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="fas fa-shopping-cart me-2"></i>
                        <span>Quản lý đơn hàng</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.orders.index') }}">Danh sách đơn hàng</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="fas fa-ticket-alt me-2"></i>
                        <span>Quản lý Voucher</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.vouchers.index') }}">Danh sách voucher</a></li>
                        <li><a href="{{ route('admin.vouchers.create') }}">Thêm mới</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('admin.counter.index') }}">
                        <i class="fas fa-cash-register me-2"></i>
                        <span>Đơn hàng tại quầy</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.voucher-products.index') }}">
                        <i class="fas fa-gift me-2"></i>
                        <span>Sản phẩm Voucher</span>
                    </a>
                </li>
            </ul>

        </div>
    </div>
</div>
