@extends('layouts.admin')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>🎟 Danh sách Voucher</h4>
    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">+ Thêm mới</a>
  </div>

  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
      <select name="expiry" class="form-select">
        <option value="">-- Lọc theo thời gian --</option>
        <option value="soon" {{ request('expiry') === 'soon' ? 'selected' : '' }}>Sắp hết hạn</option>
        <option value="expired" {{ request('expiry') === 'expired' ? 'selected' : '' }}>Đã hết hạn</option>
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-secondary">🔍 Lọc</button>
    </div>
  </form>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th>Mã</th>
        <th>Loại</th>
        <th>Giá trị</th>
        <th>Giảm tối đa</th>
        <th>Trạng thái</th>
        <th>Hết hạn</th>
        <th>Lượt dùng</th>
        <th>Sản phẩm áp dụng</th>
        <th width="100"></th>
      </tr>
    </thead>
    <tbody>
      @foreach($vouchers as $voucher)
        <tr>
          <td><strong>{{ $voucher->code }}</strong></td>
          <td>{{ $voucher->type }}</td>
          <td>
            {{ $voucher->type === 'percent' ? $voucher->value . '%' : number_format($voucher->value) . 'đ' }}
          </td>
          <td>{{ $voucher->max_discount ? number_format($voucher->max_discount) . 'đ' : '-' }}</td>
          <td>
            <span class="badge bg-{{ 
              $voucher->status === 'Expired' ? 'secondary' : 
              ($voucher->status === 'Coming Soon' ? 'warning' : 
              ($voucher->status === 'Inactive' ? 'dark' : 'success')) }}">
              {{ $voucher->status }}
            </span>
          </td>
          <td>{{ optional($voucher->expires_at)->format('d/m/Y') ?? '-' }}</td>
          <td>{{ $voucher->used_count }} / {{ $voucher->usage_limit ?? '∞' }}</td>
          <td>
            @php $productCount = $voucher->products->count(); @endphp
            @if($productCount === 0)
              <span class="text-muted fst-italic">Tất cả</span>
            @else
              <span class="badge bg-info text-dark mb-1">{{ $productCount }} sản phẩm</span>
              <ul class="mb-0 small ps-3">
                @foreach($voucher->products as $product)
                  <li>{{ $product->name }}</li>
                @endforeach
              </ul>
            @endif
          </td>
          <td class="text-end">
            <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-sm btn-outline-primary">✏️</a>
            <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}" class="d-inline-block"
              onsubmit="return confirm('Bạn chắc muốn xoá voucher này?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">🗑</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $vouchers->links() }}
@endsection
