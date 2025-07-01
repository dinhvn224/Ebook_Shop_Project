<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Hiển thị danh sách đánh giá (tuỳ admin / tác giả xem)
     */
    public function index()
    {
        $reviews = Review::with(['user', 'bookDetail.book'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('client.reviews.index', compact('reviews'));
    }

    /**
     * Lưu đánh giá từ người dùng
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Vui lòng đăng nhập để gửi đánh giá.');
        }

        $validated = $request->validate([
            'book_detail_id' => ['required', 'exists:book_details,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'book_detail_id' => $validated['book_detail_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'status' => 'visible', // hoặc 'pending' nếu cần duyệt
        ]);

        return back()->with('success', '🎉 Cảm ơn bạn đã gửi đánh giá!');
    }

    /**
     * Bật / tắt trạng thái hiển thị đánh giá
     */
    public function toggleStatus(Review $review)
    {
        $review->status = $review->status === 'visible' ? 'hidden' : 'visible';
        $review->save();

        return back()->with('success', '✅ Trạng thái đánh giá đã được cập nhật.');
    }

    /**
     * Xoá mềm đánh giá
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', '🗑️ Đánh giá đã bị xóa.');
    }
}
