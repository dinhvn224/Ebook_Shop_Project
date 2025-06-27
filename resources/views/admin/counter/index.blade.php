@extends('layouts.admin')

@section('content')
    <div class="pos-container">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-primary">
                        <i class="fas fa-cash-register me-2"></i>
                        Quản lý đơn hàng tại quầy
                    </h1>
                    <p class="text-muted mb-0">Xử lý và theo dõi đơn hàng bán tại cửa hàng</p>
                </div>
                <div class="badge bg-info fs-6">
                    <i class="fas fa-shopping-cart me-1"></i>
                    {{ $orders->total() ?? count($orders) }} đơn hàng
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Controls Panel -->
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Tạo đơn mới -->
                    <div class="col-lg-6">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-plus-circle text-success me-1"></i>
                            Tạo đơn hàng mới
                        </h6>
                        <form action="{{ route('counter.createOrder') }}" method="POST" class="row g-2 align-items-end">
                            @csrf

                            <!-- Nhân viên bán hàng -->
                            <div class="col-md-3">
                                <label class="form-label">👨‍💼 Nhân viên</label>
                                <select name="user_id" class="form-select" required>
                                    <option value="">-- Chọn nhân viên --</option>
                                    @foreach(\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nút tạo đơn -->
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-plus"></i> Tạo đơn
                                </button>
                            </div>

                        </form>

                    </div>

                </div>
            </div>
        </div>

        <!-- Danh sách đơn hàng -->
        <div class="orders-container">
            @forelse($orders as $order)
                <div class="card mb-4 shadow-sm order-card">


                    <!-- Header -->
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-receipt text-primary me-2"></i>
                                    Đơn hàng #{{ $order->id }}
                                </h5>
                                <span class="badge {{ $order->status === 'PAID' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $order->status === 'PAID' ? 'Đã thanh toán' : 'Chờ thanh toán' }}
                                </span>
                            </div>

                            @if($order->status !== 'PAID')
                                <form action="{{ route('counter.checkout', $order->id) }}" method="POST"
                                    class="d-flex flex-wrap gap-2 align-items-center">
                                    @csrf

                                    <input type="text" name="customer_name" class="form-control form-control-sm"
                                        placeholder="👤 Tên khách" value="{{ $order->customer_name ?? '' }}" style="width: 160px;">

                                    <input type="text" name="phone_number" class="form-control form-control-sm" placeholder="📱 SĐT"
                                        value="{{ $order->phone_number ?? '' }}" style="width: 130px;">

                                    <input type="text" name="shipping_address" class="form-control form-control-sm"
                                        placeholder="🏠 Địa chỉ" value="{{ $order->shipping_address ?? '' }}" style="width: 180px;">

                                    <input type="number" name="amount_paid" class="form-control form-control-sm amount-paid-input"
                                        data-total="{{ $order->final_amount }}" placeholder="💵 Tiền khách đưa"
                                        min="{{ $order->final_amount }}" step="1000" required style="width: 160px;">

                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-credit-card me-1"></i> Thanh toán
                                    </button>
                                </form>


                                <!-- Hiển thị tiền thối lại -->
                                <div class="text-end small text-muted mt-1 me-2">
                                    🧾 Tổng: {{ number_format($order->final_amount) }}đ
                                    <br>
                                    💴 Trả lại: <span class="fw-bold text-danger refund-amount">0đ</span>
                                </div>

                            @endif
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <!-- Thông tin khách hàng -->
                        <div class="row mb-3">
                            <div class="col-lg-8">
                                <div class="row g-2 text-sm">
                                    <div class="col-md-4">
                                        <i class="fas fa-user text-primary me-1"></i>
                                        <strong>{{ $order->user->name ?? $order->customer_name }}</strong>
                                    </div>
                                    <div class="col-md-4">
                                        <i class="fas fa-phone text-success me-1"></i>
                                        {{ $order->phone_number ?? 'Chưa có' }}
                                    </div>
                                    <div class="col-md-4">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                        {{ Str::limit($order->shipping_address ?? 'Tại quầy', 20) }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 text-end">
                                <a href="{{ route('counter.show', $order->id) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye me-1"></i>
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>

                        <!-- Bảng sản phẩm -->
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 40%">Sản phẩm</th>
                                        <th style="width: 15%">Số lượng</th>
                                        <th style="width: 15%">Đơn giá</th>
                                        <th style="width: 15%">Thành tiền</th>
                                        <th style="width: 15%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($order->items->where('deleted', false) as $item)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $item->bookDetail->book->name ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-language me-1"></i>
                                                        {{ $item->bookDetail->language }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('counter.updateItem', $item->id) }}"
                                                    class="d-flex gap-1">
                                                    @csrf @method('PUT')
                                                    <input type="number" name="quantity" value="{{ $item->quantity }}"
                                                        class="form-control form-control-sm" style="width: 60px;" min="1">
                                                    <button class="btn btn-sm btn-outline-primary" title="Cập nhật">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">
                                                    {{ number_format($item->promotion_price ?? $item->price) }}đ
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold">
                                                    {{ number_format(($item->promotion_price ?? $item->price) * $item->quantity) }}đ
                                                </span>
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('counter.deleteItem', $item->id) }}"
                                                    class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Xóa sản phẩm này?')" title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">
                                                <i class="fas fa-shopping-cart me-2"></i>
                                                Chưa có sản phẩm nào
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Thêm sản phẩm -->
                        <div class="border-top pt-3">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-plus text-success me-1"></i>
                                Thêm sản phẩm
                            </h6>

                            <form method="POST" action="{{ route('counter.addItem') }}">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle small">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Ảnh</th>
                                                <th>Sách</th>
                                                <th>Ngôn ngữ</th>
                                                <th>Giá</th>
                                                <th>Kho</th>
                                                <th width="90">SL</th>
                                                <th width="90">Chọn</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(\App\Models\BookDetail::with('book')->where('is_active', true)->get() as $book)
                                                <tr>
                                                    <td>
                                                        <img src="{{ $book->book->cover ?? asset('images/default.jpg') }}"
                                                            width="45" height="60"
                                                            style="object-fit: cover; border: 1px solid #ccc;" alt="Bìa sách">
                                                    </td>
                                                    <td>{{ $book->book->name }}</td>
                                                    <td>{{ $book->language }}</td>
                                                    <td>{{ number_format($book->promotion_price > 0 ? $book->promotion_price : $book->price) }}đ
                                                    </td>
                                                    <td>{{ $book->quantity }}</td>
                                                    <td>
                                                        <input type="number" name="quantities[{{ $book->id }}]"
                                                            class="form-control form-control-sm" value="1" min="1"
                                                            max="{{ $book->quantity }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="products[]" value="{{ $book->id }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-end mt-2">
                                    <button class="btn btn-success btn-sm">
                                        <i class="fas fa-plus me-1"></i> Thêm vào đơn hàng
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="fs-5 fw-bold text-success">
                                <i class="fas fa-calculator me-2"></i>
                                Tổng cộng: {{ number_format($order->final_amount) }}đ
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('counter.receipt', $order->id) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-print me-1"></i>
                                    In hóa đơn
                                </a>
                                <a href="{{ route('counter.pdf', $order->id) }}" class="btn btn-outline-dark btn-sm">
                                    <i class="fas fa-download me-1"></i>
                                    Xuất PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Không có đơn hàng nào</h5>
                        <p class="text-muted">Tạo đơn hàng mới để bắt đầu bán hàng</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if(method_exists($orders, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            @foreach($orders as $order)
                $('#search-product-{{ $order->id }}').select2({
                    width: '100%',
                    placeholder: 'Tìm sách...',
                });
            @endforeach
                                });
    </script>
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('.amount-paid-input').forEach(input => {
            input.addEventListener('input', function () {
                const total = parseInt(this.dataset.total) || 0;
                const paid = parseInt(this.value) || 0;
                const refund = Math.max(paid - total, 0);

                this.closest('.order-card')
                    .querySelector('.refund-amount')
                    .textContent = refund.toLocaleString('vi-VN') + 'đ';
            });
        });
    </script>
@endpush