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
            'details' => function ($query) {
                $query->where('is_active', true)
                      ->where(function ($q) {
                          $q->where('deleted', 0)->orWhereNull('deleted');
                      });
            }
        ]);

        // Lấy sách liên quan cùng thể loại (loại trừ chính nó)
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->with(['author', 'details' => function ($query) {
                $query->where('is_active', true)
                      ->where(function ($q) {
                          $q->where('deleted', 0)->orWhereNull('deleted');
                      });
            }])
            ->limit(8)
            ->get();

        // Trả về view
        return view('client.book.show', compact('book', 'relatedBooks'));
    }
}
