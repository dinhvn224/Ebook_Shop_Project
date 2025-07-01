@extends('admin.layouts.app')

@section('content')

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>
          @switch($error)
            @case('The code has already been taken.') Mã giảm giá này đã tồn tại rồi. @break
            @case('The start date field must be a valid date.') Trường "Ngày bắt đầu" phải là ngày hợp lệ. @break
            @case('The end date field must be a valid date.') Trường "Ngày kết thúc" phải là ngày hợp lệ. @break
            @default {{ $error }}
          @endswitch
        </li>
      @endforeach
    </ul>
  </div>
@endif

<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">🎟 Tạo mã giảm giá mới</h5>
    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary btn-sm">← Quay lại</a>
  </div>
  <div class="card-body">

    <form method="POST" action="{{ isset($voucher) ? route('admin.vouchers.update', $voucher) : route('admin.vouchers.store') }}">
      @csrf
      @if(isset($voucher)) @method('PUT') @endif

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Mã giảm giá</label>
          <div class="input-group">
            <input type="text" name="code" value="{{ old('code', $voucher->code ?? '') }}" class="form-control" required>
            <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">🎲 Ngẫu nhiên</button>
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Loại giảm</label>
          <select name="discount_type" class="form-select" required>
            <option value="">-- Chọn loại --</option>
            <option value="PERCENT" {{ old('discount_type', $voucher->discount_type ?? '') == 'PERCENT' ? 'selected' : '' }}>Phần trăm</option>
            <option value="FIXED" {{ old('discount_type', $voucher->discount_type ?? '') == 'FIXED' ? 'selected' : '' }}>Số tiền cố định</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Giá trị giảm</label>
          <input type="number" name="discount_value" class="form-control" value="{{ old('discount_value', $voucher->discount_value ?? '') }}" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Giảm tối đa (nếu phần trăm)</label>
          <input type="number" name="max_discount" class="form-control" value="{{ old('max_discount', $voucher->max_discount ?? '') }}">
        </div>

        <div class="col-md-6">
          <label class="form-label">Lượt dùng tối đa</label>
          <input type="number" name="max_uses" class="form-control" value="{{ old('max_uses', $voucher->max_uses ?? '') }}">
        </div>

        <div class="col-md-6">
          <label class="form-label">Điều kiện tối thiểu (VNĐ)</label>
          <input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', $voucher->min_order_amount ?? '') }}">
        </div>

        <div class="col-md-6">
          <label class="form-label">Ngày bắt đầu</label>
          <input type="datetime-local" name="start_date" class="form-control"
            value="{{ old('start_date', isset($voucher->start_date) ? optional($voucher->start_date)->format('Y-m-d\TH:i') : '') }}">
        </div>

        <div class="col-md-6">
          <label class="form-label">Ngày kết thúc</label>
          <input type="datetime-local" name="end_date" class="form-control"
            value="{{ old('end_date', isset($voucher->end_date) ? optional($voucher->end_date)->format('Y-m-d\TH:i') : '') }}">
        </div>

        <div class="col-md-6 d-flex align-items-center">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1"
              {{ old('is_active', $voucher->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label ms-2">Kích hoạt voucher</label>
          </div>
        </div>
      </div>

      <div class="mt-4 d-flex justify-content-end gap-2">
        <button class="btn btn-success">💾 Lưu lại</button>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">↩️ Hủy</a>
      </div>

    </form>
  </div>
</div>

<script>
function generateCode() {
  const rand = 'SALE' + Math.floor(1000 + Math.random() * 9000);
  document.querySelector('[name="code"]').value = rand;
}
</script>

@endsection
