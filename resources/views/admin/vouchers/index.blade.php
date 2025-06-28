@extends('admin.layouts.app')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">🎟 <strong>Quản lý mã giảm giá</strong></h4>
    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
      ➕ Thêm mới
    </a>
  </div>

  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
      <label for="expiry" class="form-label fw-semibold">Lọc theo thời gian</label>
      <select name="expiry" id="expiry" class="form-select">
        <option value="">-- Tất cả --</option>
        <option value="soon" {{ request('expiry') === 'soon' ? 'selected' : '' }}>Sắp hết hạn</option>
        <option value="expired" {{ request('expiry') === 'expired' ? 'selected' : '' }}>Đã hết hạn</option>
      </select>
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button type="submit" class="btn btn-outline-secondary w-100">
        🔍 Lọc
      </button>
    </div>
  </form>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="table-responsive shadow-sm border rounded">
    <table class="table table-hover table-striped align-middle mb-0">
      <thead class="table-light text-center">
        <tr>
          <th>Mã</th>
          <th>Loại</th>
          <th>Giá trị</th>
          <th>Giảm tối đa</th>
          <th>Trạng thái</th>
          <th>Hết hạn</th>
          <th>Lượt dùng</th>
          <th class="text-nowrap">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($vouchers as $voucher)
          <tr>
            <td class="text-center fw-bold">{{ $voucher->code }}</td>
            <td class="text-center text-capitalize">{{ $voucher->type }}</td>
            <td class="text-center">
              {{ $voucher->type === 'percent' ? $voucher->value . '%' : number_format($voucher->value) . 'đ' }}
            </td>
            <td class="text-center">
              {{ $voucher->max_discount ? number_format($voucher->max_discount) . 'đ' : '—' }}
            </td>
            <td class="text-center">
              <span class="badge rounded-pill 
                @switch($voucher->status)
                  @case('Expired') bg-secondary @break
                  @case('Coming Soon') bg-warning text-dark @break
                  @case('Inactive') bg-dark @break
                  @default bg-success
                @endswitch">
                {{ $voucher->status }}
              </span>
            </td>
            <td class="text-center">{{ optional($voucher->expires_at)->format('d/m/Y') ?? '—' }}</td>
            <td class="text-center">
              {{ $voucher->used_count }} / {{ $voucher->usage_limit ?? '∞' }}
            </td>
            <td class="text-center">
              <div class="btn-group btn-group-sm">
                <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-outline-primary" title="Chỉnh sửa">
                  ✏️
                </a>
                <form method="POST" action="{{ route('admin.vouchers.destroy', $voucher) }}" 
                      onsubmit="return confirm('Bạn chắc muốn xoá voucher này?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-outline-danger" title="Xóa">🗑</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-4">
              Không có voucher nào trong danh sách.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-center mt-4">
    {{ $vouchers->appends(request()->query())->links('pagination::bootstrap-5') }}
  </div>
@endsection
