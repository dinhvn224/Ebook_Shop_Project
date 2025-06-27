@extends('layouts.admin')

@section('content')
<div class="container my-4" style="max-width: 850px">
    <h3 class="mb-3">🧾 Chi tiết đơn hàng #{{ $order->id }}</h3>

    {{-- Thông tin chung --}}
    <p><strong>Ngày đặt:</strong> {{ $order->order_date?->format('d/m/Y H:i') ?? $order->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Trạng thái:</strong> {{ $order->status }}</p>

    {{-- Thông tin người bán và khách hàng --}}
    <p><strong>Nhân viên bán hàng:</strong> {{ $order->user->name ?? '-' }}</p>
    <p><strong>Khách hàng:</strong> {{ $order->customer_name }}</p>
    <p><strong>Số điện thoại:</strong> {{ $order->phone_number }}</p>
    <p><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>

    {{-- Bảng sản phẩm --}}
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Sách</th>
                <th>Ngôn ngữ</th>
                <th>SL</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->bookDetail->book->name ?? 'N/A' }}</td>
                <td>{{ $item->bookDetail->language }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->promotion_price ?? $item->price) }}đ</td>
                <td>{{ number_format(($item->promotion_price ?? $item->price) * $item->quantity) }}đ</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Tổng tiền & thanh toán --}}
    <div class="text-end mt-3">
        <p><strong>Tổng tiền:</strong> {{ number_format($order->final_amount) }}đ</p>
        <p><strong>Tiền khách đưa:</strong> {{ number_format($order->final_amount + $order->change_amount) }}đ</p>
        <p><strong>Tiền thối lại:</strong> {{ number_format($order->change_amount) }}đ</p>
    </div>

    {{-- Hành động --}}
    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('counter.receipt', $order->id) }}" class="btn btn-outline-secondary">🖨 In hóa đơn</a>
        <a href="{{ route('counter.pdf', $order->id) }}" class="btn btn-outline-dark">⬇️ Tải PDF</a>
        <a href="{{ route('counter.index') }}" class="btn btn-outline-primary">← Trở lại danh sách</a>
    </div>
</div>
@endsection
