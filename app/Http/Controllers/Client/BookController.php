<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Hiển thị trang chi tiết sách.
     */
    public function show(Book $book)
    {
        $book->load([
            'author',
            'publisher',
            'category',
            'details' => function ($query) {
                $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->where('deleted', 0)->orWhereNull('deleted');
                    });
            }
        ]);

        $relatedByAuthor = \App\Models\Book::where('author_id', $book->author_id)
            ->where('id', '!=', $book->id)
            ->limit(5)->get();

        $relatedByCategory = \App\Models\Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->limit(5)->get();

        $relatedByPublisher = \App\Models\Book::where('publisher_id', $book->publisher_id)
            ->where('id', '!=', $book->id)
            ->limit(5)->get();

        return view('client.Book.show', compact('book', 'relatedByAuthor', 'relatedByCategory', 'relatedByPublisher'));
    }
    public function searchSuggestions(Request $request)
    {
        $query = $request->input('q');
        $books = \App\Models\Book::where('name', 'like', '%' . $query . '%')
            ->limit(10)
            ->pluck('name'); // hoặc ->get(['id', 'name']) nếu muốn trả về cả id

        return response()->json($books);
    }
}
