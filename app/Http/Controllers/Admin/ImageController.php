<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\BookDetail;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    // Hiển thị danh sách ảnh
    public function index(Request $request)
    {
        $bookId = $request->get('book_id');
        $query = Image::query();
        if ($bookId) {
            $query->where('book_id', $bookId);
        }
        $images = $query->paginate(20);
        return view('admin.images.index', compact('images', 'bookId'));
    }

    // Hiển thị form tạo ảnh mới
    public function create(Request $request)
    {
        $bookId = $request->get('book_id');
        $books = Book::all();
        return view('admin.images.create', compact('books', 'bookId'));
    }

    // Lưu ảnh mới
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'image' => 'required|image|max:2048',
            'caption' => 'nullable|string',
            'is_main' => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store('client/img/products', 'public');

        // Nếu chọn là ảnh chính, bỏ is_main của ảnh khác
        if ($request->is_main) {
            Image::where('book_id', $request->book_id)
                ->update(['is_main' => 0]);
        }

        Image::create([
            'book_id' => $request->book_id,
            'url' => $path,
            'caption' => $request->caption,
            'is_main' => $request->is_main ? 1 : 0,
            'deleted' => 0
        ]);

        return redirect()->route('admin.images.index')->with('success', 'Thêm ảnh thành công!');
    }

    // Hiển thị 1 ảnh
    public function show($id)
    {
        $image = Image::findOrFail($id);
        return view('admin.images.show', compact('image'));
    }

    // Hiển thị form sửa ảnh
    public function edit($id)
    {
        $image = Image::findOrFail($id);
        $books = Book::all();
        return view('admin.images.edit', compact('image', 'books'));
    }

    // Cập nhật ảnh
    public function update(Request $request, $id)
    {
        $image = Image::findOrFail($id);
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'image' => 'nullable|image|max:2048',
            'caption' => 'nullable|string',
            'is_main' => 'nullable|boolean',
        ]);

        $data = [
            'book_id' => $request->book_id,
            'caption' => $request->caption,
            'is_main' => $request->is_main ? 1 : 0,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('client/img/products', 'public');
            $data['url'] = $path;
        }

        // Nếu chọn là ảnh chính, bỏ is_main của ảnh khác
        if ($request->is_main) {
            Image::where('book_id', $request->book_id)
                ->update(['is_main' => 0]);
            $data['is_main'] = 1;
        }

        $image->update($data);

        return redirect()->route('admin.images.index')->with('success', 'Cập nhật ảnh thành công!');
    }

    // Xóa ảnh
    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        // Xóa file vật lý nếu tồn tại
        if ($image->url && Storage::disk('public')->exists($image->url)) {
            Storage::disk('public')->delete($image->url);
        }

        $image->delete();
        return redirect()->route('admin.images.index')->with('success', 'Xóa ảnh thành công!');
    }
}
