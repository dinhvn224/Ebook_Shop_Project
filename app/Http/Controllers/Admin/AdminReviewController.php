<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Notification;
use Illuminate\Support\Carbon;

class AdminReviewController extends Controller
{
    /**
     * Danh sách đánh giá (có lọc)
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'bookDetail.book']);

        if ($request->filled('book_id')) {
            $query->whereHas('bookDetail', fn($q) => $q->where('book_id', $request->book_id));
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

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $reviews = $query->latest()->paginate(10);
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Cập nhật trạng thái từ trang danh sách
     */
    public function updateStatus(Request $request, $reviewId)
    {
        $review = Review::with(['user', 'bookDetail.book'])->findOrFail($reviewId);

        $request->validate([
            'status' => 'required|in:visible,hidden,pending',
        ]);

        $oldStatus = $review->status;
        $review->update(['status' => $request->status]);

        // Nếu chuyển từ "pending" → "visible", gửi thông báo
        if ($oldStatus === 'pending' && $request->status === 'visible') {
            $user = $review->user;
            $book = optional($review->bookDetail)->book;

            if ($user && $book) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Đánh giá đã được duyệt',
                    'message' => '🎉 Đánh giá của bạn cho sản phẩm <strong>"' . e($book->name) . '"</strong> đã được duyệt.',
                    'type' => 'system',
                    'is_read' => false,
                    'created_at' => Carbon::now(),
                ]);
            }
        }

        return redirect()->route('admin.reviews.index')->with('success', 'Cập nhật trạng thái thành công.');
    }

    /**
     * Cập nhật nhanh trạng thái review (AJAX từ trang chi tiết sách)
     */
    public function inlineUpdateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:reviews,id',
            'status' => 'required|in:visible,hidden,pending',
        ]);

        $review = Review::with(['user', 'bookDetail.book'])->find($request->id);

        if (!$review) {
            return response()->json(['message' => 'Không tìm thấy đánh giá.'], 404);
        }

        $oldStatus = $review->status;
        $review->status = $request->status;
        $review->save();

        // Gửi thông báo nếu vừa được duyệt
        if ($oldStatus === 'pending' && $review->status === 'visible') {
            $user = $review->user;
            $book = optional($review->bookDetail)->book;

            if ($user && $book) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Đánh giá đã được duyệt',
                    'message' => '🎉 Đánh giá của bạn cho sản phẩm <strong>"' . e($book->name) . '"</strong> đã được duyệt.',
                    'type' => 'system',
                    'is_read' => false,
                    'created_at' => Carbon::now(),
                ]);
            }
        }

        return response()->json(['message' => 'Cập nhật trạng thái thành công.']);
    }

    // ✅ Xoá đánh giá
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'Xóa bình luận thành công.');
    }

    // Hiển thị chi tiết bình luận
    public function show($id)
    {
        $review = Review::with(['user', 'bookDetail.book'])->findOrFail($id);
        return view('admin.reviews.show', compact('review'));
    }

    // Lưu phản hồi của admin
    public function reply(Request $request, $id)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:1000',
        ]);

        $review = Review::findOrFail($id);
        $review->admin_reply = $request->admin_reply;
        $review->save();

        return redirect()->route('admin.reviews.show', $id)->with('success', 'Phản hồi đã được gửi.');
    }
}
