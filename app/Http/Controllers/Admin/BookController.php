<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use App\Models\BookDetail;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['author', 'publisher', 'category', 'details']);

        // Tìm kiếm theo tên sách
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
        }

        // Lọc theo thể loại
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo tác giả
        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // Lọc theo nhà xuất bản
        if ($request->filled('publisher_id')) {
            $query->where('publisher_id', $request->publisher_id);
        }

        $books = $query->paginate(10);

        // Lấy dữ liệu cho dropdown filter
        $authors = Author::all();
        $publishers = Publisher::all();
        $categories = Category::all();

        return view('admin.books.index', compact('books', 'authors', 'publishers', 'categories'));
    }

    public function create()
    {
        $authors = Author::all();
        $publishers = Publisher::all();
        $categories = Category::all();
        return view('admin.books.create', compact('authors', 'publishers', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publisher,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);
        $book = Book::create($request->only(['name', 'author_id', 'publisher_id', 'category_id', 'description']) + ['deleted' => false]);
        return redirect()->route('admin.books.edit', $book->id)->with('success', 'Đã tạo sách, hãy thêm chi tiết sách!');
    }

    public function edit($id)
    {
        $book = Book::with('details')->findOrFail($id);
        $authors = Author::all();
        $publishers = Publisher::all();
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'authors', 'publishers', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publisher,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);
        $book = Book::findOrFail($id);
        $book->update($request->only(['name', 'author_id', 'publisher_id', 'category_id', 'description']));
        return redirect()->route('admin.books.index')->with('success', 'Cập nhật sách thành công!');
    }

    public function destroy($id)
    {
        $book = Book::withoutGlobalScopes()->findOrFail($id);
        $book->update(['deleted' => true]);
        return redirect()->route('admin.books.index')->with('success', 'Đã ẩn sách!');
    }

    // BookDetail CRUD (thêm/xóa/sửa chi tiết sách)
    public function addDetail(Request $request, $book_id)
    {
        $request->validate([
            'language' => 'required|string|max:100',
            'size' => 'required|string|max:50',
            'publish_year' => 'required|integer',
            'total_pages' => 'required|integer',
            'description' => 'nullable|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'promotion_price' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);
        BookDetail::create($request->only(['book_id', 'language', 'size', 'publish_year', 'total_pages', 'description', 'quantity', 'price', 'promotion_price', 'is_active']) + ['book_id' => $book_id, 'deleted' => false]);
        return redirect()->route('admin.books.edit', $book_id)->with('success', 'Đã thêm chi tiết sách!');
    }

    public function updateDetail(Request $request, $book_id, $detail_id)
    {
        $request->validate([
            'language' => 'required|string|max:100',
            'size' => 'required|string|max:50',
            'publish_year' => 'required|integer',
            'total_pages' => 'required|integer',
            'description' => 'nullable|string',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'promotion_price' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);
        $detail = BookDetail::findOrFail($detail_id);
        $detail->update($request->only(['language', 'size', 'publish_year', 'total_pages', 'description', 'quantity', 'price', 'promotion_price', 'is_active']));
        return redirect()->route('admin.books.edit', $book_id)->with('success', 'Đã cập nhật chi tiết sách!');
    }

    public function deleteDetail($book_id, $detail_id)
    {
        $detail = BookDetail::findOrFail($detail_id);
        $detail->update(['deleted' => true]);
        return redirect()->route('admin.books.edit', $book_id)->with('success', 'Đã ẩn chi tiết sách!');
    }
}
