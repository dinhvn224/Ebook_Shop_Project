@extends('layouts.admin')

@section('content')
  <h4 class="mb-3">🔗 Gán mã giảm giá cho sản phẩm</h4>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form method="GET" class="row g-2 mb-4 align-items-end">
    <div class="col-md-4">
      <input type="text" name="search" class="form-control" placeholder="🔍 Tìm sản phẩm theo tên..." value="{{ request('search') }}">
    </div>
    <div class="col-md-4">
      <select name="voucher" class="form-select">
        <option value="">-- Lọc theo mã giảm giá --</option>
        @foreach($vouchers as $v)
          <option value="{{ $v->id }}" {{ request('voucher') == $v->id ? 'selected' : '' }}>
            {{ $v->code }}
          </option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <button class="btn btn-secondary">Lọc</button>
    </div>
  </form>

  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th>Sản phẩm</th>
        <th>Giá</th>
        <th>Mã đã gán</th>
        <th>Giảm</th>
        <th width="200">Chọn mã</th>
      </tr>
    </thead>
    <tbody>
      @foreach($products as $product)
        @php
          $voucher = $product->vouchers->first();
          $discount = 0;

          if ($voucher) {
              $discount = $voucher->type === 'percent'
                  ? $product->price * $voucher->value / 100
                  : $voucher->value;

              if ($voucher->type === 'percent' && $voucher->max_discount) {
                  $discount = min($discount, $voucher->max_discount);
              }

              $discount = min($discount, $product->price);
          }
        @endphp
        <tr>
          <td><strong>{{ $product->name }}</strong></td>
          <td>{{ number_format($product->price) }}đ</td>
          <td>
            @if($voucher)
              <form method="POST" action="{{ route('admin.voucher-products.detach') }}" class="d-inline-block">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="voucher_id" value="{{ $voucher->id }}">
                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Gỡ mã {{ $voucher->code }}?')">
                  {{ $voucher->code }} ✖
                </button>
              </form>
            @else
              <span class="text-muted">Chưa gán</span>
            @endif
          </td>
          <td>
            {!! $discount ? '<span class="text-danger fw-bold">− ' . number_format($discount) . 'đ</span>' : '<span class="text-muted">—</span>' !!}
          </td>
          <td>
            <form method="POST" action="{{ route('admin.voucher-products.attach') }}">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">
              <select name="voucher_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">-- Chọn mã --</option>
                @foreach($vouchers as $v)
                  <option value="{{ $v->id }}">{{ $v->code }} ({{ $v->type === 'percent' ? $v->value.'%' : number_format($v->value).'đ' }})</option>
                @endforeach
              </select>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $products->links() }}
@endsection
