@extends('admin.layouts.app')

@section('content')
<div class="content">
    <div class="page-header d-flex justify-content-between align-items-center mb-3">
        <div class="page-title">
            <h4>📚 Danh sách sách</h4>
            <h6 class="text-muted">Quản lý thư viện sách</h6>
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
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Bộ lọc --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.books.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-bold">🔍 Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Tên, mã sách..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">📚 Thể Loại</label>
                        <select name="category_id" class="form-select">
                            <option value="">-- Chọn --</option>
                            @foreach($categories ?? [] as $c)
                                <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">✍️ Tác giả</label>
                        <select name="author_id" class="form-select">
                            <option value="">-- Chọn --</option>
                            @foreach($authors ?? [] as $a)
                                <option value="{{ $a->id }}" {{ request('author_id') == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">🏢 Nhà xuất bản</label>
                        <select name="publisher_id" class="form-select">
                            <option value="">-- Chọn --</option>
                            @foreach($publishers ?? [] as $p)
                                <option value="{{ $p->id }}" {{ request('publisher_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-success w-100">Lọc</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Danh sách --}}
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Mã</th>
                        <th>Tên</th>
                        <th>Tác giả</th>
                        <th>NXB</th>
                        <th>Thể loại</th>
                        <th>Mô tả</th>
                        <th>Giá</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $index => $book)
                        <tr>
                            <td>{{ $index + 1 + ($books->currentPage() - 1) * $books->perPage() }}</td>
                            <td>{{ $book->id }}</td>
                            <td class="text-start fw-bold">{{ $book->name }}</td>
                            <td>{{ $book->author->name ?? '—' }}</td>
                            <td>{{ $book->publisher->name ?? '—' }}</td>
                            <td>{{ $book->category->name ?? '—' }}</td>
                            <td class="text-start text-muted">
                                {{ Str::limit($book->description, 50) ?: 'Không có' }}
                            </td>
                            <td>
                                @if($book->details->count())
                                    @php
                                        $min = $book->details->min('price');
                                        $max = $book->details->max('price');
                                    @endphp
                                    {{ number_format($min) }}{{ $min != $max ? ' - ' . number_format($max) : '' }} đ
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.books.edit', $book->id) }}" class="btn btn-primary btn-sm me-1" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.books.destroy', $book->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Xóa" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-book fa-2x d-block mb-2"></i>
                                Không tìm thấy sách nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div>
                {{ $books->links('pagination::bootstrap-5') }}
            </div>
            <div class="text-muted small">
                Hiển thị từ {{ $books->firstItem() ?? 0 }} đến {{ $books->lastItem() ?? 0 }} / {{ $books->total() }} sách
            </div>
        </div>
    </div>
</div>
@endsection
