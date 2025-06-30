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
        $query = \App\Models\Book::with([
            'author',
            'publisher',
            'category',
            'details' => function ($q) {
                $q->where('is_active', true);
            },
            'images' => function ($q) {
                $q->where('is_main', 1);
            }
        ])
            ->whereHas('details', function ($query) {
                $query->where('is_active', true);
            });

        // Tìm kiếm theo tên sách hoặc tác giả
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhereHas('author', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%");
                  });
            });
        }

        // Lọc theo thể loại
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo tác giả
        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }

        // Lọc theo nhà xuất bản
        if ($request->filled('publisher_id')) {
            $query->where('publisher_id', $request->publisher_id);
        }

        // Sắp xếp
        // Sắp xếp theo tên
        if ($request->filled('sort_name')) {
            switch ($request->sort_name) {
                case 'az':
                    $query->orderBy('name', 'asc');
                    break;
                case 'za':
                    $query->orderBy('name', 'desc');
                    break;
            }
        }

        // Sắp xếp theo ngày tạo
        if ($request->filled('sort_date')) {
            switch ($request->sort_date) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
            }
        }

        // Sắp xếp theo giá
        if ($request->filled('sort_price')) {
            switch ($request->sort_price) {
                case 'price_asc':
                    $query->withMin('details', 'price')->orderBy('details_min_price', 'asc');
                    break;
                case 'price_desc':
                    $query->withMin('details', 'price')->orderBy('details_min_price', 'desc');
                    break;
            }
        }

        if ($request->filled('price_range')) {
            [$min, $max] = explode('-', $request->price_range);
            $query->whereHas('details', function($q) use ($min, $max) {
                $q->where('price', '>=', $min)->where('price', '<=', $max);
            });
        }

        // Phân trang
        $books = $query->paginate(9)->appends($request->all());

        // Lấy danh mục, tác giả, nhà xuất bản cho filter
        $categories = \App\Models\Category::all();
        $authors = \App\Models\Author::all();
        $publishers = \App\Models\Publisher::all();

        return view('client.home', compact('books', 'categories', 'authors', 'publishers'));
    }

    public function getProductsData()
    {
        $books = Book::with(['author', 'publisher', 'category', 'details' => function ($query) {
            $query->where('is_active', true);
        }])
            ->whereHas('details', function ($query) {
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
                    'book_id' => $masp // Đảm bảo luôn có book_id
                ];
                $maspCounter++;
            }
        }

        return response()->json($products);
    }
}
