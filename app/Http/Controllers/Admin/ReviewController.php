<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookDetail;
use App\Models\Order;
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
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'You must be logged in to leave a review.');
        }

        $bookDetail = BookDetail::with('book')->findOrFail($bookDetailId);
        $hasPurchased = Order::where('user_id', $user->id)
            ->where('status', 'COMPLETED')
            ->whereHas('items', function ($query) use ($bookDetailId) {
                $query->where('ebook_variant_id', $bookDetailId);
            })
            ->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'You must purchase this book to leave a review.');
        }

        return view('client.reviews.create', compact('bookDetailId', 'bookDetail'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_detail_id' => 'required|exists:book_details,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'You must be logged in to leave a review.');
        }

        $bookDetailId = $request->book_detail_id;
        $hasPurchased = Order::where('user_id', $user->id)
            ->where('status', 'COMPLETED')
            ->whereHas('items', function ($query) use ($bookDetailId) {
                $query->where('ebook_variant_id', $bookDetailId);
            })
            ->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'You must purchase this book to leave a review.');
        }

        Review::create([
            'user_id' => $user->id,
            'book_detail_id' => $bookDetailId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
            'created_at' => NOW(),
        ]);

        return redirect()->route('home.user')->with('success', 'Review submitted successfully and is awaiting approval.');
    }
}