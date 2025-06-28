<div class="card mb-4 shadow-sm order-card">
    <!-- Header -->
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0">
                <i class="fas fa-receipt text-primary me-2"></i> Đơn hàng #{{ $order->id }}
            </h5>
            <span class="badge {{ $order->status === 'PAID' ? 'bg-success' : 'bg-warning text-dark' }}">
                {{ $order->status === 'PAID' ? 'Đã thanh toán' : 'Chờ thanh toán' }}
            </span>
        </div>

        @if($order->status === 'PENDING')
            <!-- Form thanh toán -->
            <form action="{{ route('admin.counter.checkout', $order->id) }}" method="POST"
                class="d-flex gap-2 align-items-center">
                @csrf
                <input type="text" name="customer_name" placeholder="👤 Khách" value="{{ $order->customer_name }}"
                    class="form-control form-control-sm" style="width: 150px;">
                <input type="text" name="phone_number" placeholder="📱 SĐT" value="{{ $order->phone_number }}"
                    class="form-control form-control-sm" style="width: 120px;">
                <input type="text" name="shipping_address" placeholder="🏠 Địa chỉ" value="{{ $order->shipping_address }}"
                    class="form-control form-control-sm" style="width: 180px;">
                <input type="number" name="amount_paid" min="{{ $order->final_amount }}"
                    class="form-control form-control-sm amount-paid-input" data-total="{{ $order->final_amount }}"
                    placeholder="💵 Tiền khách đưa" style="width: 150px;" required>
                <button class="btn btn-sm btn-primary"><i class="fas fa-credit-card me-1"></i> Thanh toán</button>
            </form>
        @endif
    </div>

    <!-- Body -->
    <div class="card-body">
        <!-- Thông tin khách hàng -->
        <div class="row mb-3">
            <div class="col-md-8 small">
                <strong>👨‍💼 Nhân viên:</strong> {{ $order->user->name ?? '-' }} |
                <strong>📱 SĐT:</strong> {{ $order->phone_number }} |
                <strong>🏠 Địa chỉ:</strong> {{ $order->shipping_address }}
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('admin.counter.show', $order->id) }}" class="btn btn-sm btn-outline-info">
                    <i class="fas fa-eye me-1"></i> Xem chi tiết
                </a>
            </div>
        </div>

        <!-- Bảng sản phẩm -->
        <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0 align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>Ảnh</th>
                        <th>Sách</th>
                        <th>Ngôn ngữ</th>
                        <th>SL</th>
                        <th>Giá</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items->where('deleted', false) as $item)
                        <tr>
                            <!-- Ảnh sản phẩm -->
                            <td class="text-center">
                                <img src="{{ $item->bookDetail->book->cover ?? asset('images/default.jpg') }}"
                                    alt="Bìa sách" width="50" height="70"
                                    style="object-fit: cover; border: 1px solid #ccc; border-radius: 6px;">
                            </td>

                            <!-- Tên sách -->
                            <td>
                                <strong>{{ $item->bookDetail->book->name ?? 'N/A' }}</strong>
                                <br>
                                <small class="text-muted">#{{ $item->bookDetail->id }}</small>
                            </td>

                            <td class="text-center">{{ $item->bookDetail->language }}</td>

                            <!-- Số lượng -->
                            <td class="text-center">
                                <form action="{{ route('admin.counter.updateItem', $item->id) }}" method="POST"
                                    class="d-flex justify-content-center gap-1">
                                    @csrf @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}"
                                        class="form-control form-control-sm text-center" style="width: 60px;">
                                    <button class="btn btn-sm btn-outline-primary" title="Cập nhật"><i
                                            class="fas fa-save"></i></button>
                                </form>
                            </td>

                            <!-- Giá đơn -->
                            <td class="text-end">{{ number_format($item->promotion_price ?? $item->price) }}đ</td>

                            <!-- Thành tiền -->
                            <td class="text-end">
                                {{ number_format(($item->promotion_price ?? $item->price) * $item->quantity) }}đ
                            </td>

                            <!-- Xóa -->
                            <td class="text-center">
                                <form action="{{ route('admin.counter.deleteItem', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Xoá sản phẩm?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <!-- Tổng cộng & tiền thối lại -->
        <div class="text-end mt-2">
            <p><strong>🧾 Tổng:</strong> {{ number_format($order->final_amount) }}đ</p>
            <p><strong>💴 Trả lại:</strong> <span class="refund-amount text-danger">0đ</span></p>
        </div>

        <!-- Thêm sản phẩm -->
        @if($order->status === 'PENDING')
            <div class="border-top pt-3 mt-4">
                <h6 class="fw-bold mb-3"><i class="fas fa-plus text-success me-1"></i> Thêm sản phẩm</h6>
                <form action="{{ route('admin.counter.addItem') }}" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle small">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Sách</th>
                                    <th>Ngôn ngữ</th>
                                    <th>Giá</th>
                                    <th>Kho</th>
                                    <th>SL</th>
                                    <th>Chọn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\BookDetail::with('book')->where('is_active', true)->get() as $book)
                                    <tr>
                                        <!-- Ảnh -->
                                        <td class="text-center">
                                            <img src="{{ $book->book->cover ?? asset('images/default.jpg') }}" alt="Bìa sách"
                                                width="45" height="60"
                                                style="object-fit: cover; border: 1px solid #ccc; border-radius: 5px;">
                                        </td>

                                        <!-- Tên sách -->
                                        <td>{{ $book->book->name }}</td>

                                        <!-- Ngôn ngữ -->
                                        <td class="text-center">{{ $book->language }}</td>

                                        <!-- Giá -->
                                        <td class="text-end">
                                            {{ number_format($book->promotion_price > 0 ? $book->promotion_price : $book->price) }}đ
                                        </td>

                                        <!-- Kho -->
                                        <td class="text-center">{{ $book->quantity }}</td>

                                        <!-- Số lượng -->
                                        <td class="text-center">
                                            <input type="number" name="quantities[{{ $book->id }}]" value="1"
                                                class="form-control form-control-sm text-center" min="1"
                                                max="{{ $book->quantity }}">
                                        </td>

                                        <!-- Chọn -->
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
        @endif

    </div>

    <!-- Footer -->
    <div class="card-footer bg-light d-flex justify-content-between align-items-center">
        <div class="fs-5 fw-bold text-success">
            <i class="fas fa-calculator me-2"></i> Tổng cộng: {{ number_format($order->final_amount) }}đ
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.counter.receipt', $order->id) }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-print me-1"></i> In hóa đơn
            </a>
            <a href="{{ route('admin.counter.pdf', $order->id) }}" class="btn btn-sm btn-outline-dark">
                <i class="fas fa-download me-1"></i> Tải PDF
            </a>
        </div>
    </div>
</div>