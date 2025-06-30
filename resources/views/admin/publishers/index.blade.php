@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-primary">
            <i class="fas fa-industry me-2"></i> Danh sách Nhà Sản Xuất
        </h4>
        <a href="{{ route('admin.publishers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form Tìm kiếm -->
    <div class="mb-3">
        <form action="{{ route('admin.publishers.index') }}" method="GET" class="row g-2">
            <div class="col-auto">
                <input type="text" name="keyword" class="form-control" placeholder="Tìm theo tên..."
                       value="{{ request('keyword') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
            @if(request('keyword'))
                <div class="col-auto">
                    <a href="{{ route('admin.publishers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Xóa lọc
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Bảng danh sách -->
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên Nhà Sản Xuất</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publishers as $publisher)
                        <tr>
                            <td>{{ $publisher->id }}</td>
                            <td class="text-start">{{ $publisher->name }}</td>
                            <td>{{ $publisher->created_at ? $publisher->created_at->format('d/m/Y H:i') : '—' }}</td>
                            <td>
                                <span class="badge {{ $publisher->deleted ? 'bg-secondary' : 'bg-success' }}">
                                    {{ $publisher->deleted ? 'Ẩn' : 'Hiển thị' }}
                                </span>
                            </td>
                            <td>
                                @if(!$publisher->deleted)
                                    <a href="{{ route('admin.publishers.edit', $publisher->id) }}" class="btn btn-warning btn-sm me-1">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.publishers.destroy', $publisher->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc muốn ẩn nhà sản xuất này?')">
                                            <i class="fas fa-trash-alt"></i> Ẩn
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.publishers.restore', $publisher->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-info btn-sm"
                                            onclick="return confirm('Khôi phục nhà sản xuất này?')">
                                            <i class="fas fa-undo-alt"></i> Khôi phục
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted text-center py-4">
                                <i class="fas fa-building-slash fa-2x mb-2"></i><br>
                                Chưa có nhà sản xuất nào được thêm.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($publishers->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div>
                {{ $publishers->links('pagination::bootstrap-5') }}
            </div>
            <div class="text-muted small">
                Hiển thị từ {{ $publishers->firstItem() ?? 0 }} đến {{ $publishers->lastItem() ?? 0 }} / {{ $publishers->total() }} nhà sản xuất
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
