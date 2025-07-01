<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->q);

        $books = Book::with(['details', 'author', 'images'])
            ->where('deleted', 0)
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhereHas('author', fn($q) => $q->where('name', 'like', "%{$keyword}%"))
                    ->orWhereHas('publisher', fn($q) => $q->where('name', 'like', "%{$keyword}%"))
                    ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$keyword}%"));
            })
            ->paginate(12);

        return view('client.search.index', compact('books', 'keyword'));
    }
}
