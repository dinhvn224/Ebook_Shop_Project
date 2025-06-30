<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * Hiển thị trang chi tiết sách.
     */
    public function show(Book $book)
    {
        // Load các mối quan hệ
        $book->load([
            'author',
            'publisher',
            'category',
            'details.images' => function ($query) {
                $query->where('is_active', true)
                      ->where(function ($q) {
                          $q->where('deleted', 0)->orWhereNull('deleted');
                      });
            }
        ]);

        // Lấy sách liên quan
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->with(['author', 'details.images'])
            ->limit(4)
            ->get();

        // Trả về view
        return view('client.Book.show', compact('book', 'relatedBooks'));
    }
}
