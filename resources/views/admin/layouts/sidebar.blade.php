<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="active">
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('assets/img/icons/dashboard.svg') }}" alt="img">
                        <span>Bảng điều khiển</span>
                    </a>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg') }}"
                            alt="img"><span>
                            Quản lý danh mục</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('admin.categories.index') }}">Danh mục</a></li>


                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg') }}"
                            alt="img"><span>
                            Quản lý sách</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('admin.books.index') }}">Danh sách sách</a></li>
                        <li><a href="{{ route('admin.books.create') }}">Thêm sách mới</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg') }}"
                            alt="img"><span>
                            Quản lý tác giả</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('admin.authors.index') }}">Danh sách tác giả</a></li>


                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg') }}"
                            alt="img"><span>
                            Quản lý người dùng</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{ route('admin.users.index') }}">Danh sách người dùng</a></li>


                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/img/icons/product.svg') }}" alt="icon">
                        <span>Quản lý nhà sản xuất</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul>
                        <li><a href="{{ route('admin.publishers.index') }}">Danh sách nhà sản xuất</a></li>


                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/img/icons/order.svg') }}" alt="img">
                        <span>Quản lý đơn hàng</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.orders.index') }}">Danh sách đơn hàng</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);">
                        <img src="{{ asset('assets/img/icons/discount.svg') }}" alt="icon">
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
                        <img src="{{ asset('assets/img/icons/pos.svg') }}" alt="icon">
                        <span>Đơn hàng tại quầy</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.voucher-products.index') }}">
                        <img src="{{ asset('assets/img/icons/gift.svg') }}" alt="icon">
                        <span>Sản phẩm Voucher</span>
                    </a>
                </li>



                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg') }}"
                            alt="img"><span>
                            Ứng dụng</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="chat.html">Chat</a></li>
                        <li><a href="calendar.html">Lịch</a></li>
                        <li><a href="email.html">Email</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/users1.svg') }}" alt="img"><span>
                            Người dùng</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="newuser.html">Người dùng mới </a></li>
                        <li><a href="userlists.html">Danh sách người dùng</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/settings.svg') }}"
                            alt="img"><span>
                            Cài đặt</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="generalsettings.html">Cài đặt chung</a></li>
                        <li><a href="emailsettings.html">Cài đặt email</a></li>
                        <li><a href="paymentsettings.html">Cài đặt thanh toán</a></li>
                        <li><a href="currencysettings.html">Cài đặt tiền tệ</a></li>
                        <li><a href="grouppermissions.html">Quyền nhóm</a></li>
                        <li><a href="taxrates.html">Thuế suất</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>