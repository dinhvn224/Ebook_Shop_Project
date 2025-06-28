@extends('admin.layouts.app')
@section('content')
<div class="content">
    <div class="page-header d-flex justify-between align-items-center">
        <div class="page-title">
            <h4>Danh sách sách</h4>
            <h6>Quản lý thư viện sách</h6>
        </div>
        <div class="page-btn">
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Thêm Sách
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="mb-3 fw-bold">Bộ Lọc Sách</h6>
            <form method="GET" action="{{ route('admin.books.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Mã sách, tên sách,..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Thể Loại</label>
                        <select name="category_id" class="form-select">
                            <option value="">Chọn thể loại</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Tác giả</label>
                        <select name="author_id" class="form-select">
                            <option value="">Chọn tác giả</option>
                            @foreach($authors ?? [] as $author)
                                <option value="{{ $author->id }}" {{ request('author_id') == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1 fw-bold">Nhà xuất bản</label>
                        <select name="publisher_id" class="form-select">
                            <option value="">Chọn NXB</option>
                            @foreach($publishers ?? [] as $publisher)
                                <option value="{{ $publisher->id }}" {{ request('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                    {{ $publisher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">Tìm kiếm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Danh sách sách -->
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>MÃ</th>
                        <th>TÊN SÁCH</th>
                        <th>TÁC GIẢ</th>
                        <th>NHÀ XUẤT BẢN</th>
                        <th>THỂ LOẠI</th>
                        <th>MÔ TẢ</th>
                        <th>GIÁ</th>
                        <th>HÀNH ĐỘNG</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $index => $book)
                    <tr>
                        <td>{{ $index + 1 + ($books->currentPage() - 1) * $books->perPage() }}</td>
                        <td>{{ $book->id }}</td>
                        <td class="text-start">
                            <strong>{{ $book->name }}</strong>
                        </td>
                        <td>{{ $book->author->name ?? 'N/A' }}</td>
                        <td>{{ $book->publisher->name ?? 'N/A' }}</td>
                        <td>{{ $book->category->name ?? 'N/A' }}</td>
                        <td class="text-start">
                            <span class="text-muted">
                                {{ Str::limit($book->description, 50) ?: 'Không có mô tả' }}
                            </span>
                        </td>
                        <td>
                            @if($book->details->count() > 0)
                                @php
                                    $minPrice = $book->details->min('price');
                                    $maxPrice = $book->details->max('price');
                                @endphp
                                @if($minPrice == $maxPrice)
                                    {{ number_format($minPrice) }} đ
                                @else
                                    {{ number_format($minPrice) }} - {{ number_format($maxPrice) }} đ
                                @endif
                            @else
                                <span class="text-muted">Chưa có giá</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-success btn-sm me-1" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-primary btn-sm me-1" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Xóa"
                                        onclick="return confirm('Bạn có chắc muốn ẩn sách này?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-book fa-3x mb-3"></i>
                                <p>Chưa có sách nào trong hệ thống</p>
                                <a href="{{ route('admin.books.create') }}" class="btn btn-primary">Thêm sách đầu tiên</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($books->hasPages())
        <div class="card-footer d-flex justify-between align-items-center">
            <div>
                @if($books->onFirstPage())
                    <button class="btn btn-light btn-sm" disabled>← Trước</button>
                @else
                    <a href="{{ $books->previousPageUrl() }}" class="btn btn-light btn-sm">← Trước</a>
                @endif

                <span class="mx-2">Trang {{ $books->currentPage() }} / {{ $books->lastPage() }}</span>

                @if($books->hasMorePages())
                    <a href="{{ $books->nextPageUrl() }}" class="btn btn-light btn-sm">Tiếp →</a>
                @else
                    <button class="btn btn-light btn-sm" disabled>Tiếp →</button>
                @endif
            </div>
            <div>
                <span class="text-muted">Hiển thị {{ $books->firstItem() ?? 0 }} - {{ $books->lastItem() ?? 0 }} trong tổng số {{ $books->total() }} sách</span>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.table th {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    font-weight: 600;
    font-size: 0.875rem;
}

.table td {
    vertical-align: middle;
    font-size: 0.875rem;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.page-title h4 {
    color: #333;
    margin-bottom: 0.25rem;
}

.page-title h6 {
    color: #6c757d;
    font-weight: 400;
}

.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}
</style>
@endsection
