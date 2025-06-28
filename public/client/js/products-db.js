// Biến toàn cục để lưu danh sách sản phẩm từ database
var list_products = [];

// Hàm load dữ liệu sản phẩm từ database
function loadProductsFromDB() {
    return fetch('/api/products')
        .then(response => response.json())
        .then(data => {
            list_products = data;
            console.log('Đã load', list_products.length, 'sản phẩm từ database');
            return data;
        })
        .catch(error => {
            console.error('Lỗi khi load sản phẩm:', error);
            // Fallback: sử dụng dữ liệu mặc định nếu có lỗi
            list_products = [];
            return [];
        });
}

// Load dữ liệu khi trang được load
document.addEventListener('DOMContentLoaded', function() {
    loadProductsFromDB();
});

// Hàm tìm kiếm sản phẩm theo mã
function timKiemTheoMa(list, masp) {
    for (var i = 0; i < list.length; i++) {
        if (list[i].masp == masp) {
            return list[i];
        }
    }
    return null;
}

// Hàm chuyển đổi string thành số
function stringToNum(str) {
    return parseInt(str.replace(/[^\d]/g, ''));
}

// Hàm chuyển đổi số thành string có định dạng
function numToString(num) {
    return num.toLocaleString('vi-VN');
}
