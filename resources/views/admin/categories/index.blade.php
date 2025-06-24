<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Danh Mục - Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

    <div class="container mt-4">

        <h1>Quản Lý Danh Mục</h1>

        <!-- Thông báo thành công -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Nút thêm danh mục mới -->
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success mb-3">Thêm Mới Danh Mục</a>

        <!-- Bảng danh sách danh mục -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên</th>
                    <th>Ngày Tạo</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->created_at }}</td>
                    <td>
                        <span class="badge {{ $category->deleted ? 'bg-secondary' : 'bg-success' }}">
                            {{ $category->deleted ? 'Ẩn' : 'Hiển thị' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning">Sửa</a>
                        <!-- Form xóa danh mục -->
                        <form class="d-inline" method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" onsubmit="return confirm('Xóa bản ghi này?')" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Phân trang -->
        <div>
            {{ $categories->links() }} <!-- Hiển thị phân trang -->
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
