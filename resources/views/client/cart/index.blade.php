@extends('client.layouts.app')

@section('content')
<section style="min-height: 85vh; padding: 24px;">
    <h2 style="margin: 0 0 24px 0; text-align:center; font-size: 32px; color: #333;">Giỏ hàng của bạn</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($cart && $cart->items->where('deleted', false)->count())
        <div class="cart-container">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @php $index = 1; $total = 0; @endphp
                    @foreach ($cart->items->where('deleted', false) as $item)
                        @php
                            $subtotal = $item->bookDetail->price * $item->quantity;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $index++ }}</td>
                            <td class="product-info">
                                <a href="{{ route('book.detail', $item->bookDetail->book->id) }}" target="_blank">{{ $item->bookDetail->book->name }}</a>
                                <img src="{{ asset('storage/' . $item->bookDetail->image) }}" alt="Product Image">
                            </td>
                            <td class="align-right">{{ number_format($item->bookDetail->price, 0, ',', '.') }} ₫</td>
                            <td class="quantity">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                                    @csrf
                                    <button type="submit" formaction="{{ route('cart.update', $item->id) }}?action=decrease" class="btn btn-secondary">−</button>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="50" readonly>
                                    <button type="submit" formaction="{{ route('cart.update', $item->id) }}?action=increase" class="btn btn-secondary">+</button>
                                </form>
                            </td>
                            <td class="align-right">{{ number_format($subtotal, 0, ',', '.') }} ₫</td>
                            <td>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="cart-summary">
                <h3>Tổng cộng: <span>{{ number_format($total, 0, ',', '.') }} ₫</span></h3>
                <div class="cart-actions">
                    <button class="btn btn-success" disabled style="opacity:0.5; cursor:not-allowed;">Thanh toán</button>
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Xóa hết</button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <p style="text-align:center; font-size: 20px; color: #555;">Giỏ hàng của bạn đang trống.</p>
    @endif
</section>

<style>
    .cart-container {
        max-width: 1000px;
        margin: 0 auto;
        background-color: #fff;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .cart-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 24px;
    }

    .cart-table thead {
        background-color: #f8f9fa;
        color: #495057;
    }

    .cart-table th, .cart-table td {
        padding: 12px;
        border: 1px solid #dee2e6;
        text-align: center;
        vertical-align: middle;
        font-size: 14px;
    }

    .cart-table th {
        font-weight: bold;
        background-color: #e9ecef;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 12px;
        justify-content: center;
    }

    .product-info a {
        text-decoration: none;
        color: #007bff;
        font-weight: 600;
    }

    .product-info a:hover {
        text-decoration: underline;
    }

    .product-info img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #ddd;
        transition: transform 0.3s;
    }

    .product-info img:hover {
        transform: scale(1.05);
    }

    .align-right {
        text-align: right;
        font-weight: bold;
        color: #28a745;
    }

    .quantity form {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .quantity input[type="number"] {
        width: 50px;
        text-align: center;
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 4px;
        background-color: #f8f9fa;
    }

    .btn {
        padding: 6px 12px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn:hover {
        opacity: 0.85;
        transform: scale(1.05);
    }

    .cart-summary {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #dee2e6;
        padding-top: 16px;
    }

    .cart-summary h3 {
        font-size: 22px;
        color: #333;
    }

    .cart-summary h3 span {
        color: #28a745;
        font-weight: 700;
    }

    .cart-actions {
        display: flex;
        gap: 12px;
    }

    .alert-success {
        padding: 12px 20px;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        border-radius: 4px;
        color: #155724;
        width: fit-content;
        margin: 0 auto 24px;
        text-align: center;
        font-weight: 500;
    }

    .cart-actions button {
        width: 120px;
        padding: 10px;
    }

    /* Tạo một số hiệu ứng hover cho toàn bộ giỏ hàng */
    .cart-container:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
</style>
@endsection
