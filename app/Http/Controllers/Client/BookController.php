<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use App\Models\BookDetail;
use App\Models\OrderItem;
use App\Models\Review;
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
        // Lấy book detail đầu tiên làm mặc định
        $bookDetail = $book->details->first();

        // Lấy danh sách tất cả bình luận
        $reviews = Review::with(['user', 'bookDetail'])
            ->whereHas('bookDetail', function ($query) use ($book) {
                $query->where('book_id', $book->id)->where('is_active', true);
            })
            ->where('status', 'visible')
            ->latest()
            ->get();

        // Kiểm tra trạng thái người dùng
        $userId = Auth::id();
        $hasPurchased = Auth::check() ? $this->hasUserPurchasedBook($userId, $book) : false;

        $existingReview = Auth::check() ? Review::where('user_id', $userId)
            ->whereHas('bookDetail', function ($query) use ($book) {
                $query->where('book_id', $book->id);
            })
            ->whereNotNull('rating')
            ->first() : null;

        // Tính trung bình số sao và tổng số đánh giá chỉ với những review có rating
        $avgRating = Review::whereHas('bookDetail', function ($query) use ($book) {
                $query->where('book_id', $book->id)->where('is_active', true);
            })
            ->where('status', 'visible')
            ->whereNotNull('rating')
            ->avg('rating') ?? 0;

        $totalReviews = Review::whereHas('bookDetail', function ($query) use ($book) {
                $query->where('book_id', $book->id)->where('is_active', true);
            })
            ->where('status', 'visible')
            ->whereNotNull('rating')
            ->count();

        // Trả về view
        return view('client.book.show', compact(
            'book',
            'bookDetail',
            'reviews',
            'hasPurchased',
            'existingReview',
            'avgRating',
            'totalReviews'
        ));
    }


private function hasUserPurchasedBook($userId, $book)
{
    $bookDetailIds = $book->details->pluck('id');

    return OrderItem::whereIn('ebook_variant_id', $bookDetailIds)
        ->whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('status', 'COMPLETED');
        })->exists();
}


}
