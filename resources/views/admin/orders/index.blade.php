@extends('layouts.admin')

@section('content')
    <h2 class="mb-4">📦 Danh sách đơn hàng</h2>

    {{-- Form lọc --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
                placeholder="Tên hoặc Email khách hàng">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Trạng thái --</option>
                @foreach(['PENDING', 'PAID', 'COMPLETED', 'CANCELLED'] as $status)
                    <option value="{{ $status }}" @selected(request('status') == $status)>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="from" value="{{ request('from') }}" class="form-control">
        </div>
        <div class="col-md-2">
            <input type="date" name="to" value="{{ request('to') }}" class="form-control">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">🔍 Lọc</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">🔄 Reset</a>
        </div>
    </form>

    {{-- Danh sách đơn hàng --}}
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Mã đơn</th>
                <th>Tên KH</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <th>Ngày đặt</th>
                <th>Trạng thái</th>
                <th class="text-end">Tổng tiền</th>
                <th>Thao tác</th>
            </tr>
        </thead>

        
        <tbody>
            @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->customer_name ?? $order->user->name ?? 'N/A' }}</td>
                        <td>{{ $order->phone_number ?? 'N/A' }}</td>
                        <td>{{ $order->shipping_address ?? 'N/A' }}</td>
                        <td>{{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') : $order->created_at->format('d/m/Y') }}
                        </td>
                        <td>
                            <span class="badge bg-{{ match ($order->status) {
                    'PENDING' => 'warning',
                    'PAID' => 'info',
                    'COMPLETED' => 'success',
                    'CANCELLED' => 'danger',
                    default => 'secondary'
                } }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="text-end">{{ number_format($order->final_amount) }}đ</td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">Chi tiết</a>
                        </td>
                    </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">Không có đơn hàng nào phù hợp.</td>
                </tr>
            @endforelse

        </tbody>
    </table>

    {{-- Phân trang --}}
    <div class="mt-4 d-flex justify-content-center">
        {!! $orders->appends(request()->query())->links('pagination::bootstrap-5') !!}
    </div>
@endsection