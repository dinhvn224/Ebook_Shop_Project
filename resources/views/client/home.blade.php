@extends('client.layouts.app')

@section('content')
    <div class="banner">
        <div class="owl-carousel owl-theme"></div>
    </div>
    <img src="{{ asset('client/img/banners/blackFriday.gif') }}" alt="" style="width: 100%;">
    <br>
    <div class="companyMenu group flexContain"></div>
    <div class="flexContain">
        <div class="pricesRangeFilter dropdown">
            <button class="dropbtn">Giá tiền</button>
            <div class="dropdown-content"></div>
        </div>
        <div class="promosFilter dropdown">
            <button class="dropbtn">Khuyến mãi</button>
            <div class="dropdown-content"></div>
        </div>
        <div class="starFilter dropdown">
            <button class="dropbtn">Số lượng sao</button>
            <div class="dropdown-content"></div>
        </div>
        <div class="sortFilter dropdown">
            <button class="dropbtn">Sắp xếp</button>
            <div class="dropdown-content"></div>
        </div>
    </div>
    <div class="choosedFilter flexContain">
        <a id="deleteAllFilter" style="display: none;">
            <h3>Xóa bộ lọc</h3>
        </a>
    </div>
    <hr>
    <div class="contain-products" style="display:none">
        <div class="filterName">
            <input type="text" placeholder="Lọc trong trang theo tên..." onkeyup="filterProductsName(this)">
        </div>
        <ul id="products" class="homeproduct group flexContain">
            <div id="khongCoSanPham">
                <i class="fa fa-times-circle"></i>
                Không có sản phẩm nào
            </div>
        </ul>
        <div class="pagination"></div>
    </div>
    <div class="contain-khungSanPham"></div>
    <script>addContainTaiKhoan();</script>
@endsection

@section('scripts')
    <script src="{{ asset('client/js/classes.js') }}"></script>
    <script src="{{ asset('client/js/dungchung.js') }}"></script>
    <script>
       fetch('/api/products')
            .then(res => res.json())
            .then(data => {
                list_products = data;
                console.log('LIST_PRODUCTS:', list_products);
                renderTrangChu();
            });
    </script>
    <script src="{{ asset('client/js/trangchu.js') }}"></script>
@endsection
