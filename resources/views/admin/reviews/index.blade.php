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
                <form method="GET" action="{{ route('admin.reviews.index') }}">
                    <div class="row">
                        <!-- Bộ lọc tìm kiếm -->
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <input type="text" name="search" placeholder="Tìm kiếm bình luận" value="{{ request('search') }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="book_id" class="form-control">
                                    <option value="">Tất cả sách</option>
                                    @foreach (\App\Models\Book::all() as $book)
                                        <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>{{ $book->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="user_id" class="form-control">
                                    <option value="">Tất cả người dùng</option>
                                    @foreach (\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="status" class="form-control">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="visible" {{ request('status') == 'visible' ? 'selected' : '' }}>Visible</option>
                                    <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Hidden</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-6 col-12">
                            <div class="form-group">
                                <select name="rating" class="form-control">
                                    <option value="">Tất cả đánh giá</option>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-sm-6 col-12">
                            <button type="submit" class="btn btn-primary">Lọc</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table datanew">
                <thead>
                    <tr>
                        <th><input type="checkbox"></th>
                        <th>Họ tên</th>
                        <th>Sản phẩm</th>
                        <th>Đánh giá</th>
                        <th>Bình luận</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $review)
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>{{ $review->user->name }}</td>
                            <td>{{ $review->bookDetail->book->name }}</td>
                            <td>{{ $review->rating }}</td>
                            <td>{{ Str::limit($review->comment, 50) }}</td>
                            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
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
            <div class="mt-3">
                {{ $reviews->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
