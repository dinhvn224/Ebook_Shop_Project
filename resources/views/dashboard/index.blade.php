@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  <h1 class="h2">Welcome, {{ Auth::user()->name }}</h1>
  <p>Quick Overview of the System</p>

  <!-- Example cards or charts -->
  <div class="row">
    <div class="col-md-4">
      <div class="card">Tổng số sản phẩm</div>
    </div>
    <div class="col-md-4">
      <div class="card">Doanh thu hôm nay</div>
    </div>
  </div>
@endsection
