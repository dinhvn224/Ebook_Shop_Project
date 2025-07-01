<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Notification;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'bookDetail.book']);

        if ($request->filled('book_id')) {
            $query->whereHas('bookDetail', function ($q) use ($request) {
                $q->where('book_id', $request->book_id);
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('search')) {
            $query->where('comment', 'like', '%' . $request->search . '%');
        }

        $reviews = $query->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Request $request, $reviewId)
    {
        $review = Review::with(['user', 'bookDetail.book'])->findOrFail($reviewId);

        $request->validate([
            'status' => 'required|in:visible,hidden,pending',
        ]);

        $oldStatus = $review->status;
        $review->update(['status' => $request->status]);

        if ($oldStatus === 'pending' && $request->status === 'visible') {
            $user = $review->user;
            $book = $review->bookDetail->book;

            if ($user && $book) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Đánh giá đã được duyệt',
                    'message' => '🎉 Đánh giá của bạn cho sản phẩm <strong>"' . e($book->name) . '"</strong> đã được duyệt.',
                    'type' => 'system',
                    'is_read' => false,
                    'created_at' =>now()
                ]);
            }
        }

        return redirect()->route('admin.reviews.index')->with('success', 'Cập nhật trạng thái thành công.');
    }
}
