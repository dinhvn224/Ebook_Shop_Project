<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class ClientReviewController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'book_detail_id' => 'required|exists:book_details,id',
        'comment' => 'nullable|string|max:1000',
        'rating' => 'nullable|integer|between:1,5',
    ]);

    $userId = Auth::id();
    $hasPurchased = $this->hasUserPurchasedBook($userId, $request->book_detail_id);

    if ($hasPurchased) {
        // Chỉ check trùng nếu có rating
        $existingReview = Review::where('user_id', $userId)
            ->where('book_detail_id', $request->book_detail_id)
            ->whereNotNull('rating')
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Bạn đã gửi đánh giá sao cho sản phẩm này.');
        }

        if (!$request->filled('rating')) {
            return redirect()->back()->with('error', 'Vui lòng chọn số sao đánh giá.');
        }
    } else {
        // Khách chưa mua không được gửi rating
        if ($request->filled('rating')) {
            return redirect()->back()->with('error', 'Bạn không thể đánh giá sao khi chưa mua sản phẩm.');
        }
    }

    Review::create([
        'user_id' => $userId,
        'book_detail_id' => $request->book_detail_id,
        'rating' => $hasPurchased ? $request->rating : null,
        'comment' => $request->comment,
        'status' => $hasPurchased ? 'pending' : 'visible',
        'created_at' => now(),
    ]);

    return redirect()->back()->with('success', 'Cảm ơn bạn đã gửi đánh giá.');
}


    public function update(Request $request, $review)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($review);

        if ($review->created_at->diffInHours(now()) > 24) {
            return redirect()->back()->with('error', 'Đã quá thời gian cho phép chỉnh sửa đánh giá.');
        }

        $request->validate([
            'comment' => 'nullable|string|max:1000',
            'rating' => 'nullable|integer|between:1,5',
        ]);

        $hasPurchased = $this->hasUserPurchasedBook(Auth::id(), $review->book_detail_id);

        if ($hasPurchased) {
            if (!$request->filled('rating')) {
                return redirect()->back()->with('error', 'Vui lòng chọn số sao đánh giá.');
            }
        } else {
            if ($request->filled('rating')) {
                return redirect()->back()->with('error', 'Bạn không thể đánh giá sao khi chưa mua sản phẩm.');
            }
        }

        $review->update([
            'rating' => $hasPurchased ? $request->rating : null,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được cập nhật và đang chờ duyệt.');
    }

    private function hasUserPurchasedBook($userId, $bookDetailId)
    {
        return OrderItem::where('ebook_variant_id', $bookDetailId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', 'COMPLETED');
            })->exists();
    }
}
