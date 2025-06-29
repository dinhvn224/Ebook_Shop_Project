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

        // Trả về view
        return view('client.book.show', compact('book'));
    }
}
