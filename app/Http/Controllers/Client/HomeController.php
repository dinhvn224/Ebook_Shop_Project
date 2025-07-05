<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookDetail;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Trang chủ - hiển thị danh sách sách và danh mục
     */
    public function index()
    {
        $newestBooks = Book::with([
            'author',
            'details' => fn($q) => $q->active(),
            'images'  => fn($q) => $q->mainActive()
        ])
            ->whereHas('details', fn($q) => $q->active())
            ->latest()
            ->take(10)
            ->get();

        $promotionalBooks = Book::with([
            'author',
            'details' => fn($q) => $q->active()->whereNotNull('promotion_price'),
            'images'  => fn($q) => $q->mainActive()
        ])
            ->whereHas(
                'details',
                fn($q) =>
                $q->active()
                    ->whereNotNull('promotion_price')
                    ->whereColumn('promotion_price', '<', 'price')
            )
            ->take(10)
            ->get();

        $categories = Category::latest()->get();
        $publishers = Publisher::all();
        $popularCategories = Category::orderByDesc('id')->take(4)->get();

        // ✅ Thêm danh sách đánh giá từ người dùng
        $reviews = \App\Models\Review::with('user')
            ->where('status', 'visible')
            ->latest()
            ->take(6)
            ->get();

        return view('client.home', compact(
            'newestBooks',
            'promotionalBooks',
            'categories',
            'publishers',
            'popularCategories',
            'reviews' // 👈 thêm biến này vào view
        ));
    }


    /**
     * API: Trả về danh sách sản phẩm có phân trang
     */
    public function getProductsData()
    {
        $bookDetails = BookDetail::active()
            ->with([
                'book.author',
                'book.publisher',
                'book.images' => fn($q) => $q->mainActive(),
                'reviews'
            ])
            ->paginate(20);

        $products = $bookDetails->map(function ($detail) {
            $book = $detail->book;
            $image = $book->images->first();
            // Lấy ảnh mặc định từ DB nếu không có ảnh
            $defaultImage = \App\Models\Image::first();
            $imageUrl = $image ? asset($image->url) : ($defaultImage ? asset($defaultImage->url) : '');

            $promoPrice = $detail->promotion_price;
            $price = $promoPrice ?? $detail->price;

            return [
                'masp'   => $detail->id,
                'book_id' => $book->id,
                'name'   => $book->name,
                'company' => $book->publisher->name ?? 'N/A',
                'img'    => $imageUrl,
                'price'  => number_format($price, 0, '', '.'),
                'original_price' => number_format($detail->price, 0, '', '.'),
                'star'   => round($detail->reviews->avg('rating') ?? 0, 1),
                'rateCount' => $detail->reviews->count(),
                'promo' => [
                    'name'  => 'giamgia',
                    'value' => ($promoPrice && $promoPrice < $detail->price)
                        ? number_format($detail->price - $promoPrice, 0, '', '.')
                        : '0'
                ],
                'detail' => [
                    'tacgia'  => $book->author->name ?? 'N/A',
                    'nxb'     => $book->publisher->name ?? 'N/A',
                    'publish_year' => $detail->publish_year,
                    'total_pages'  => $detail->total_pages,
                ]
            ];
        });

        return response()->json([
            'data' => $products,
            'links' => [
                'first' => $bookDetails->url(1),
                'last'  => $bookDetails->url($bookDetails->lastPage()),
                'prev'  => $bookDetails->previousPageUrl(),
                'next'  => $bookDetails->nextPageUrl(),
            ],
            'meta' => [
                'current_page' => $bookDetails->currentPage(),
                'from'         => $bookDetails->firstItem(),
                'last_page'    => $bookDetails->lastPage(),
                'path'         => $bookDetails->path(),
                'per_page'     => $bookDetails->perPage(),
                'to'           => $bookDetails->lastItem(),
                'total'        => $bookDetails->total(),
            ],
        ]);
    }
}
