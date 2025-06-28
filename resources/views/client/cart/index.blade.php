<!-- resources/views/client/cart/index.blade.php -->
@extends('client.layouts.app')

@section('content')
<section style="min-height: 85vh">
    <h2 style="margin: 24px 0 16px 0; text-align:center;">Giỏ hàng của bạn</h2>

    @if (session('success'))
        <div class="alert alert-success" style="text-align:center;">{{ session('success') }}</div>
    @endif

    @if ($cart && $cart->items->where('deleted', false)->count())
        <table class="listSanPham">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Thời gian</th>
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
                            <a href="{{ route('product.detail', $item->bookDetail->id) }}" target="_blank">{{ $item->bookDetail->language }}</a>
                            <img src="{{ asset('storage/' . $item->bookDetail->image) }}" alt="#" style="width:40px;height:40px;">
                        </td>
                        <td class="align-right">{{ number_format($item->bookDetail->price, 0, ',', '.') }} ₫</td>
                        <td class="soluong">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                                @csrf
                                <button type="submit" formaction="{{ route('cart.update', $item->id) }}?action=decrease" class="btn btn-secondary">−</button>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="50" readonly>
                                <button type="submit" formaction="{{ route('cart.update', $item->id) }}?action=increase" class="btn btn-secondary">+</button>
                            </form>
                        </td>
                        <td class="align-right">{{ number_format($subtotal, 0, ',', '.') }} ₫</td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                <tr class="total-row">
                    <td colspan="4">Tổng cộng:</td>
                    <td class="align-right">{{ number_format($total, 0, ',', '.') }} ₫</td>
                    <td colspan="2" class="actions">
                        <button class="btn btn-success" disabled style="opacity:0.5;">Thanh toán</button>
                        <form action="{{ route('cart.clear') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">Xóa hết</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    @else
        <p style="text-align:center;">Giỏ hàng của bạn đang trống.</p>
    @endif
</section>

<style>
    .listSanPham {
        width: 100%;
        border-collapse: collapse;
        margin: 0 auto;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .listSanPham thead {
        background-color: #f5f5f5;
    }

    .listSanPham th, .listSanPham td {
        padding: 12px 16px;
        border: 1px solid #ddd;
        text-align: center;
        vertical-align: middle;
    }

    .listSanPham th {
        background-color: #f8f8f8;
        font-weight: bold;
        color: #333;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }

    .product-info a {
        text-decoration: none;
        color: #333;
        font-weight: 500;
    }

    .product-info a:hover {
        color: #007bff;
    }

    .product-info img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }

    .align-right {
        text-align: right;
    }

    .soluong form {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .soluong input[type="number"] {
        width: 50px;
        text-align: center;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 4px 0;
        background-color: #f9f9f9;
    }

    .soluong button {
        padding: 4px 10px;
    }

    .total-row {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    .actions {
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .actions button {
        padding: 8px 16px;
    }

    .actions button[disabled] {
        cursor: not-allowed;
    }

    .btn {
        padding: 6px 12px;
        border-radius: 4px;
        border: none;
        cursor: pointer;
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
        opacity: 0.9;
    }

    .alert-success {
        padding: 10px 20px;
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        border-radius: 4px;
        color: #155724;
        width: fit-content;
        margin: 0 auto 16px;
    }

    p {
        font-size: 18px;
        color: #777;
    }
</style>
@endsection
