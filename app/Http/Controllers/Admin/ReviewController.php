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

// public function create($bookDetailId)
//     {
//         $user = Auth::user();
//         if (!$user) {
//             return redirect()->back()->with('error', 'You must be logged in to leave a review.');
//         }

//         $bookDetail = BookDetail::with('book')->findOrFail($bookDetailId);
//         $hasPurchased = Order::where('user_id', $user->id)
//             ->where('status', 'COMPLETED')
//             ->whereHas('items', function ($query) use ($bookDetailId) {
//                 $query->where('ebook_variant_id', $bookDetailId);
//             })
//             ->exists();

//         if (!$hasPurchased) {
//             return redirect()->back()->with('error', 'You must purchase this book to leave a review.');
//         }

//         return view('client.reviews.create', compact('bookDetailId', 'bookDetail'));
//     }

//     public function store(Request $request)
//     {
//         $request->validate([
//             'book_detail_id' => 'required|exists:book_details,id',
//             'rating' => 'required|integer|between:1,5',
//             'comment' => 'required|string|max:1000',
//         ]);

//         $user = Auth::user();
//         if (!$user) {
//             return redirect()->back()->with('error', 'You must be logged in to leave a review.');
//         }

//         $bookDetailId = $request->book_detail_id;
//         $hasPurchased = Order::where('user_id', $user->id)
//             ->where('status', 'COMPLETED')
//             ->whereHas('items', function ($query) use ($bookDetailId) {
//                 $query->where('ebook_variant_id', $bookDetailId);
//             })
//             ->exists();

//         if (!$hasPurchased) {
//             return redirect()->back()->with('error', 'You must purchase this book to leave a review.');
//         }

//         Review::create([
//             'user_id' => $user->id,
//             'book_detail_id' => $bookDetailId,
//             'rating' => $request->rating,
//             'comment' => $request->comment,
//             'status' => 'pending',
//             'created_at' => NOW(),
//         ]);

//         return redirect()->route('home.user')->with('success', 'Review submitted successfully and is awaiting approval.');
//     }
public function create($bookDetailId)
    {
        $userId = Auth::id();

        // Kiểm tra người dùng đã mua sản phẩm chưa
    $hasPurchased = OrderItem::where('ebook_variant_id', $bookDetailId)
        ->whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('status', 'COMPLETED'); // trạng thái ở bảng orders ✅
        })->exists();
        if (!$hasPurchased) {
            return redirect()->route('home')->with('error', 'Bạn cần mua sách này trước khi đánh giá.');
        }

        $bookDetail = BookDetail::with('book')->findOrFail($bookDetailId);
        return view('client.reviews.create', compact('bookDetail', 'bookDetailId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_detail_id' => 'required|exists:book_details,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $userId = Auth::id();

        // Kiểm tra lại một lần nữa
        $hasPurchased = OrderItem::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('status', 'COMPLETED');
        })->where('ebook_variant_id', $request->book_detail_id)->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'Bạn chưa mua sản phẩm này.');
        }

        Review::create([
            'user_id' => $userId,
            'book_detail_id' => $request->book_detail_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return redirect()->route('home')->with('success', 'Đánh giá của bạn đã được gửi và chờ duyệt.');
    }
}