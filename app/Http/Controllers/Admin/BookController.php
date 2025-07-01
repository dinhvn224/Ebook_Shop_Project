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

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        if ($request->filled('publisher_id')) {
            $query->where('publisher_id', $request->publisher_id);
        }

        $books = $query->paginate(10);
        $authors = Author::all();
        $publishers = Publisher::all();
        $categories = Category::all();

        return view('admin.books.index', compact('books', 'authors', 'publishers', 'categories'));
    }

    public function show($id)
    {
        $book = Book::with(['author', 'publisher', 'category', 'details'])->findOrFail($id);
        return view('admin.books.show', compact('book'));
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
            'name' => 'required',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book = Book::create($request->all());
        return redirect()->route('admin.books.index')->with('success', 'Thêm sách thành công');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $authors = Author::all();
        $publishers = Publisher::all();
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'authors', 'publishers', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        $book->update($request->all());
        return redirect()->route('admin.books.index')->with('success', 'Cập nhật sách thành công');
    }

    public function destroy($id)
    {
        Book::findOrFail($id)->delete();
        return redirect()->route('admin.books.index')->with('success', 'Xoá sách thành công');
    }

    // BookDetail methods...
    public function addDetail(Request $request, $bookId)
    {
        // validate và thêm chi tiết sách
    }

    public function updateDetail(Request $request, $bookId, $detailId)
    {
        // validate và cập nhật chi tiết sách
    }

    public function deleteDetail($bookId, $detailId)
    {
        // xoá chi tiết sách
    }
}
