@extends('admin.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget">
                <div class="dash-widgetimg">
                    <span><img src="assets/img/icons/dash1.svg" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>$<span class="counters" data-count="307144.00">$307,144.00</span></h5>
                    <h6>Tổng số tiền chi</h6> <!-- Total Receipts -->
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash1">
                <div class="dash-widgetimg">
                    <span><img src="assets/img/icons/dash2.svg" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>$<span class="counters" data-count="4385.00">$4,385.00</span></h5>
                    <h6>Tổng số tiền thu</h6> <!-- Total Receipts -->
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash2">
                <div class="dash-widgetimg">
                    <span><img src="assets/img/icons/dash3.svg" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>$<span class="counters" data-count="385656.50">385,656.50</span></h5>
                    <h6>Tổng doanh thu</h6> <!-- total sales -->
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12">
            <div class="dash-widget dash3">
                <div class="dash-widgetimg">
                    <span><img src="assets/img/icons/dash4.svg" alt="img"></span>
                </div>
                <div class="dash-widgetcontent">
                    <h5>$<span class="counters" data-count="40000.00">400.00</span></h5>
                    <h6>Tổng doanh thu</h6> <!-- total sales -->
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count">
                <div class="dash-counts">
                    <h4>100</h4>
                    <h5>Khách hàng</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="user"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das1">
                <div class="dash-counts">
                    <h4>100</h4>
                    <h5>Nhà cung cấp</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="user-check"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das2">
                <div class="dash-counts">
                    <h4>100</h4>
                    <h5>Hóa đơn mua</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="file-text"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das3">
                <div class="dash-counts">
                    <h4>105</h4>
                    <h5>Hóa đơn bán</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="file"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 col-sm-12 col-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Mua & Bán</h5>
                    <div class="graph-sets">
                        <ul>
                            <li>
                                <span>Bán</span>
                            </li>
                            <li>
                                <span>Mua</span>
                            </li>
                        </ul>
                        <div class="dropdown">
                            <button class="btn btn-white btn-sm dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                2022 <img src="assets/img/icons/dropdown.svg" alt="img" class="ms-2">
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2022</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2021</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2020</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="sales_charts"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-sm-12 col-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Sản phẩm vừa thêm</h4>
                    <div class="dropdown">
                        <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false"
                            class="dropset">
                            <i class="fa fa-ellipsis-v"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li>
                                <a href="productlist.html" class="dropdown-item">Danh sách sản phẩm</a>
                            </li>
                            <li>
                                <a href="addproduct.html" class="dropdown-item">Thêm sản phẩm</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive dataview">
                        <table class="table datatable ">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td class="productimgname">
                                        <a href="productlist.html" class="product-img">
                                            <img src="assets/img/product/product22.jpg" alt="product">
                                        </a>
                                        <a href="productlist.html">Apple Earpods</a>
                                    </td>
                                    <td>$891.2</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td class="productimgname">
                                        <a href="productlist.html" class="product-img">
                                            <img src="assets/img/product/product23.jpg" alt="product">
                                        </a>
                                        <a href="productlist.html">iPhone 11</a>
                                    </td>
                                    <td>$668.51</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td class="productimgname">
                                        <a href="productlist.html" class="product-img">
                                            <img src="assets/img/product/product24.jpg" alt="product">
                                        </a>
                                        <a href="productlist.html">Samsung</a>
                                    </td>
                                    <td>$522.29</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td class="productimgname">
                                        <a href="productlist.html" class="product-img">
                                            <img src="assets/img/product/product6.jpg" alt="product">
                                        </a>
                                        <a href="productlist.html">Macbook Pro</a>
                                    </td>
                                    <td>$291.01</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-0">
        <div class="card-body">
            <h4 class="card-title">Sản phẩm hết hạn</h4>
            <div class="table-responsive dataview">
                <table class="table datatable ">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Thương hiệu</th>
                            <th>Danh mục</th>
                            <th>Ngày hết hạn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><a href="javascript:void(0);">IT0001</a></td>
                            <td class="productimgname">
                                <a class="product-img" href="productlist.html">
                                    <img src="assets/img/product/product2.jpg" alt="product">
                                </a>
                                <a href="productlist.html">Cam</a>
                            </td>
                            <td>N/D</td>
                            <td>Trái cây</td>
                            <td>12-12-2022</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><a href="javascript:void(0);">IT0002</a></td>
                            <td class="productimgname">
                                <a class="product-img" href="productlist.html">
                                    <img src="assets/img/product/product3.jpg" alt="product">
                                </a>
                                <a href="productlist.html">Dứa</a>
                            </td>
                            <td>N/D</td>
                            <td>Trái cây</td>
                            <td>25-11-2022</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td><a href="javascript:void(0);">IT0003</a></td>
                            <td class="productimgname">
                                <a class="product-img" href="productlist.html">
                                    <img src="assets/img/product/product4.jpg" alt="product">
                                </a>
                                <a href="productlist.html">Dâu tây</a>
                            </td>
                            <td>N/D</td>
                            <td>Trái cây</td>
                            <td>19-11-2022</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td><a href="javascript:void(0);">IT0004</a></td>
                            <td class="productimgname">
                                <a class="product-img" href="productlist.html">
                                    <img src="assets/img/product/product5.jpg" alt="product">
                                </a>
                                <a href="productlist.html">Bơ</a>
                            </td>
                            <td>N/D</td>
                            <td>Trái cây</td>
                            <td>20-11-2022</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
