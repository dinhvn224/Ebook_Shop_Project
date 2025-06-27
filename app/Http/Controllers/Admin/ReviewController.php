<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

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
}