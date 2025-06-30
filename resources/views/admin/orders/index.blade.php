@extends('admin.layouts.app')

@section('content')
    <h2 class="mb-4">📦 Danh sách đơn hàng</h2>

    {{-- Form lọc --}}
    <form method="GET" class="row g-3 mb-4 align-items-center">
        <div class="col-md-3">
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control" placeholder="Tên hoặc Email khách hàng" autofocus>
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
            <input type="date" name="from" value="{{ request('from') }}" class="form-control" placeholder="Từ ngày">
        </div>

        <div class="col-md-2">
            <input type="date" name="to" value="{{ request('to') }}" class="form-control" placeholder="Đến ngày">
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1">🔍 Lọc</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary flex-grow-1">🔄 Reset</a>
        </div>
    </form>

    {{-- Bảng danh sách đơn hàng --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle table-hover">
            <thead class="table-light text-center">
                <tr>
                    <th style="width: 8%;">Mã đơn</th>
                    <th style="width: 18%;">Tên KH</th>
                    <th style="width: 12%;">SĐT</th>
                    <th style="width: 22%;">Địa chỉ</th>
                    <th style="width: 12%;">Ngày đặt</th>
                    <th style="width: 12%;">Trạng thái</th>
                    <th style="width: 10%;" class="text-end">Tổng tiền</th>
                    <th style="width: 10%;">Thao tác</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="text-center">#{{ $order->id }}</td>
                        <td>{{ $order->customer_name ?? $order->user->name ?? 'N/A' }}</td>
                        <td class="text-center">{{ $order->phone_number ?? 'N/A' }}</td>
                        <td>{{ Str::limit($order->shipping_address ?? 'N/A', 40) }}</td>
                        <td class="text-center">
                            {{ $order->order_date ? \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') : $order->created_at->format('d/m/Y') }}
                        </td>
                        <td class="text-center">
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
                        <td class="text-end text-nowrap">{{ number_format($order->final_amount) }}đ</td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary" title="Xem chi tiết">
                                <i class="bi bi-eye"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted fst-italic">Không có đơn hàng nào phù hợp.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="mt-4 d-flex justify-content-center">
        {!! $orders->appends(request()->query())->links('pagination::bootstrap-5') !!}
    </div>
@endsection
