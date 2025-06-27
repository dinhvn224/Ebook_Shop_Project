@extends('layouts.admin')

@section('content')
<div class="container my-4" style="max-width: 700px; font-family: 'Courier New', monospace;">
    <h4 class="text-center mb-4">🧾 HÓA ĐƠN BÁN HÀNG</h4>

    {{-- Thông tin chung --}}
    <p><strong>Mã đơn:</strong> #{{ $order->id }}</p>
    <p><strong>Ngày:</strong> {{ $order->order_date?->format('d/m/Y H:i') ?? $order->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Trạng thái:</strong> {{ $order->status }}</p>

    {{-- Nhân viên & Khách hàng --}}
    <p><strong>Nhân viên:</strong> {{ $order->user->name ?? '-' }}</p>
    <p><strong>Khách hàng:</strong> {{ $order->customer_name }}</p>
    <p><strong>SĐT:</strong> {{ $order->phone_number }}</p>
    <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>
    <hr>

    {{-- Bảng sản phẩm --}}
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Sách</th>
                <th>SL</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->bookDetail->book->name ?? 'N/A' }} ({{ $item->bookDetail->language }})</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->promotion_price ?? $item->price) }}đ</td>
                <td>{{ number_format(($item->promotion_price ?? $item->price) * $item->quantity) }}đ</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tổng kết --}}
    <div class="text-end mt-4">
        <p><strong>Tổng tiền:</strong> {{ number_format($order->final_amount) }}đ</p>
        <p><strong>Tiền khách đưa:</strong> {{ number_format($order->final_amount + $order->change_amount) }}đ</p>
        <p><strong>Tiền thối lại:</strong> {{ number_format($order->change_amount) }}đ</p>
    </div>

    {{-- Nút in --}}
    <div class="text-center mt-4 d-print-none">
        <button onclick="window.print()" class="btn btn-primary">🖨 In hóa đơn</button>
        <a href="{{ route('counter.index') }}" class="btn btn-secondary">← Trở lại danh sách</a>
    </div>
</div>
@endsection
