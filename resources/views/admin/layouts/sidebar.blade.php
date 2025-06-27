        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="active">
                            <a href="index.html"><img src="{{ asset('assets/img/icons/dashboard.svg') }}" alt="img"><span>
                                    Bảng điều khiển</span> </a>
                        </li>
                         <li class="submenu">
                            <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg') }}" alt="img"><span>
                                    Quản lý danh mục</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ route('admin.categories.index') }}">Danh mục</a></li>


                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg') }}" alt="img"><span>
                                    Quản lý sách</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ route('admin.books.index') }}">Danh sách sách</a></li>
                                <li><a href="{{ route('admin.books.create') }}">Thêm sách mới</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg') }}" alt="img"><span>
                                    Quản lý tác giả</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ route('admin.authors.index') }}">Danh sách tác giả</a></li>


                            </ul>
                        </li>
                         <li class="submenu">
                            <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg') }}" alt="img"><span>
                                    Quản lý người dùng</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ route('admin.users.index') }}">Danh sách người dùng</a></li>


                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg') }}" alt="img"><span>
                                    Quản lý nhà sản xuất</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ route('admin.publishers.index') }}">Danh sách nhà sản xuất</a></li>


                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg') }}" alt="img"><span>
                                    Quản lý đánh giá</span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ route('admin.reviews.index') }}">Danh sách đánh giá</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="components.html"><i data-feather="layers"></i><span> Thành phần</span> </a>
                        </li>
                        <li>
                            <a href="blankpage.html"><i data-feather="file"></i><span> Trang trống</span> </a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="alert-octagon"></i> <span> Trang lỗi </span> <span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="error-404.html">Lỗi 404 </a></li>
                                <li><a href="error-500.html">Lỗi 500 </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="box"></i> <span>Elements </span> <span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="sweetalerts.html">Sweet Alerts</a></li>
                                <li><a href="tooltip.html">Tooltip</a></li>
                                <li><a href="popover.html">Popover</a></li>
                                <li><a href="ribbon.html">Ribbon</a></li>
                                <li><a href="clipboard.html">Clipboard</a></li>
                                <li><a href="drag-drop.html">Drag & Drop</a></li>
                                <li><a href="rangeslider.html">Range Slider</a></li>
                                <li><a href="rating.html">Rating</a></li>
                                <li><a href="toastr.html">Toastr</a></li>
                                <li><a href="text-editor.html">Text Editor</a></li>
                                <li><a href="counter.html">Counter</a></li>
                                <li><a href="scrollbar.html">Scrollbar</a></li>
                                <li><a href="spinner.html">Spinner</a></li>
                                <li><a href="notification.html">Notification</a></li>
                                <li><a href="lightbox.html">Lightbox</a></li>
                                <li><a href="stickynote.html">Sticky Note</a></li>
                                <li><a href="timeline.html">Timeline</a></li>
                                <li><a href="form-wizard.html">Form Wizard</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="bar-chart-2"></i> <span> Charts </span> <span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="chart-apex.html">Apex Charts</a></li>
                                <li><a href="chart-js.html">Chart Js</a></li>
                                <li><a href="chart-morris.html">Morris Charts</a></li>
                                <li><a href="chart-flot.html">Flot Charts</a></li>
                                <li><a href="chart-peity.html">Peity Charts</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="award"></i><span> Icons </span> <span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
                                <li><a href="icon-feather.html">Feather Icons</a></li>
                                <li><a href="icon-ionic.html">Ionic Icons</a></li>
                                <li><a href="icon-material.html">Material Icons</a></li>
                                <li><a href="icon-pe7.html">Pe7 Icons</a></li>
                                <li><a href="icon-simpleline.html">Simpleline Icons</a></li>
                                <li><a href="icon-themify.html">Themify Icons</a></li>
                                <li><a href="icon-weather.html">Weather Icons</a></li>
                                <li><a href="icon-typicon.html">Typicon Icons</a></li>
                                <li><a href="icon-flag.html">Flag Icons</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="columns"></i> <span> Forms </span> <span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="form-basic-inputs.html">Basic Inputs </a></li>
                                <li><a href="form-input-groups.html">Input Groups </a></li>
                                <li><a href="form-horizontal.html">Horizontal Form </a></li>
                                <li><a href="form-vertical.html"> Vertical Form </a></li>
                                <li><a href="form-mask.html">Form Mask </a></li>
                                <li><a href="form-validation.html">Form Validation </a></li>
                                <li><a href="form-select2.html">Form Select2 </a></li>
                                <li><a href="form-fileupload.html">File Upload </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><i data-feather="layout"></i> <span> Table </span> <span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="tables-basic.html">Basic Tables </a></li>
                                <li><a href="data-tables.html">Data Table </a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/product.svg') }}" alt="img"><span>
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
                                <li><a href="{{ route('admin.users.create') }}">Người dùng mới </a></li>
                                <li><a href="{{ route('admin.users.index') }}">Danh sách người dùng</a></li>
                            </ul>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"><img src="{{ asset('assets/img/icons/settings.svg') }}" alt="img"><span>
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
