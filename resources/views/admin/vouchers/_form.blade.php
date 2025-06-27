@extends('layouts.admin')

@section('content')

@if($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>
          @switch($error)
            @case('The code has already been taken.') Mã giảm giá này đã tồn tại rồi. @break
            @case('The start at field must be a valid date.') Trường "Ngày bắt đầu" phải là ngày hợp lệ. @break
            @case('The expires at field must be a valid date.') Trường "Ngày kết thúc" phải là ngày hợp lệ. @break
            @default {{ $error }}
          @endswitch
        </li>
      @endforeach
    </ul>
  </div>
@endif


<form method="POST" action="{{ isset($voucher) ? route('admin.vouchers.update', $voucher) : route('admin.vouchers.store') }}">
  @csrf
  @if(isset($voucher)) @method('PUT') @endif

  {{-- Mã voucher + nút tạo tự động --}}
  <div class="mb-2 d-flex gap-2 align-items-center">
    <label class="form-label">Mã Voucher</label>
    <input type="text" name="code" value="{{ old('code', $voucher->code ?? '') }}" class="form-control" required>
    <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">🎲 Ngẫu nhiên</button>
  </div>

  <div class="mb-2">
    <label>Loại giảm</label>
    <select name="type" class="form-select">
      <option value="percent" {{ old('type', $voucher->type ?? '') == 'percent' ? 'selected' : '' }}>Phần trăm</option>
      <option value="fixed" {{ old('type', $voucher->type ?? '') == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
    </select>
  </div>

  <div class="mb-2">
    <label>Giá trị giảm</label>
    <input type="number" name="value" class="form-control" value="{{ old('value', $voucher->value ?? '') }}">
  </div>

  <div class="mb-2">
    <label>Giảm tối đa (nếu phần trăm)</label>
    <input type="number" name="max_discount" class="form-control" value="{{ old('max_discount', $voucher->max_discount ?? '') }}">
  </div>

  <div class="mb-2">
    <label>Lượt dùng tối đa</label>
    <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $voucher->usage_limit ?? '') }}">
  </div>

  <div class="mb-2">
    <label>Ngày bắt đầu</label>
    <input type="datetime-local" name="start_at" class="form-control"
      value="{{ old('start_at', isset($voucher->start_at) ? optional($voucher->start_at)->format('Y-m-d\TH:i') : '') }}">
  </div>

  <div class="mb-2">
    <label>Ngày kết thúc</label>
    <input type="datetime-local" name="expires_at" class="form-control"
      value="{{ old('expires_at', isset($voucher->expires_at) ? optional($voucher->expires_at)->format('Y-m-d\TH:i') : '') }}">
  </div>

  {{-- Kích hoạt switch --}}
  <div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" role="switch" name="is_active" value="1"
      {{ old('is_active', $voucher->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label">Kích hoạt voucher</label>
  </div>

  {{-- Chọn sản phẩm --}}
  @if(isset($allProducts))
    <div class="mb-3">
      <label>Áp dụng cho sản phẩm</label>
      <select name="product_ids[]" multiple class="form-select">
        @foreach($allProducts as $product)
          <option value="{{ $product->id }}"
            {{ in_array($product->id, old('product_ids', isset($voucher) ? $voucher->products->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
            {{ $product->name }} ({{ number_format($product->price) }}đ)
          </option>
        @endforeach
      </select>
      <small class="text-muted">Giữ Ctrl hoặc Cmd để chọn nhiều</small>
    </div>
  @endif

  <button class="btn btn-success">💾 Lưu lại</button>
  <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">↩️ Quay lại</a>
</form>

<script>
function generateCode() {
  const rand = 'SALE' + Math.floor(1000 + Math.random() * 9000);
  document.querySelector('[name="code"]').value = rand;
}
</script>

@endsection
