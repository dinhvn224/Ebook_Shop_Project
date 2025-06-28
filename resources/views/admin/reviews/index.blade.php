@extends('admin.layouts.app')

@section('content')

    <div class="page-header">
        <div class="page-title">
            <h4>Danh sách bình luận</h4>
        </div>
    </div>
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-path">
                        <a class="btn btn-filter" id="filter_search">
                            <img src="{{ asset('assets/img/icons/filter.svg') }}" alt="img">
                            <span><img src="{{ asset('assets/img/icons/closes.svg') }}" alt="img"></span>
                        </a>
                    </div>
                    <div class="search-input">
                        <a class="btn btn-searchset"><img src="{{ asset('assets/img/icons/search-white.svg') }}"
                                alt="img"></a>
                        <div id="DataTables_Table_0_filter" class="dataTables_filter"><label> <input
                                    type="search" class="form-control form-control-sm"
                                    placeholder="Tìm kiếm" aria-controls="DataTables_Table_0"></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" id="filter_inputs">
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" placeholder="Tên người dùng">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" placeholder="Số điện thoại">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" class="datetimepicker cal-icon" placeholder="Chọn ngày">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <select class="select">
                                    <option>Vô hiệu hóa</option>
                                    <option>Kích hoạt</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-sm-6 col-12 ms-auto">
                            <div class="form-group">
                                <a class="btn btn-filters ms-auto"><img
                                        src="{{ asset('assets/img/icons/search-whites.svg') }}" alt="img"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table  datanew">
                    <thead>
                        <tr>
                            <th>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </th>
                            <th>Họ tên</th>
                            <th>Sản phẩm</th>
                            <th>Đánh giá</th>
                            <th>Bình luận</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $review)
                        <tr>
                            <td>
                                <label class="checkboxs">
                                    <input type="checkbox">
                                    <span class="checkmarks"></span>
                                </label>
                            </td>
                            <td>{{ $review->user->name }}</td>
                            <td>{{ $review->bookDetail->book->name }}</td>
                            <td>{{ $review->rating }}</td>
                            <td>{{ $review->comment }}</td>
                            <td>{{ $review->status }}</td>
                            <td>
                                <form action="{{ route('admin.reviews.updateStatus', $review->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-control" onchange="this.form.submit()">
                                        <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="visible" {{ $review->status == 'visible' ? 'selected' : '' }}>Visible</option>
                                        <option value="hidden" {{ $review->status == 'hidden' ? 'selected' : '' }}>Hidden</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection



        