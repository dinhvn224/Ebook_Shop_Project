<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Nhà Sản Xuất - Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

    <div class="container mt-4">

        <h1>Quản Lý Nhà Sản Xuất</h1>

        <!-- Thông báo thành công -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Nút thêm nhà sản xuất mới -->
        <a href="{{ route('admin.publishers.create') }}" class="btn btn-success mb-3">Thêm Mới Nhà Sản Xuất</a>

        <!-- Bảng danh sách nhà sản xuất -->
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
                @foreach ($publishers as $publisher)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $publisher->name }}</td>
                    <td>{{ $publisher->created_at }}</td>
                    <td>
                        <span class="badge {{ $publisher->deleted ? 'bg-secondary' : 'bg-success' }}">
                            {{ $publisher->deleted ? 'Ẩn' : 'Hiển thị' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.publishers.edit', $publisher->id) }}" class="btn btn-warning">Sửa</a>
                        <!-- Form xóa nhà sản xuất -->
                        <form class="d-inline" method="POST" action="{{ route('admin.publishers.destroy', $publisher->id) }}" onsubmit="return confirm('Xóa bản ghi này?')" style="display:inline;">
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
            {{ $publishers->links() }} <!-- Hiển thị phân trang -->
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
