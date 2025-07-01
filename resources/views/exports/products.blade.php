<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Danh sách sản phẩm</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 5px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Danh sách sản phẩm</h2>
    <table>
        <thead>
            <tr>
                <th>Tên sách</th>
                <th>Tác giả</th>
                <th>Thể loại</th>
                <th>Nhà xuất bản</th>
                <th>Giá</th>
                <th>Mô tả</th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $book)
                <tr>
                    <td>{{ $book->name }}</td>
                    <td>{{ $book->author->name ?? '' }}</td>
                    <td>{{ $book->category->name ?? '' }}</td>
                    <td>{{ $book->publisher->name ?? '' }}</td>
                    <td>
                        @if($book->details->count())
                            {{ number_format($book->details->first()->price, 0, '', '.') }} VNĐ
                        @endif
                    </td>
                    <td>{{ $book->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
