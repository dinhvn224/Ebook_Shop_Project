<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $booksQuery = $category->books()
            ->whereHas('details', fn($q) => $q->where('is_active', true))
            ->with([
                'details' => fn($q) => $q->where('is_active', true),
                'images',
                'author',
                'publisher'
            ]);

        // Lọc theo khoảng giá
        if ($priceRange = $request->input('price')) {
            [$min, $max] = explode('-', $priceRange);
            $max = $max === 'Infinity' ? 1e9 : $max;

            $booksQuery->whereHas('details', function ($q) use ($min, $max) {
                $q->whereBetween('price', [(float)$min, (float)$max]);
            });
        }

        // Lọc theo nhà xuất bản
        if ($publisherId = $request->input('publisher')) {
            $booksQuery->where('publisher_id', $publisherId);
        }

        // Sắp xếp
        match ($request->input('sort')) {
            'price-asc'  => $booksQuery->orderBy('details.price'),
            'price-desc' => $booksQuery->orderByDesc('details.price'),
            'name-asc'   => $booksQuery->orderBy('name'),
            'latest'     => $booksQuery->latest(),
            default      => null
        };

        $books = $booksQuery->paginate(12)->withQueryString();
        $publishers = Publisher::all();

        return view('client.categories.show', compact('category', 'books', 'publishers'));
    }
}
