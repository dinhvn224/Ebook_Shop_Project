@extends('client.layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #e0f7fa 0%, #e8eaf6 100%);
    }
    .checkout-container {
        max-width: 1000px;
        margin: 60px auto;
        display: grid;
        grid-template-columns: 2.5fr 1.5fr;
        gap: 40px;
    }
    .checkout-form, .order-summary {
        background: #ffffff;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }
    .checkout-form:hover, .order-summary:hover {
        transform: translateY(-5px);
    }
    .checkout-form h2, .order-summary h2 {
        font-size: 2rem;
        margin-bottom: 25px;
        color: #424242;
        position: relative;
    }
    .checkout-form h2::after, .order-summary h2::after {
        content: '';
        position: absolute;
        width: 60px;
        height: 3px;
        background: #009688;
        bottom: -10px;
        left: 0;
        border-radius: 2px;
    }
    .form-group {
        margin-bottom: 25px;
    }
    .form-control {
        border-radius: 8px;
        border: 1px solid #cfd8dc;
        padding: 14px;
        font-size: 1rem;
        outline: none;
        transition: border-color 0.3s;
    }
    .form-control:focus {
        border-color: #009688;
        box-shadow: 0 0 5px rgba(0, 150, 136, 0.3);
    }
    .alert-danger {
        max-width: 1000px;
        margin: 20px auto;
    }
    .product-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #eceff1;
        transition: background 0.2s;
    }
    .product-item:hover {
        background: #f1f8e9;
    }
    .product-item span:first-child {
        flex: 1;
        font-weight: 500;
        color: #37474f;
    }
    .product-item span:last-child {
        color: #212121;
        font-weight: 600;
    }
    .total-line {
        display: flex;
        justify-content: space-between;
        padding: 20px 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: #212121;
        border-top: 2px solid #b0bec5;
        margin-top: 20px;
    }
    .btn-submit {
        display: block;
        width: 100%;
        background-color: #009688;
        border-color: #009688;
        font-size: 1.2rem;
        padding: 16px;
        border-radius: 8px;
        margin-top: 20px;
        transition: background 0.3s;
    }
    .btn-submit:hover {
        background-color: #00796b;
        border-color: #00796b;
    }
    @media (max-width: 768px) {
        .checkout-container {
            grid-template-columns: 1fr;
        }
    }
</style>



<form action="{{ route('checkout.process') }}" method="POST">
    @csrf
    <div class="checkout-container">
        {{-- Form thanh toán --}}
        <div class="checkout-form">
            <h2>Thông tin giao nhận</h2>

            {{-- Hiển thị lỗi tồn kho và lỗi chung --}}
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $msg)
                            <li>{{ $msg }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="customer_name">Họ tên</label>
                <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Nguyễn Văn A" value="{{ old('customer_name') }}" required>
            </div>
            <div class="form-group">
                <label for="shipping_address">Địa chỉ giao hàng</label>
                <input type="text" id="shipping_address" name="shipping_address" class="form-control" placeholder="123 Đường ABC, Phường XYZ" value="{{ old('shipping_address') }}" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Số điện thoại</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control" placeholder="0912345678" value="{{ old('phone_number') }}" required>
            </div>
        </div>

        {{-- Tổng đơn hàng --}}
        <div class="order-summary">
            <h2>Đơn hàng của bạn</h2>
            @php $total = 0; @endphp
            @foreach ($cart->items as $item)
                @php
                    $price = $item->bookDetail->promotion_price ?? $item->bookDetail->price;
                    $subtotal = $price * $item->quantity;
                    $total += $subtotal;
                @endphp
                <div class="product-item">
                    <span>{{ $item->bookDetail->book->name }} x{{ $item->quantity }}</span>
                    <span>{{ number_format($subtotal, 0, ',', '.') }} ₫</span>
                </div>
            @endforeach
            <div class="product-item">
                <span>Phí vận chuyển</span>
                <span>{{ number_format(30000, 0, ',', '.') }} ₫</span>
            </div>
            <div class="total-line">
                <span>Tổng thanh toán</span>
                <span>{{ number_format($total + 30000, 0, ',', '.') }} ₫</span>
            </div>
            <button type="submit" class="btn btn-primary btn-submit">Xác nhận & Thanh toán</button>
        </div>
    </div>
</form>
@endsection
