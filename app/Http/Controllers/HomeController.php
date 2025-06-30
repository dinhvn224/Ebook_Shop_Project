<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookDetail;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['author', 'publisher', 'category', 'details', 'images' => function($q) {
            $q->where('is_main', 1);
        }])
        ->whereHas('details', function($query) {
            $query->where('is_active', true);
        });

        // Lọc theo từ khóa tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $books = $query->get();
        $categories = Category::all();

        return view('client.home', compact('books', 'categories'));
    }

    public function getProductsData()
    {
        $books = Book::with(['author', 'publisher', 'category', 'details' => function($query) {
            $query->where('is_active', true);
        }])
        ->whereHas('details', function($query) {
            $query->where('is_active', true);
        })
        ->get();

        $products = [];
        $maspCounter = 1;

        foreach ($books as $book) {
            foreach ($book->details as $detail) {
                // Tạo masp duy nhất
                $masp = 'DB' . $maspCounter;

                // Xác định loại khuyến mãi
                $promo = ['name' => '', 'value' => '0'];
                if ($detail->promotion_price && $detail->promotion_price < $detail->price) {
                    $promo = [
                        'name' => 'giamgia',
                        'value' => number_format($detail->price - $detail->promotion_price, 0, '', '.')
                    ];
                } elseif ($detail->promotion_price && $detail->promotion_price == $detail->price) {
                    $promo = [
                        'name' => 'moiramat',
                        'value' => '0'
                    ];
                }

                // Tạo giá dạng string có dấu chấm
                $priceStr = number_format($detail->promotion_price && $detail->promotion_price < $detail->price ? $detail->promotion_price : $detail->price, 0, '', '.');

                // Lấy ảnh nếu có, nếu không thì lấy noimage
                $img = 'client/img/products/noimage.png';
                // Nếu bạn có trường ảnh trong db thì thay ở đây

                $products[] = [
                    'name' => $book->name,
                    'company' => $book->publisher->name ?? '',
                    'img' => $img,
                    'price' => $priceStr,
                    'star' => rand(3, 5), // random cho đẹp
                    'rateCount' => rand(10, 999),
                    'promo' => $promo,
                    'detail' => [
                        'tacgia' => $book->author->name ?? 'N/A',
                        'xuatsu' => $book->publisher->name ?? 'N/A'
                    ],
                    'masp' => $masp,
                    'book_id' => $book->id // Đảm bảo luôn có book_id
                ];
                $maspCounter++;
            }
        }

        return response()->json($products);
    }
}