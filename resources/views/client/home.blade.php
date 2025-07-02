@extends('client.layouts.app')

@section('content')
@php use Illuminate\Support\Str; @endphp
<div class="homepage-container">
    <!-- Hero Banner Section -->
    <section class="hero-banner">
        <div class="banner-carousel">
            <div class="owl-carousel owl-theme">
                <!-- Carousel items will be loaded here -->
            </div>
        </div>
        <div class="promotional-banner">
            <img src="{{ asset('client/img/banners/blackFriday.gif') }}" alt="Black Friday Sale" class="promo-image">
        </div>
    </section>

    <!-- Category Menu -->
    <section class="category-section">
        <div class="container">
            <h2 class="section-title">Danh mục sách</h2>
            <div class="category-menu">
                <!-- Categories will be populated here -->
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <div class="filter-bar">
                <div class="filter-group">
                    <div class="filter-dropdown">
                        <button class="filter-btn" data-filter="price">
                            <i class="fas fa-dollar-sign"></i>
                            <span>Khoảng giá</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <div class="price-range">
                                <label>Từ: <input type="number" placeholder="0" class="price-input"></label>
                                <label>Đến: <input type="number" placeholder="1000000" class="price-input"></label>
                                <button class="apply-btn">Áp dụng</button>
                            </div>
                        </div>
                    </div>

                    <div class="filter-dropdown">
                        <button class="filter-btn" data-filter="promotion">
                            <i class="fas fa-tags"></i>
                            <span>Khuyến mãi</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <div class="filter-options">
                                <label><input type="checkbox" value="sale"> Đang giảm giá</label>
                                <label><input type="checkbox" value="new"> Sách mới</label>
                                <label><input type="checkbox" value="bestseller"> Bán chạy</label>
                            </div>
                        </div>
                    </div>

                    <div class="filter-dropdown">
                        <button class="filter-btn" data-filter="rating">
                            <i class="fas fa-star"></i>
                            <span>Đánh giá</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <div class="rating-options">
                                <label><input type="radio" name="rating" value="5"> 5 sao</label>
                                <label><input type="radio" name="rating" value="4"> 4 sao trở lên</label>
                                <label><input type="radio" name="rating" value="3"> 3 sao trở lên</label>
                            </div>
                        </div>
                    </div>

                    <div class="filter-dropdown">
                        <button class="filter-btn" data-filter="sort">
                            <i class="fas fa-sort"></i>
                            <span>Sắp xếp</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <div class="sort-options">
                                <label><input type="radio" name="sort" value="newest"> Mới nhất</label>
                                <label><input type="radio" name="sort" value="price-low"> Giá thấp đến cao</label>
                                <label><input type="radio" name="sort" value="price-high"> Giá cao đến thấp</label>
                                <label><input type="radio" name="sort" value="popular"> Phổ biến</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="search-filter">
                    <div class="search-box">
                        <input type="text" placeholder="Tìm kiếm sách..." class="search-input" onkeyup="filterProductsName(this)">
                        <button class="search-btn"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

            <!-- Active Filters Display -->
            <div class="active-filters">
                <div class="filter-tags">
                    <!-- Active filter tags will appear here -->
                </div>
                <button class="clear-all-btn" id="deleteAllFilter">
                    <i class="fas fa-times"></i>
                    Xóa tất cả bộ lọc
                </button>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Sách nổi bật</h2>
                <div class="view-options">
                    <button class="view-btn active" data-view="grid"><i class="fas fa-th"></i></button>
                    <button class="view-btn" data-view="list"><i class="fas fa-list"></i></button>
                </div>
            </div>

            <div class="products-grid" id="products">
                @forelse ($books as $book)
                    @foreach ($book->details as $detail)
                        <div class="product-card" data-book-id="{{ $book->id }}" data-price="{{ $detail->promotion_price && $detail->promotion_price < $detail->price ? $detail->promotion_price : $detail->price }}" data-promo="@if ($detail->promotion_price && $detail->promotion_price < $detail->price) sale @elseif ($detail->promotion_price && $detail->promotion_price == $detail->price) new @else none @endif" data-rating="{{ rand(3, 5) }}">
                            <div class="product-image">
                                @php
                                    $img = $book->images->first()->url ?? 'client/img/products/noimage.png';
                                @endphp
                                <img src="{{ asset('storage/' . $img) }}" alt="{{ $book->name }}" loading="lazy">
                                <div class="product-overlay">
                                    <button class="quick-view-btn" title="Xem nhanh">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="wishlist-btn" title="Yêu thích">
                                        <i class="far fa-heart"></i>
                                    </button>
                                </div>
                                @if ($detail->promotion_price && $detail->promotion_price < $detail->price)
                                    <div class="discount-badge">
                                        -{{ round((($detail->price - $detail->promotion_price) / $detail->price) * 100) }}%
                                    </div>
                                @elseif ($detail->promotion_price && $detail->promotion_price == $detail->price)
                                    <div class="new-badge">Mới</div>
                                @endif
                            </div>

                            <div class="product-info">
<h3 class="product-title">
    <a href="{{ route('book.detail', $book->id) }}" title="{{ $book->name }}">
        {{ $book->name }}
    </a>
</h3>


                                <div class="product-author">
                                    <i class="fas fa-user"></i>
                                    <span>Tác giả: {{ $book->author->name ?? 'Chưa cập nhật' }}</span>
                                </div>

                                <div class="product-rating">
                                    @php
                                        $rating = rand(3, 5);
                                        $reviews = rand(10, 999);
                                    @endphp
                                    <div class="stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $rating ? 'active' : '' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="rating-text">{{ $rating }}/5 ({{ $reviews }} đánh giá)</span>
                                </div>

                                <div class="product-price">
                                    @php
                                        $showPrice = $detail->promotion_price && $detail->promotion_price < $detail->price
                                            ? $detail->promotion_price
                                            : $detail->price;
                                    @endphp
                                    <span class="current-price">{{ number_format($showPrice, 0, '', '.') }}₫</span>
                                    @if ($detail->promotion_price && $detail->promotion_price < $detail->price)
                                        <span class="original-price">{{ number_format($detail->price, 0, '', '.') }}₫</span>
                                    @endif
                                </div>

                                <div class="product-actions">
                                    <button class="add-to-cart-btn">
                                        <i class="fas fa-shopping-cart"></i>
                                        Thêm vào giỏ
                                    </button>
                                    <button class="buy-now-btn">
                                        Mua ngay
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="no-products">
                        <div class="no-products-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3>Không tìm thấy sản phẩm</h3>
                        <p>Hãy thử điều chỉnh bộ lọc hoặc tìm kiếm với từ khóa khác</p>
                        <button class="reset-filter-btn">Đặt lại bộ lọc</button>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if(isset($books) && method_exists($books, 'links'))
                <div class="pagination-wrapper">
                    {{ $books->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-content">
                <div class="newsletter-text">
                    <h3>Đăng ký nhận tin</h3>
                    <p>Nhận thông báo về sách mới và ưu đãi đặc biệt</p>
                </div>
                <div class="newsletter-form">
                    <input type="email" placeholder="Nhập email của bạn..." class="email-input">
                    <button class="subscribe-btn">Đăng ký</button>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
/* General Styles */
.homepage-container {
    background-color: #f8f9fa;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 2rem;
    text-align: center;
    position: relative;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #3498db, #2980b9);
    border-radius: 2px;
}

/* Hero Banner */
.hero-banner {
    margin-bottom: 3rem;
}

.promotional-banner {
    margin-top: 1rem;
}

.promo-image {
    width: 100%;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Category Section */
.category-section {
    background: white;
    padding: 3rem 0;
    margin-bottom: 2rem;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* Filter Section */
.filter-section {
    background: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.filter-group {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-dropdown {
    position: relative;
}

.filter-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    color: #495057;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-btn:hover {
    background: #e9ecef;
    border-color: #3498db;
    color: #3498db;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    min-width: 200px;
    z-index: 1000;
    display: none;
    padding: 1rem;
}

.filter-dropdown:hover .dropdown-menu {
    display: block;
}

.price-range {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.price-input {
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    width: 100px;
}

.apply-btn {
    padding: 0.5rem 1rem;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.filter-options, .rating-options, .sort-options {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-options label, .rating-options label, .sort-options label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    padding: 0.25rem 0;
}

.search-filter {
    flex: 1;
    max-width: 400px;
}

.search-box {
    display: flex;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
}

.search-input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: none;
    outline: none;
    font-size: 1rem;
}

.search-btn {
    padding: 0.75rem 1rem;
    background: #3498db;
    color: white;
    border: none;
    cursor: pointer;
    transition: background 0.3s ease;
}

.search-btn:hover {
    background: #2980b9;
}

.active-filters {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.clear-all-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
}

/* Products Section */
.products-section {
    padding: 2rem 0;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.view-options {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    padding: 0.5rem;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.view-btn.active {
    background: #3498db;
    color: white;
    border-color: #3498db;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.product-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.product-image {
    position: relative;
    overflow: hidden;
    height: 250px;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.quick-view-btn, .wishlist-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: rgba(255,255,255,0.9);
    color: #666;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.quick-view-btn:hover, .wishlist-btn:hover {
    background: #3498db;
    color: white;
}

.discount-badge, .new-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
}

.discount-badge {
    background: #e74c3c;
}

.new-badge {
    background: #27ae60;
}

.product-info {
    padding: 1.5rem;
}

.product-title {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    line-height: 1.4;
}

.product-title a {
    color: #2c3e50;
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-title a:hover {
    color: #3498db;
}

.product-author {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.stars {
    display: flex;
    gap: 2px;
}

.stars i {
    color: #ddd;
    font-size: 0.9rem;
}

.stars i.active {
    color: #f39c12;
}

.rating-text {
    font-size: 0.8rem;
    color: #666;
}

.product-price {
    margin-bottom: 1rem;
}

.current-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #e74c3c;
}

.original-price {
    font-size: 1rem;
    color: #999;
    text-decoration: line-through;
    margin-left: 0.5rem;
}

.product-actions {
    display: flex;
    gap: 0.5rem;
}

.add-to-cart-btn, .buy-now-btn {
    flex: 1;
    padding: 0.75rem;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-to-cart-btn {
    background: #f8f9fa;
    color: #3498db;
    border: 1px solid #3498db;
}

.add-to-cart-btn:hover {
    background: #3498db;
    color: white;
}

.buy-now-btn {
    background: #3498db;
    color: white;
}

.buy-now-btn:hover {
    background: #2980b9;
}

.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
}

.no-products-icon {
    font-size: 4rem;
    color: #bdc3c7;
    margin-bottom: 1rem;
}

.no-products h3 {
    font-size: 1.5rem;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.no-products p {
    color: #666;
    margin-bottom: 1.5rem;
}

.reset-filter-btn {
    padding: 0.75rem 1.5rem;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

/* Newsletter Section */
.newsletter-section {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    padding: 3rem 0;
    margin-top: 3rem;
}

.newsletter-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 2rem;
}

.newsletter-text h3 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.newsletter-form {
    display: flex;
    gap: 0.5rem;
    max-width: 400px;
    flex: 1;
}

.email-input {
    flex: 1;
    padding: 0.75rem;
    border: none;
    border-radius: 6px;
    outline: none;
}

.subscribe-btn {
    padding: 0.75rem 1.5rem;
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    white-space: nowrap;
}

/* Responsive Design */
@media (max-width: 768px) {
    .filter-bar {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-group {
        justify-content: center;
    }

    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }

    .newsletter-content {
        flex-direction: column;
        text-align: center;
    }

    .section-title {
        font-size: 1.5rem;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: 1fr;
    }

    .product-actions {
        flex-direction: column;
    }

    .newsletter-form {
        flex-direction: column;
    }
}
</style>

<script>
// Add account container function
function addContainTaiKhoan() {
    // Implementation for account container
    console.log('Account container added');
}

// Filter products by name
function filterProductsName(input) {
    const searchTerm = input.value.trim().toLowerCase();
    const products = document.querySelectorAll('.product-card');
    let found = false;

    if (searchTerm.length > 0) {
        products.forEach(product => {
            const title = product.querySelector('.product-title a').textContent.trim().toLowerCase();
            if (title.includes(searchTerm)) {
                product.style.display = '';
                found = true;
            } else {
                product.style.display = 'none';
            }
        });
    } else {
        products.forEach(product => {
            product.style.display = '';
            found = true;
        });
    }

    // Hiện thông báo nếu không có sản phẩm
    let noProductsMsg = document.querySelector('.no-products');
    if (!found) {
        if (!noProductsMsg) {
            const productsGrid = document.querySelector('.products-grid');
            const noProductsDiv = document.createElement('div');
            noProductsDiv.className = 'no-products';
            noProductsDiv.innerHTML = `
                <div class=\"no-products-icon\">\n                    <i class=\"fas fa-book-open\"></i>\n                </div>\n                <h3>Không tìm thấy sản phẩm</h3>\n                <p>Không có sản phẩm nào phù hợp với từ khóa tìm kiếm</p>\n            `;
            productsGrid.appendChild(noProductsDiv);
        }
    } else if (noProductsMsg) {
        noProductsMsg.remove();
    }
}

// Lọc tổng hợp theo filter
function filterProductsAll() {
    const minPrice = parseInt(document.querySelector('.price-range input[placeholder="0"]').value) || 0;
    const maxPrice = parseInt(document.querySelector('.price-range input[placeholder="1000000"]').value) || 1000000;
    const promoChecked = Array.from(document.querySelectorAll('.filter-options input[type="checkbox"]:checked')).map(cb => cb.value);
    const rating = parseInt(document.querySelector('.rating-options input[type="radio"]:checked')?.value || 0);
    const sort = document.querySelector('.sort-options input[type="radio"]:checked')?.value || '';

    let products = Array.from(document.querySelectorAll('.product-card'));
    let found = false;

    // Lọc theo giá
    products.forEach(product => {
        const price = parseInt(product.getAttribute('data-price'));
        const promo = product.getAttribute('data-promo');
        const rate = parseInt(product.getAttribute('data-rating'));
        let show = true;

        if (price < minPrice || price > maxPrice) show = false;
        if (promoChecked.length > 0 && !promoChecked.includes(promo)) show = false;
        if (rating > 0 && rate < rating) show = false;

        product.style.display = show ? '' : 'none';
        if (show) found = true;
    });

    // Sắp xếp
    if (sort) {
        let sorted = products.filter(p => p.style.display !== 'none');
        if (sort === 'price-low') {
            sorted.sort((a, b) => parseInt(a.getAttribute('data-price')) - parseInt(b.getAttribute('data-price')));
        } else if (sort === 'price-high') {
            sorted.sort((a, b) => parseInt(b.getAttribute('data-price')) - parseInt(a.getAttribute('data-price')));
        } else if (sort === 'newest') {
            // Không có dữ liệu ngày, bỏ qua
        } else if (sort === 'popular') {
            sorted.sort((a, b) => parseInt(b.getAttribute('data-rating')) - parseInt(a.getAttribute('data-rating')));
        }
        const grid = document.querySelector('.products-grid');
        sorted.forEach(p => grid.appendChild(p));
    }

    // Hiện thông báo nếu không có sản phẩm
    let noProductsMsg = document.querySelector('.no-products');
    if (!found) {
        if (!noProductsMsg) {
            const productsGrid = document.querySelector('.products-grid');
            const noProductsDiv = document.createElement('div');
            noProductsDiv.className = 'no-products';
            noProductsDiv.innerHTML = `
                <div class=\"no-products-icon\">\n                    <i class=\"fas fa-book-open\"></i>\n                </div>\n                <h3>Không tìm thấy sản phẩm</h3>\n                <p>Không có sản phẩm nào phù hợp với bộ lọc</p>\n            `;
            productsGrid.appendChild(noProductsDiv);
        }
    } else if (noProductsMsg) {
        noProductsMsg.remove();
    }
}

// Gắn sự kiện cho filter

document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const viewBtns = document.querySelectorAll('.view-btn');
    const productsGrid = document.querySelector('.products-grid');

    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            if (this.dataset.view === 'list') {
                productsGrid.style.gridTemplateColumns = '1fr';
            } else {
                productsGrid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(280px, 1fr))';
            }
        });
    });

    // Wishlist functionality
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            icon.classList.toggle('far');
            icon.classList.toggle('fas');

            if (icon.classList.contains('fas')) {
                this.style.color = '#e74c3c';
            } else {
                this.style.color = '#666';
            }
        });
    });

    // Newsletter subscription
    const subscribeBtn = document.querySelector('.subscribe-btn');
    if (subscribeBtn) {
        subscribeBtn.addEventListener('click', function() {
            const emailInput = document.querySelector('.email-input');
            const email = emailInput.value;

            if (email && email.includes('@')) {
                alert('Cảm ơn bạn đã đăng ký nhận tin!');
                emailInput.value = '';
            } else {
                alert('Vui lòng nhập email hợp lệ!');
            }
        });
    }

    // Add to cart functionality
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productCard = this.closest('.product-card');
            const productName = productCard.querySelector('.product-title a').textContent;

            // Add loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
            this.disabled = true;

            // Simulate API call
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-check"></i> Đã thêm';
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.disabled = false;
                }, 1000);
            }, 500);
        });
    });

    // Khoảng giá
    document.querySelector('.price-range .apply-btn').addEventListener('click', function() {
        filterProductsAll();
    });
    // Khuyến mãi
    document.querySelectorAll('.filter-options input[type="checkbox"]').forEach(cb => {
        cb.addEventListener('change', filterProductsAll);
    });
    // Đánh giá
    document.querySelectorAll('.rating-options input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', filterProductsAll);
    });
    // Sắp xếp
    document.querySelectorAll('.sort-options input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', filterProductsAll);
    });

    // Reset filter
    const resetBtn = document.getElementById('deleteAllFilter');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            // Reset khoảng giá
            document.querySelector('.price-range input[placeholder="0"]').value = '';
            document.querySelector('.price-range input[placeholder="1000000"]').value = '';
            // Reset khuyến mãi
            document.querySelectorAll('.filter-options input[type="checkbox"]').forEach(cb => cb.checked = false);
            // Reset đánh giá
            document.querySelectorAll('.rating-options input[type="radio"]').forEach(radio => radio.checked = false);
            // Reset sắp xếp
            document.querySelectorAll('.sort-options input[type="radio"]').forEach(radio => radio.checked = false);
            // Hiện lại tất cả sản phẩm
            filterProductsAll();
        });
    }
});

// Call the function when page loads
addContainTaiKhoan();
</script>
@endsection
