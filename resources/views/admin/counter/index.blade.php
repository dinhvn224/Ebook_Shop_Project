@extends('admin.layouts.app')

@section('content')
<div class="pos-container">

    <!-- Page Header -->
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h4 text-primary"><i class="fas fa-cash-register me-2"></i> Quản lý đơn hàng tại quầy</h1>
            <p class="text-muted mb-0">Xử lý và theo dõi đơn hàng bán tại cửa hàng</p>
        </div>
        <div class="badge bg-info fs-6">
            <i class="fas fa-shopping-cart me-1"></i>
            {{ $orders->total() ?? count($orders) }} đơn hàng
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form tạo đơn mới -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3"><i class="fas fa-plus-circle text-success me-1"></i> Tạo đơn hàng mới</h6>
            <form action="{{ route('admin.counter.createOrder') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-4">
                    <label class="form-label">👨‍💼 Nhân viên</label>
                    <select name="user_id" class="form-select" required>
                        <option value="">-- Chọn nhân viên --</option>
                        @foreach(\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success w-100">
                        <i class="fas fa-plus me-1"></i> Tạo đơn
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách đơn hàng -->
    <div class="orders-container">
        @forelse($orders as $order)
            @include('admin.counter._order_card', ['order' => $order])
        @empty
            <div class="card">
                <div class="card-body text-center py-5 text-muted">
                    <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                    <h5>Không có đơn hàng nào</h5>
                    <p>Tạo đơn mới để bắt đầu bán hàng</p>
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
