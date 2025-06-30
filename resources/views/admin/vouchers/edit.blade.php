@extends('admin.layouts.app')

@section('content')
  <div class="container py-4" style="max-width: 700px;">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ isset($voucher) ? route('admin.vouchers.update', $voucher) : route('admin.vouchers.store') }}">
      @csrf
      @if(isset($voucher)) @method('PUT') @endif

      <div class="mb-3 row align-items-center">
        <label for="code" class="col-sm-3 col-form-label">Mã Voucher</label>
        <div class="col-sm-6">
          <input id="code" type="text" name="code" value="{{ old('code', $voucher->code ?? '') }}" class="form-control" required>
        </div>
        <div class="col-sm-3">
          <button type="button" class="btn btn-outline-secondary w-100" onclick="generateCode()" title="Tạo mã ngẫu nhiên">🎲 Ngẫu nhiên</button>
        </div>
      </div>

      <div class="mb-3 row">
        <label for="type" class="col-sm-3 col-form-label">Loại giảm</label>
        <div class="col-sm-9">
          <select id="type" name="type" class="form-select">
            <option value="percent" {{ old('type', $voucher->type ?? '') == 'percent' ? 'selected' : '' }}>Giảm theo %</option>
            <option value="fixed" {{ old('type', $voucher->type ?? '') == 'fixed' ? 'selected' : '' }}>Giảm cố định</option>
          </select>
        </div>
      </div>

      <div class="mb-3 row">
        <label for="value" class="col-sm-3 col-form-label">Giá trị giảm</label>
        <div class="col-sm-9">
          <input id="value" type="number" name="value" class="form-control" value="{{ old('value', $voucher->value ?? '') }}" required>
        </div>
      </div>

      <div class="mb-3 row">
        <label for="max_discount" class="col-sm-3 col-form-label">Giảm tối đa (nếu phần trăm)</label>
        <div class="col-sm-9">
          <input id="max_discount" type="number" name="max_discount" class="form-control" value="{{ old('max_discount', $voucher->max_discount ?? '') }}">
        </div>
      </div>

      <div class="mb-3 row">
        <label for="usage_limit" class="col-sm-3 col-form-label">Lượt dùng tối đa</label>
        <div class="col-sm-9">
          <input id="usage_limit" type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $voucher->usage_limit ?? '') }}">
        </div>
      </div>

      <div class="mb-3 row">
        <label for="start_at" class="col-sm-3 col-form-label">Ngày bắt đầu</label>
        <div class="col-sm-9">
          <input id="start_at" type="datetime-local" name="start_at" class="form-control"
            value="{{ old('start_at', isset($voucher->start_at) ? optional($voucher->start_at)->format('Y-m-d\TH:i') : '') }}">
        </div>
      </div>

      <div class="mb-3 row">
        <label for="expires_at" class="col-sm-3 col-form-label">Ngày kết thúc</label>
        <div class="col-sm-9">
          <input id="expires_at" type="datetime-local" name="expires_at" class="form-control"
            value="{{ old('expires_at', isset($voucher->expires_at) ? optional($voucher->expires_at)->format('Y-m-d\TH:i') : '') }}">
        </div>
      </div>

      <div class="mb-4 row">
        <div class="offset-sm-3 col-sm-9">
          <div class="form-check form-switch">
            <input id="is_active" class="form-check-input" type="checkbox" role="switch" name="is_active" value="1"
              {{ old('is_active', $voucher->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Kích hoạt voucher</label>
          </div>
        </div>
      </div>

      <div class="mb-3 row">
        <div class="offset-sm-3 col-sm-9 d-flex gap-2">
          <button type="submit" class="btn btn-success">💾 Lưu lại</button>
          <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">↩️ Quay lại danh sách</a>
        </div>
      </div>

    </form>
  </div>

  <script>
    function generateCode() {
      const rand = 'SALE' + Math.floor(1000 + Math.random() * 9000);
      document.getElementById('code').value = rand;
    }
  </script>
@endsection
