@extends('client.layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('client/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/topnav.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/taikhoan.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/trangchu.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/home_products.css') }}">
    <link rel="stylesheet" href="{{ asset('client/css/chitietsanpham.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('client/css/footer.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('client/js/owlcarousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('client/js/owlcarousel/owl.theme.default.min.css') }}">
@endsection

@section('content')
<div class="container" style="max-width:1200px;margin:auto;">
    <section>
        <div id="productNotFound" style="min-height: 50vh; text-align: center; margin: 50px; display: none;">
            <h1 style="color: red; margin-bottom: 10px;">Không tìm thấy sản phẩm</h1>
            <a href="{{ url('/') }}" style="text-decoration: underline;">Quay lại trang chủ</a>
        </div>
        <div class="chitietSanpham" style="margin-bottom: 100px">
            <h1>Sách </h1>
            <div class="rating"></div>
            <div class="rowdetail group">
                <div class="picture">
                    <img src="" onclick="opencertain()">
                </div>
                <div class="price_sale">
                    <div class="area_price"> </div>
                    <div class="ship" style="display: none;">
                        <img src="{{ asset('client/img/chitietsanpham/clock-152067_960_720.png') }}">
                        <div>NHẬN HÀNG TRONG 1 GIỜ</div>
                    </div>
                    <div class="area_promo">
                        <strong>khuyến mãi</strong>
                        <div class="promo">
                            <img src="{{ asset('client/img/chitietsanpham/icon-tick.png') }}">
                            <div id="detailPromo"> </div>
                        </div>
                    </div>
                    <div class="policy">
                        <div>
                            <img src="{{ asset('client/img/chitietsanpham/box.png') }}">
                            <p>Trong hộp có: thẻ sách </p>
                        </div>
                        <div>
                            <img src="{{ asset('client/img/chitietsanpham/icon-baohanh.png') }}">
                            <p>Bảo hành chính hãng 1 tháng.</p>
                        </div>
                        <div class="last">
                            <img src="{{ asset('client/img/chitietsanpham/1-1.jpg') }}">
                            <p>1 đổi 1 trong 1 tháng nếu lỗi, đổi sản phẩm tại nhà trong 1 ngày.</p>
                        </div>
                    </div>
                    <div class="area_order">
                        <a class="buy_now" onclick="themVaoGioHang(maProduct, nameProduct);">
                            <b><i class="fa fa-cart-plus"></i> Thêm vào giỏ hàng</b>
                            <p>Giao trong 1 giờ hoặc nhận tại cửa hàng</p>
                        </a>
                    </div>
                </div>
                <div class="info_product">
                    <h2>Thông tin sách</h2>
                    <ul class="info"></ul>
                </div>
            </div>
            <div id="overlaycertainimg" class="overlaycertainimg">
                <div class="close" onclick="closecertain()">&times;</div>
                <div class="overlaycertainimg-content">
                    <img id="bigimg" class="bigimg" src="">
                    <div class="div_smallimg owl-carousel"></div>
                </div>
            </div>
        </div>
        <div id="goiYSanPham"></div>
    </section>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('client/js/Jquery/Jquery.min.js') }}"></script>
    <script src="{{ asset('client/js/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('client/js/classes.js') }}"></script>
    <script src="{{ asset('client/js/dungchung.js') }}"></script>
    <script src="{{ asset('client/js/chitietsanpham.js') }}"></script>
    <script>
        fetch('/api/products')
            .then(res => res.json())
            .then(data => {
                list_products = data;
                if (typeof initProductDetail === 'function') {
                    initProductDetail();
                }
            });
    </script>
@endsection