<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookDetail;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'bookDetail.book'])->get();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $request->validate([
            'status' => 'required|in:visible,hidden,pending',
        ]);

        $review->update(['status' => $request->status]);

        return redirect()->route('admin.reviews.index')->with('success', 'Sửa trạng thái bình luận thành công');
    }

    public function create($bookDetailId)
    {
        // Lấy chỉ các đánh giá liên quan đến bookDetailId hiện tại
        $reviews = Review::with(['user', 'bookDetail.book'])
            ->where('book_detail_id', $bookDetailId)
            ->where('status', 'visible')
            ->paginate(5);

        $userId = Auth::id();

        // Fetch book detail
        $bookDetail = BookDetail::with('book')->findOrFail($bookDetailId);
        if (!$bookDetail) {
            return redirect()->route('home')->with('error', 'Sản phẩm không tồn tại.');
        }

        // Kiểm tra xem người dùng đã mua sản phẩm chưa
        $hasPurchased = Auth::check() ? $this->hasUserPurchasedBook($userId, $bookDetailId) : false;

        // Kiểm tra xem người dùng đã đánh giá chưa
        $existingReview = Auth::check() ? Review::where('user_id', $userId)
            ->where('book_detail_id', $bookDetailId)
            ->first() : null;

        return view('client.reviews.create', compact('bookDetail', 'bookDetailId', 'reviews', 'hasPurchased', 'existingReview'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_detail_id' => 'required|exists:book_details,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $userId = Auth::id();

        // Verify purchase again
        $hasPurchased = $this->hasUserPurchasedBook($userId, $request->book_detail_id);

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'Bạn chưa mua sản phẩm này.');
        }

        Review::create([
            'user_id' => $userId,
            'book_detail_id' => $request->book_detail_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'visible',
            'created_at' => now(),
        ]);

        return redirect()->route('reviews.create', ['bookDetailId' => $request->book_detail_id])
                ->with('success', 'Đánh giá thành công');
    }

    private function hasUserPurchasedBook($userId, $bookDetailId)
    {
        return OrderItem::where('ebook_variant_id', $bookDetailId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', 'COMPLETED');
            })->exists();
    }
}