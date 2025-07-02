@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-primary">
            <i class="fas fa-user-edit me-2"></i> Danh sách Tác giả
        </h4>
        <a href="{{ route('admin.authors.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm mới tác giả
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên tác giả</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $author)
                        <tr>
                            <td>{{ $author->id }}</td>
                            <td class="text-start">{{ $author->name }}</td>
                            <td>{{ $author->created_at ? $author->created_at->format('d/m/Y H:i') : '—' }}</td>
                            <td>
                                <a href="{{ route('admin.authors.edit', $author->id) }}" class="btn btn-warning btn-sm me-1">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('admin.authors.destroy', $author->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa tác giả này?')">
                                        <i class="fas fa-trash-alt"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted text-center py-4">
                                <i class="fas fa-user-slash fa-2x d-block mb-2"></i>
                                Chưa có tác giả nào được tạo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($authors->hasPages())
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div>
                {{ $authors->links('pagination::bootstrap-5') }}
            </div>
            <div class="text-muted small">
                Hiển thị từ {{ $authors->firstItem() ?? 0 }} đến {{ $authors->lastItem() ?? 0 }} / {{ $authors->total() }} tác giả
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
