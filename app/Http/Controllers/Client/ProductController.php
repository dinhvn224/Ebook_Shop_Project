<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Book;

class ProductController extends Controller
{
    public function show($id)
    {
        $book = Book::with([
            'author',
            'publisher',
            'images',
            'details.reviews.user',
        ])->findOrFail($id);

        $relatedBooks = $this->getRelatedBooks($book);

        return view('client.products.show', compact('book', 'relatedBooks'));
    }

    private function getRelatedBooks(Book $book)
    {
        if (!$book->category_id) {
            return collect(); // Không có danh mục, trả về danh sách rỗng
        }

        return Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->whereHas('details', function ($query) {
                $query->where('is_active', true);
            })
            ->with([
                'images',
                'author',
                'details' => function ($query) {
                    $query->where('is_active', true);
                },
            ])
            ->take(6)
            ->get();
    }
}
