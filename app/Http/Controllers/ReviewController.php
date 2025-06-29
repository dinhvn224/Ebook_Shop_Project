<?php

namespace App\Http\Controllers;

use App\Models\BookDetail;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'bookDetail.book']);

        // 🔍 Lọc theo sách (từ book_id, phải join qua bookDetail)
        if ($request->filled('book_id')) {
            $query->whereHas('bookDetail', function ($q) use ($request) {
                $q->where('book_id', $request->book_id);
            });
        }

        // 🔍 Lọc theo user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // 🔍 Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔍 Lọc theo rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // 🔍 Tìm kiếm trong comment
        if ($request->filled('search')) {
            $query->where('comment', 'like', '%' . $request->search . '%');
        }

        $reviews = $query->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function statistics()
    {
        $stats = [
            'by_book' => Review::select('book_details.book_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as review_count'))
                ->join('book_details', 'reviews.book_detail_id', '=', 'book_details.id')
                ->groupBy('book_details.book_id')
                ->with(['bookDetail.book'])
                ->get(),
            'by_user' => Review::select('user_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(*) as review_count'))
                ->groupBy('user_id')
                ->with('user')
                ->get(),
        ];
        return view('admin.reviews.statistics', compact('stats'));
    }

    public function updateStatus(Request $request, $review)
    {
        $review = Review::with(['user', 'bookDetail.book'])->findOrFail($review);

        $request->validate([
            'status' => 'required|in:visible,hidden,pending',
        ]);

        $oldStatus = $review->status;
        $review->update(['status' => $request->status]);

        // 🔔 Gửi thông báo khi duyệt
        if ($oldStatus === 'pending' && $request->status === 'visible') {
            $user = $review->user;
            $book = $review->bookDetail->book;

            if ($user && $book) {
                Notification::create([
                    'user_id' => $user->id,
                    'title'   => 'Đánh giá đã được duyệt',
                    'message' => '🎉 Đánh giá của bạn cho sản phẩm <strong>"' . e($book->name) . '"</strong> đã được duyệt.',
                    'type'    => 'system',
                    'is_read' => false,
                    'created_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.reviews.index')->with('success', 'Cập nhật trạng thái đánh giá thành công.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_detail_id' => 'required|exists:book_details,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        $hasPurchased = $this->hasUserPurchasedBook($userId, $request->book_detail_id);

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'Bạn cần mua sản phẩm này trước khi đánh giá.');
        }

        $existingReview = Review::where('user_id', $userId)
            ->where('book_detail_id', $request->book_detail_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Bạn đã gửi đánh giá cho sản phẩm này.');
        }

        Review::create([
            'user_id' => $userId,
            'book_detail_id' => $request->book_detail_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Đánh giá đã được gửi và đang chờ duyệt.');
    }

    public function update(Request $request, $review)
    {
        $review = Review::where('user_id', Auth::id())->findOrFail($review);

        if ($review->created_at->diffInHours(now()) > 24) {
            return redirect()->route('book.detail', ['book' => $review->bookDetail->book_id])
                ->with('error', 'Đã quá thời gian cho phép chỉnh sửa đánh giá.');
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);
        $review->refresh();

        return redirect()->route('book.detail', ['book' => $review->bookDetail->book_id])
            ->with('success', 'Đánh giá đã được cập nhật và đang chờ duyệt.');
    }

    private function hasUserPurchasedBook($userId, $bookDetailId)
    {
        return OrderItem::where('ebook_variant_id', $bookDetailId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('status', 'COMPLETED');
            })->exists();

        
    }
}