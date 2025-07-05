<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BookStore - Nhà Sách Trực Tuyến')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #e74c3c;
            --accent-color: #f39c12;
            --success-color: #27ae60;
            --text-dark: #2c3e50;
            --bg-light: #ecf0f1;
        }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-light); }
        .navbar-brand { font-weight: bold; color: var(--primary-color) !important; }
        .hero-section { background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(44, 62, 80, 0.8)), url('https://images.unsplash.com/photo-1532012197267-da84d127e765?q=80&w=1974&auto=format&fit=crop'); background-size: cover; background-position: center; color: white; padding: 120px 0; }
        .book-card { transition: transform 0.3s ease, box-shadow 0.3s ease; height: 100%; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05); background-color: white; border-radius: 15px; overflow: hidden; }
        .book-card:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
        .book-image { height: 280px; object-fit: cover; width: 100%; cursor: pointer; }
        .price { color: var(--secondary-color); font-weight: bold; font-size: 1.3em; }
        .old-price { text-decoration: line-through; color: #6c757d; font-size: 1em; }
        .discount-badge { position: absolute; top: 10px; right: 10px; background: var(--secondary-color); color: white; padding: 5px 10px; border-radius: 15px; font-size: 0.8em; font-weight: bold; }
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover { background-color: #34495e; border-color: #34495e; }
        .btn-accent { background-color: var(--accent-color); border-color: var(--accent-color); color: white; }
        .btn-accent:hover { background-color: #d35400; border-color: #d35400; color: white; }
        .category-card { background: linear-gradient(135deg, var(--bg-light), white); border-radius: 15px; padding: 40px 20px; text-align: center; transition: all 0.4s ease; cursor: pointer; border: 2px solid transparent; }
        .category-card:hover { background: linear-gradient(135deg, var(--primary-color), #34495e); color: white; transform: translateY(-10px); border-color: var(--accent-color); }
        .footer { background-color: var(--primary-color); color: white; padding: 60px 0 30px; }
        .cart-count { background-color: var(--secondary-color); color: white; border-radius: 50%; padding: 3px 8px; font-size: 0.75em; position: absolute; top: -8px; right: -8px; }
        .page { display: none; }
        .page.active { display: block; animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .product-detail-img { height: 450px; width: 100%; object-fit: contain; }
        .cart-item { border-bottom: 1px solid #dee2e6; padding: 20px 0; }
        .rating-stars { color: #ffc107; }
        .search-box { flex-grow: 1; max-width: 600px; margin: 0 auto; }
        .promotion-banner { background: linear-gradient(45deg, var(--secondary-color), var(--accent-color)); color: white; padding: 15px 0; text-align: center; font-weight: bold; }
        .user-menu .dropdown-menu { min-width: 250px; }
        .order-status { padding: 5px 12px; border-radius: 20px; font-size: 0.85em; font-weight: bold; }
        .status-pending { background-color: #ffeaa7; color: #2d3436; }
        .status-confirmed { background-color: #74b9ff; color: white; }
        .status-shipping { background-color: #fd79a8; color: white; }
        .status-delivered { background-color: #00b894; color: white; }
        .status-cancelled { background-color: #636e72; color: white; }
        .review-card { background: #ffffff; border-radius: 10px; padding: 20px; margin-bottom: 15px; border: 1px solid #dee2e6; }
        .toast-container { position: fixed; top: 80px; right: 20px; z-index: 9999; }
        .voucher-input { border: 2px dashed #dee2e6; background: #f8f9fa; }
        .login-modal .modal-dialog { max-width: 400px; }
    </style>
</head>
<body>

    @include('client.partials.header')

    <div class="toast-container" id="toastContainer"></div>

    <main>
        @yield('content')
    </main>

    @include('client.partials.footer')

    <div class="modal fade login-modal" id="loginModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Đăng Nhập / Đăng Ký</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation"><a class="nav-link active" data-bs-toggle="pill" href="#pills-login" role="tab">Đăng Nhập</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" data-bs-toggle="pill" href="#pills-register" role="tab">Đăng Ký</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pills-login" role="tabpanel">
                            <form onsubmit="event.preventDefault(); login();">
                                <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" id="loginEmail" required></div>
                                <div class="mb-3"><label class="form-label">Mật khẩu</label><input type="password" class="form-control" id="loginPassword" required></div>
                                <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="pills-register" role="tabpanel">
                            <form onsubmit="event.preventDefault(); register();">
                                <div class="mb-3"><label class="form-label">Họ và Tên</label><input type="text" class="form-control" id="registerName" required></div>
                                <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" id="registerEmail" required></div>
                                <div class="mb-3"><label class="form-label">Mật khẩu</label><input type="password" class="form-control" id="registerPassword" required></div>
                                <button type="submit" class="btn btn-primary w-100">Đăng Ký</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/chatbot.js') }}"></script>
    <script>
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();
            const toastBg = type === 'success' ? 'bg-success' : 'bg-danger';
            const toastHTML = `<div id="${toastId}" class="toast align-items-center text-white ${toastBg} border-0" role="alert" aria-live="assertive" aria-atomic="true"><div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div></div>`;
            toastContainer.insertAdjacentHTML('beforeend', toastHTML);
            const toast = new bootstrap.Toast(document.getElementById(toastId), { delay: 3000 });
            toast.show();
            document.getElementById(toastId).addEventListener('hidden.bs.toast', e => e.target.remove());
        }
        let loginModal;
        function showLoginModal() {
            if(!loginModal) {
                loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            }
            loginModal.show();
        }
        function login() {
            // Xử lý đăng nhập thực tế ở đây
        }
        function register() {
            // Xử lý đăng ký thực tế ở đây
        }
        function logout() {
            // Xử lý đăng xuất thực tế ở đây
        }
    </script>

</body>
</html>
