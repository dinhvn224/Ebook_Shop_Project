@extends('admin.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title">
        <h4>📋 Danh sách đánh giá & bình luận</h4>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.reviews.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label for="search" class="form-label">Tìm kiếm</label>
                    <input type="text" name="search" id="search" placeholder="Nội dung bình luận..." value="{{ request('search') }}" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Sách</label>
                    <select name="book_id" class="form-control">
                        <option value="">Tất cả</option>
                        @foreach (\App\Models\Book::all() as $book)
                            <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>{{ $book->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Người dùng</label>
                    <select name="user_id" class="form-control">
                        <option value="">Tất cả</option>
                        @foreach (\App\Models\User::all() as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="">Tất cả</option>
                        @foreach (['pending' => 'Pending', 'visible' => 'Visible', 'hidden' => 'Hidden'] as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Số sao</label>
                    <select name="rating" class="form-control">
                        <option value="">Tất cả</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Lọc</button>
                </div>
            </div>
        </form>

        <hr>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Người dùng</th>
                        <th>Sản phẩm</th>
                        <th>⭐ Đánh giá</th>
                        <th>Bình luận</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>{{ $review->user->name ?? 'Ẩn danh' }}</td>
                            <td>{{ $review->bookDetail->book->name ?? 'Không xác định' }}</td>
                            <td>{{ $review->rating ?? '-' }}</td>
                            <td>{{ Str::limit($review->comment, 60) }}</td>
                            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'pending' => 'warning',
                                        'visible' => 'success',
                                        'hidden' => 'secondary'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$review->status] ?? 'light' }}">
                                    {{ ucfirst($review->status) }}
                                </span>
                            </td>
                            <td class="d-flex gap-1">
                                {{-- Xem chi tiết --}}
                                <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>

                                {{-- Đổi trạng thái --}}
                                <form action="{{ route('admin.reviews.updateStatus', $review->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="visible" {{ $review->status == 'visible' ? 'selected' : '' }}>Visible</option>
                                        <option value="hidden" {{ $review->status == 'hidden' ? 'selected' : '' }}>Hidden</option>
                                    </select>
                                </form>

                                {{-- Xoá --}}
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xoá bình luận này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Xoá">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Không có bình luận nào được tìm thấy.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $reviews->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
