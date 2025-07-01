@extends('admin.layouts.app')

@section('content')
<div class="container my-4" style="max-width: 850px">
    <h3 class="mb-4">
        🧾 Chi tiết đơn hàng <span class="text-primary">#{{ $order->id }}</span>
    </h3>

    {{-- Thông tin đơn hàng --}}
    <div class="row row-cols-1 row-cols-md-2 mb-4 g-3">
        <div>
            <strong>📅 Ngày đặt:</strong> 
            {{ $order->order_date?->format('d/m/Y H:i') ?? $order->created_at->format('d/m/Y H:i') }}
        </div>
        <div>
            <strong>🔁 Trạng thái:</strong> 
            <span class="badge 
                @switch($order->status)
                    @case('PAID') bg-success @break
                    @case('PENDING') bg-warning text-dark @break
                    @default bg-secondary
                @endswitch">
                {{ $order->status }}
            </span>
        </div>
        <div><strong>👨‍💼 Nhân viên bán hàng:</strong> {{ $order->user->name ?? '-' }}</div>
        <div><strong>👤 Khách hàng:</strong> {{ $order->customer_name ?? '---' }}</div>
        <div><strong>📱 Số điện thoại:</strong> {{ $order->phone_number ?? '---' }}</div>
        <div><strong>🏠 Địa chỉ:</strong> {{ $order->shipping_address ?? '---' }}</div>
    </div>

    {{-- Danh sách sản phẩm --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light fw-bold">📚 Sản phẩm trong đơn</div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light text-center">
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
                        <td class="text-center">{{ $item->bookDetail->language ?? '---' }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($item->promotion_price ?? $item->price) }}đ</td>
                        <td class="text-end">
                            {{ number_format(($item->promotion_price ?? $item->price) * $item->quantity) }}đ
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tổng cộng --}}
    <div class="text-end mb-4">
        <p><strong>💰 Tổng tiền:</strong> {{ number_format($order->final_amount) }}đ</p>
        <p><strong>💵 Tiền khách đưa:</strong> {{ number_format($order->final_amount + $order->change_amount) }}đ</p>
        <p><strong>💴 Trả lại:</strong> 
            <span class="text-danger fw-bold">{{ number_format($order->change_amount) }}đ</span>
        </p>
    </div>

    {{-- Hành động --}}
    <div class="d-flex gap-2">
        <a href="{{ route('admin.counter.receipt', $order->id) }}" class="btn btn-outline-secondary">
            🖨 In hóa đơn
        </a>
        <a href="{{ route('admin.counter.pdf', $order->id) }}" class="btn btn-outline-dark">
            ⬇️ Tải PDF
        </a>
        <a href="{{ route('admin.counter.index') }}" class="btn btn-outline-primary">
            ← Quay lại danh sách
        </a>
    </div>
</div>
@endsection
