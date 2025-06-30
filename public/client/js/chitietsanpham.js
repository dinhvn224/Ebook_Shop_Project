// Tên sản phẩm, mã sản phẩm, sản phẩm hiện tại (biến toàn cục)
var nameProduct, maProduct, sanPhamHienTai;

// Hàm khởi tạo trang chi tiết sản phẩm
function initProductDetail() {
    khoiTao();
    phanTich_URL_chiTietSanPham();
    // autocomplete cho khung tim kiem
    autocomplete(document.getElementById('search-box'), list_products);
    // Thêm gợi ý sản phẩm
    sanPhamHienTai && suggestion();
}

function khongTimThaySanPham() {
    document.getElementById('productNotFound').style.display = 'block';
    document.getElementsByClassName('chitietSanpham')[0].style.display = 'none';
}

function phanTich_URL_chiTietSanPham() {
    // Lấy ID từ URL thay vì tên sản phẩm
    const urlParts = window.location.pathname.split('/');
    const productId = urlParts[urlParts.length - 1];
    if(!productId) return khongTimThaySanPham();

    // Tìm sản phẩm theo book_id (hoặc id, masp)
    sanPhamHienTai = list_products.find(p =>
        String(p.book_id || p.id || p.masp) === String(productId)
    );
    if(!sanPhamHienTai) return khongTimThaySanPham();

    nameProduct = sanPhamHienTai.name || sanPhamHienTai.title || 'Sản phẩm không tên';
    maProduct = sanPhamHienTai.book_id || sanPhamHienTai.id || sanPhamHienTai.masp;

    var divChiTiet = document.getElementsByClassName('chitietSanpham')[0];
    // Đổi title
    document.title = nameProduct + ' - Thế giới sách';
    // Cập nhật tên h1
    var h1 = divChiTiet.getElementsByTagName('h1')[0];
    h1.innerHTML += nameProduct;
    // Cập nhật sao
    var rating = "";
    if (sanPhamHienTai.rateCount && sanPhamHienTai.rateCount > 0) {
        for (var i = 1; i <= 5; i++) {
            if (i <= (sanPhamHienTai.star || 0)) {
                rating += `<i class="fa fa-star"></i>`
            } else {
                rating += `<i class="fa fa-star-o"></i>`
            }
        }
        rating += `<span> ` + sanPhamHienTai.rateCount + ` đánh giá</span>`;
    }
    divChiTiet.getElementsByClassName('rating')[0].innerHTML += rating;
    // Cập nhật giá + label khuyến mãi
    var price = divChiTiet.getElementsByClassName('area_price')[0];
    if (sanPhamHienTai.promo && sanPhamHienTai.promo.name && sanPhamHienTai.promo.name != 'giareonline') {
        price.innerHTML = `<strong>` + sanPhamHienTai.price + `₫</strong>`;
        price.innerHTML += new Promo(sanPhamHienTai.promo.name, sanPhamHienTai.promo.value).toWeb();
    } else if (sanPhamHienTai.promo && sanPhamHienTai.promo.name == 'giareonline') {
        document.getElementsByClassName('ship')[0].style.display = ''; // hiển thị 'giao hàng trong 1 giờ'
        price.innerHTML = `<strong>` + sanPhamHienTai.promo.value + `&#8363;</strong>
                            <span>` + sanPhamHienTai.price + `&#8363;</span>`;
    } else {
        // Trường hợp không có khuyến mãi
        price.innerHTML = `<strong>` + sanPhamHienTai.price + `₫</strong>`;
    }
    // Cập nhật chi tiết khuyến mãi
    document.getElementById('detailPromo').innerHTML = getDetailPromo(sanPhamHienTai);
    // Cập nhật thông số
    var info = document.getElementsByClassName('info')[0];
    var s = '';
    if (sanPhamHienTai.detail) {
        s = addThongSo('Tác Giả', sanPhamHienTai.detail.tacgia || 'Chưa có thông tin');
        s += addThongSo('xuất sứ', sanPhamHienTai.detail.xuatsu || 'Chưa có thông tin');
    } else {
        s = addThongSo('Tác Giả', 'Chưa có thông tin');
        s += addThongSo('xuất sứ', 'Chưa có thông tin');
    }
    info.innerHTML = s;
    // Cập nhật hình
    var hinh = divChiTiet.getElementsByClassName('picture')[0];
    hinh = hinh.getElementsByTagName('img')[0];
    if (sanPhamHienTai.img) {
        hinh.src = sanPhamHienTai.img;
        document.getElementById('bigimg').src = sanPhamHienTai.img;
    } else {
        hinh.src = 'img/product/noimage.png';
        document.getElementById('bigimg').src = 'img/product/noimage.png';
    }
    // Khởi động thư viện hỗ trợ banner - chỉ chạy sau khi tạo xong hình nhỏ
    var owl = $('.owl-carousel');
    if (owl.length > 0) {
        owl.owlCarousel({
            items: 5,
            center: true,
            smartSpeed: 450,
        });
    }
    // Nút thêm vào giỏ hàng
    document.querySelector('.buy_now').onclick = function() {
        themVaoGioHang(sanPhamHienTai.book_id || sanPhamHienTai.id || sanPhamHienTai.masp, nameProduct);
    };
}

// Chi tiết khuyến mãi
function getDetailPromo(sp) {
    if (!sp.promo || !sp.promo.name) return '';
    switch (sp.promo.name) {
        case 'giamgia':
            var span = `<span style="font-weight: bold">` + sp.promo.value + `</span>`;
            return `Khách hàng sẽ được giảm ` + span + `₫ khi tới mua trực tiếp tại cửa hàng`;
        case 'moiramat':
            return `Khách hàng được đọc thử tại cửa hàng.`;
        case 'giareonline':
            var del = stringToNum(sp.price) - stringToNum(sp.promo.value);
            var span = `<span style="font-weight: bold">` + numToString(del) + `</span>`;
            return `Sản phẩm sẽ được giảm ` + span + `₫ khi mua hàng online bằng thẻ VPBank hoặc tin nhắn SMS`;
        default:
            return '';
    }
}

function addThongSo(ten, giatri) {
    return `<li>
                <p>` + ten + `</p>
                <div>` + giatri + `</div>
            </li>`;
}

// add hình
function addSmallImg(img) {
    var newDiv = `<div class='item'>
                        <a>
                            <img src=` + img + ` onclick="changepic(this.src)">
                        </a>
                    </div>`;
    var banner = document.getElementsByClassName('owl-carousel')[0];
    banner.innerHTML += newDiv;
}

// đóng mở xem hình
function opencertain() {
    document.getElementById("overlaycertainimg").style.transform = "scale(1)";
}

function closecertain() {
    document.getElementById("overlaycertainimg").style.transform = "scale(0)";
}

// đổi hình trong chế độ xem hình
function changepic(src) {
    document.getElementById("bigimg").src = src;
}

// Thêm sản phẩm vào các khung sản phẩm
function addKhungSanPham(list_sanpham, tenKhung, color, ele) {
    // convert color to code
    var gradient = `background-image: linear-gradient(120deg, ` + color[0] + ` 0%, ` + color[1] + ` 50%, ` + color[0] + ` 100%);`
    var borderColor = `border-color: ` + color[0];
    var borderA = `    border-left: 2px solid ` + color[0] + `;
                    border-right: 2px solid ` + color[0] + `;`;
    // mở tag
    var s = `<div class="khungSanPham" style="` + borderColor + `">
                <h3 class="tenKhung" style="` + gradient + `">* ` + tenKhung + ` *</h3>
                <div class="listSpTrongKhung flexContain">`;
    for (var i = 0; i < list_sanpham.length; i++) {
        s += addProduct(list_sanpham[i], null, true);
        // truyền vào 'true' để trả về chuỗi rồi gán vào s
    }
    // thêm khung vào contain-khung
    ele.innerHTML += s;
}

// Gợi ý sản phẩm
function suggestion(){
    // Lấy ra thông tin sản phẩm hiện tại
    const giaSanPhamHienTai = stringToNum(sanPhamHienTai.price);
    // Tìm các sản phẩm tương tự theo tiêu chí
    const sanPhamTuongTu = list_products
        // Lọc sản phẩm trùng
        .filter((_) => _.masp !== sanPhamHienTai.masp && _.masp && sanPhamHienTai.masp)
        // Tính điểm cho từng sản phẩm
        .map(sanPham => {
            // Tiêu chí 1: giá sản phẩm ko lệch nhau quá 1 triệu
            const giaSanPham = stringToNum(sanPham.price);
            let giaTienGanGiong = Math.abs(giaSanPham - giaSanPhamHienTai) < 1000000;
            // Tiêu chí 2: các thông số kỹ thuật giống nhau
            let soLuongChiTietGiongNhau = 0;
            if (sanPham.detail && sanPhamHienTai.detail) {
                for(let key in sanPham.detail) {
                    let value = sanPham.detail[key];
                    let currentValue = sanPhamHienTai.detail[key];
                    if(value == currentValue) soLuongChiTietGiongNhau++;
                }
            }
            let giongThongSoKyThuat  = soLuongChiTietGiongNhau >= 3;
            // Tiêu chí 3: cùng hãng sản xuất
            let cungHangSanXuat = (sanPham.company && sanPhamHienTai.company) ?
                (sanPham.company === sanPhamHienTai.company) : false;
            // Tiêu chí 4: cùng loại khuyến mãi
            let cungLoaiKhuyenMai = (sanPham.promo && sanPhamHienTai.promo) ?
                (sanPham.promo.name === sanPhamHienTai.promo.name) : false;
            // Tiêu chí 5: có đánh giá, số sao
            let soDanhGia = Number.parseInt(sanPham.rateCount || 0, 10)
            let soSao = Number.parseInt(sanPham.star || 0, 10);
            // Tính điểm cho sản phẩm này
            let diem = 0;
            if(giaTienGanGiong) diem += 20;
            if(giongThongSoKyThuat) diem += soLuongChiTietGiongNhau;
            if(cungHangSanXuat) diem += 15;
            if(cungLoaiKhuyenMai) diem += 10;
            if(soDanhGia > 0) diem += (soDanhGia + '').length;
            diem += soSao;
            // Thêm thuộc tính diem vào dữ liệu trả về
            return {
                ...sanPham,
                diem: diem
            };
        })
        // Sắp xếp theo số điểm cao xuống thấp
        .sort((a,b) => b.diem - a.diem)
        // Lấy ra 10 sản phẩm đầu tiên
        .slice(0, 10);
    if(sanPhamTuongTu.length) {
        let div = document.getElementById('goiYSanPham');
        addKhungSanPham(sanPhamTuongTu, 'Bạn có thể thích', ['#434aa8', '#ec1f1f'], div);
    }
}
