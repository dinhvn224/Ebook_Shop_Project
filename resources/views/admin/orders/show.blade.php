@extends('layouts.admin')

@section('content')
<h2>Chi tiết đơn hàng #{{ $order->id }}</h2>

<p><strong>Tên khách hàng:</strong> {{ $order->customer_name ?? $order->user->name ?? 'N/A' }}</p>
<p><strong>Số điện thoại:</strong> {{ $order->phone_number ?? 'N/A' }}</p>
<p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address ?? 'N/A' }}</p>
<p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
<p><strong>Ngày đặt:</strong> {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') : $order->created_at->format('d/m/Y') }}</p>
<p><strong>Trạng thái:</strong>
    <span class="badge bg-{{ match($order->status) {
        'PENDING' => 'warning',
        'PAID' => 'info',
        'COMPLETED' => 'success',
        'CANCELLED' => 'danger',
        'REFUNDED' => 'secondary',
        default => 'dark'
    } }}">
        {{ $order->status }}
    </span>
</p>

<h4 class="mt-4">📚 Sản phẩm trong đơn</h4>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Tên sách</th>
            <th>Ngôn ngữ</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->bookDetail->book->name ?? 'Không xác định' }}</td>
            <td>{{ $item->bookDetail->language ?? '-' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($item->price) }}đ</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h5 class="mt-4">🎯 Tổng thanh toán: <strong class="text-success">{{ number_format($order->final_amount) }}đ</strong></h5>

<form method="POST" action="{{ route('orders.update', $order->id) }}" class="mt-4">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="status">Cập nhật trạng thái:</label>
        <select name="status" class="form-control" id="status">
            @foreach(['PENDING', 'PAID', 'COMPLETED', 'CANCELLED', 'REFUNDED'] as $status)
                <option value="{{ $status }}" @if($order->status === $status) selected @endif>{{ $status }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-success mt-2">Cập nhật</button>
    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary mt-3">← Quay về danh sách đơn</a>
</form>
@endsection
