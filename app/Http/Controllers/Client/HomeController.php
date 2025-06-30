<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\BookDetail;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $books = Book::with(['author', 'publisher', 'category', 'details', 'images' => function($q) {
            $q->where('is_main', 1);
        }])
        ->whereHas('details', function($query) {
            $query->where('is_active', true);
        })
        ->get();

        $categories = Category::all();
        $publishers = \App\Models\Publisher::all();

        return view('client.home', compact('books', 'categories', 'publishers'));
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
                $masp = 'DB' . $maspCounter;
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
                $priceStr = number_format($detail->promotion_price && $detail->promotion_price < $detail->price ? $detail->promotion_price : $detail->price, 0, '', '.');
                $img = 'client/img/products/noimage.png';
                $products[] = [
                    'name' => $book->name,
                    'company' => $book->publisher->name ?? '',
                    'img' => $img,
                    'price' => $priceStr,
                    'star' => rand(3, 5),
                    'rateCount' => rand(10, 999),
                    'promo' => $promo,
                    'detail' => [
                        'tacgia' => $book->author->name ?? 'N/A',
                        'xuatsu' => $book->publisher->name ?? 'N/A'
                    ],
                    'masp' => $masp,
                    'book_id' => $masp
                ];
                $maspCounter++;
            }
        }
        return response()->json($products);
    }
}
