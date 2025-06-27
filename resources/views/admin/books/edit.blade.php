@extends('admin.layouts.app')
@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Chi tiết sách</h4>
            <h6>Thông tin đầy đủ về sách</h6>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Thông tin sách -->
        <div class="col-lg-8 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thông tin sách</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.books.update', $book->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tên sách</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $book->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Tác giả</label>
                                    <select name="author_id" class="form-select" required>
                                        @foreach($authors as $author)
                                            <option value="{{ $author->id }}" {{ $book->author_id == $author->id ? 'selected' : '' }}>
                                                {{ $author->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Nhà xuất bản</label>
                                    <select name="publisher_id" class="form-select" required>
                                        @foreach($publishers as $publisher)
                                            <option value="{{ $publisher->id }}" {{ $book->publisher_id == $publisher->id ? 'selected' : '' }}>
                                                {{ $publisher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Thể loại</label>
                                    <select name="category_id" class="form-select" required>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $book->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">Mô tả</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $book->description) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Chi tiết sách -->
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Chi tiết sách</h5>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDetailModal">
                        <i class="fas fa-plus"></i> Thêm chi tiết
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Ngôn ngữ</th>
                                    <th>Kích thước</th>
                                    <th>Năm XB</th>
                                    <th>Số trang</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Giá KM</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($book->details as $detail)
                                <tr>
                                    <td>{{ $detail->language }}</td>
                                    <td>{{ $detail->size }}</td>
                                    <td>{{ $detail->publish_year }}</td>
                                    <td>{{ $detail->total_pages }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->price) }} đ</td>
                                    <td>{{ $detail->promotion_price ? number_format($detail->promotion_price) . ' đ' : '-' }}</td>
                                    <td>
                                        <span class="badge {{ $detail->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $detail->is_active ? 'Hiện' : 'Ẩn' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm"
                                                onclick="editDetail({{ $detail->id }}, '{{ $detail->language }}', '{{ $detail->size }}', {{ $detail->publish_year }}, {{ $detail->total_pages }}, '{{ $detail->description }}', {{ $detail->quantity }}, {{ $detail->price }}, {{ $detail->promotion_price ?? 0 }}, {{ $detail->is_active ? 'true' : 'false' }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.books.details.delete', [$book->id, $detail->id]) }}" method="POST" style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Ẩn chi tiết này?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                                            <p>Chưa có chi tiết sách nào</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin chi tiết -->
        <div class="col-lg-4 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thông tin tổng quan</h5>
                </div>
                <div class="card-body">
                    <div class="productdetails">
                        <ul class="product-bar">
                            <li>
                                <h4>ID</h4>
                                <h6>{{ $book->id }}</h6>
                            </li>
                            <li>
                                <h4>Tên sách</h4>
                                <h6>{{ $book->name }}</h6>
                            </li>
                            <li>
                                <h4>Tác giả</h4>
                                <h6>{{ $book->author->name ?? 'N/A' }}</h6>
                            </li>
                            <li>
                                <h4>Nhà xuất bản</h4>
                                <h6>{{ $book->publisher->name ?? 'N/A' }}</h6>
                            </li>
                            <li>
                                <h4>Thể loại</h4>
                                <h6>{{ $book->category->name ?? 'N/A' }}</h6>
                            </li>
                            <li>
                                <h4>Số chi tiết</h4>
                                <h6>{{ $book->details->count() }}</h6>
                            </li>
                            <li>
                                <h4>Tổng số lượng</h6>
                                <h6>{{ $book->details->sum('quantity') }}</h6>
                            </li>
                            <li>
                                <h4>Giá thấp nhất</h4>
                                <h6>{{ $book->details->count() > 0 ? number_format($book->details->min('price')) . ' đ' : 'N/A' }}</h6>
                            </li>
                            <li>
                                <h4>Giá cao nhất</h4>
                                <h6>{{ $book->details->count() > 0 ? number_format($book->details->max('price')) . ' đ' : 'N/A' }}</h6>
                            </li>
                            <li>
                                <h4>Tạo vào</h4>
                                <h6>{{ $book->created_at->format('d/m/Y H:i') }}</h6>
                            </li>
                            <li>
                                <h4>Cập nhật vào</h4>
                                <h6>{{ $book->updated_at->format('d/m/Y H:i') }}</h6>
                            </li>
                            <li>
                                <h4>Mô tả</h4>
                                <h6>{{ $book->description ?: 'Không có mô tả' }}</h6>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thêm chi tiết -->
<div class="modal fade" id="addDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm chi tiết sách</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.books.details.add', $book->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Ngôn ngữ</label>
                                <input type="text" name="language" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Kích thước</label>
                                <input type="text" name="size" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Năm xuất bản</label>
                                <input type="number" name="publish_year" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Số trang</label>
                                <input type="number" name="total_pages" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Số lượng</label>
                                <input type="number" name="quantity" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Giá</label>
                                <input type="number" step="0.01" name="price" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Giá khuyến mãi</label>
                                <input type="number" step="0.01" name="promotion_price" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Trạng thái</label>
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
                                    <label class="form-check-label">Hiện</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Thêm chi tiết</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Sửa chi tiết -->
<div class="modal fade" id="editDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sửa chi tiết sách</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDetailForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Ngôn ngữ</label>
                                <input type="text" name="language" id="edit_language" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Kích thước</label>
                                <input type="text" name="size" id="edit_size" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Năm xuất bản</label>
                                <input type="number" name="publish_year" id="edit_publish_year" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Số trang</label>
                                <input type="number" name="total_pages" id="edit_total_pages" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Số lượng</label>
                                <input type="number" name="quantity" id="edit_quantity" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Giá</label>
                                <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Giá khuyến mãi</label>
                                <input type="number" step="0.01" name="promotion_price" id="edit_promotion_price" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Trạng thái</label>
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" value="1" id="edit_is_active" class="form-check-input">
                                    <label class="form-check-label">Hiện</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.product-bar {
    list-style: none;
    padding: 0;
    margin: 0;
}

.product-bar li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f0f0f0;
}

.product-bar li:last-child {
    border-bottom: none;
}

.product-bar h4 {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.product-bar h6 {
    font-size: 14px;
    color: #666;
    margin: 0;
    text-align: right;
    max-width: 60%;
}

.card {
    border: 1px solid #e3e6f0;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75rem;
}
</style>

<script>
function editDetail(id, language, size, publishYear, totalPages, description, quantity, price, promotionPrice, isActive) {
    document.getElementById('edit_language').value = language;
    document.getElementById('edit_size').value = size;
    document.getElementById('edit_publish_year').value = publishYear;
    document.getElementById('edit_total_pages').value = totalPages;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_quantity').value = quantity;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_promotion_price').value = promotionPrice;
    document.getElementById('edit_is_active').checked = isActive === 'true';

    document.getElementById('editDetailForm').action = '{{ route("admin.books.details.update", ["book" => $book->id, "detail" => ":id"]) }}'.replace(':id', id);

    new bootstrap.Modal(document.getElementById('editDetailModal')).show();
}
</script>
@endsection
