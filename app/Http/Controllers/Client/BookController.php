<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function show(Book $book)
    {
        $book->load([
            'author',
            'publisher',
            'category',
            'details.images' => fn($q) => $q->where('is_active', true)->where(function ($q) {
                $q->where('deleted', 0)->orWhereNull('deleted');
            })
        ]);

        $bookDetail = $book->details->first();
        $userId = Auth::id();
        $hasPurchased = Auth::check() ? $this->hasUserPurchasedBook($userId, $book) : false;

        $reviews = Review::with(['user', 'bookDetail'])
            ->whereHas('bookDetail', fn($q) => $q->where('book_id', $book->id)->where('is_active', true))
            ->where('status', 'visible')
            ->latest()
            ->get();

        $avgRating = Review::whereHas('bookDetail', fn($q) => $q->where('book_id', $book->id)->where('is_active', true))
            ->where('status', 'visible')->whereNotNull('rating')->avg('rating') ?? 0;

        $totalReviews = Review::whereHas('bookDetail', fn($q) => $q->where('book_id', $book->id)->where('is_active', true))
            ->where('status', 'visible')->whereNotNull('rating')->count();

        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->with(['author', 'details.images'])
            ->limit(4)->get();

        return view('client.books.show', compact(
            'book', 'bookDetail', 'reviews', 'avgRating', 'totalReviews',
            'relatedBooks', 'hasPurchased'
        ));
    }

    private function hasUserPurchasedBook($userId, $book)
    {
        $bookDetailIds = $book->details->pluck('id');
        return OrderItem::whereIn('ebook_variant_id', $bookDetailIds)
            ->whereHas('order', fn($q) => $q->where('user_id', $userId)->where('status', 'COMPLETED'))
            ->exists();
    }
}
