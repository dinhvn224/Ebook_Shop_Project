@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-primary">
            <i class="fas fa-tags me-2"></i> Gán mã giảm giá cho sản phẩm
        </h4>
    </div>

    {{-- Thông báo --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form lọc --}}
    <form method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-md-4">
            <label class="form-label">🔍 Tìm kiếm</label>
            <input type="text" name="search" class="form-control" placeholder="Tên sản phẩm..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">🎟 Lọc theo voucher</label>
            <select name="voucher" class="form-select">
                <option value="">-- Tất cả mã giảm giá --</option>
                @foreach($vouchers as $v)
                    <option value="{{ $v->id }}" {{ request('voucher') == $v->id ? 'selected' : '' }}>
                        {{ $v->code }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-secondary w-100">
                <i class="fas fa-filter me-1"></i> Lọc
            </button>
        </div>
    </form>

    {{-- Bảng sản phẩm --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th width="30%">📘 Sản phẩm</th>
                    <th>💰 Giá</th>
                    <th>🎟 Voucher đã gán</th>
                    <th>⬇ Giảm</th>
                    <th>➕ Gán / Đổi voucher</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    @php
                        $voucher = $product->vouchers->first();
                        $discount = 0;
                        if ($voucher) {
                            $discount = $voucher->type === 'percent'
                                ? $product->price * $voucher->value / 100
                                : $voucher->value;
                            if ($voucher->type === 'percent' && $voucher->max_discount) {
                                $discount = min($discount, $voucher->max_discount);
                            }
                            $discount = min($discount, $product->price);
                        }
                    @endphp
                    <tr>
                        <td class="text-start ps-3">
                            <strong>{{ $product->name }}</strong>
                            <br>
                            <small class="text-muted">#{{ $product->id }}</small>
                        </td>
                        <td>{{ number_format($product->price) }}đ</td>
                        <td>
                            @if($voucher)
                                <form method="POST" action="{{ route('admin.voucher-products.detach') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="voucher_id" value="{{ $voucher->id }}">
                                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Gỡ mã {{ $voucher->code }}?')">
                                        {{ $voucher->code }} ✖
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">Chưa gán</span>
                            @endif
                        </td>
                        <td>
                            @if($discount > 0)
                                <span class="text-danger fw-bold">− {{ number_format($discount) }}đ</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.voucher-products.attach') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <select name="voucher_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">-- Chọn mã --</option>
                                    @foreach($vouchers as $v)
                                        <option value="{{ $v->id }}">
                                            {{ $v->code }} 
                                            ({{ $v->type === 'percent' ? $v->value.'%' : number_format($v->value).'đ' }})
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
