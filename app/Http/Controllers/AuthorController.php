<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;
use App\Models\Book;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::all();
        return view('client.author', compact('authors'));
    }

    public function show(Request $request, $id)
    {
        $author = Author::findOrFail($id);

        $booksQuery = $author->books()->with(['category', 'publisher', 'details']);

        // Tìm kiếm theo tên
        if ($search = $request->input('search')) {
            $booksQuery->where('name', 'like', '%' . $search . '%');
        }

        // Sắp xếp
        switch ($request->input('sort')) {
            case 'name-asc':
                $booksQuery->orderBy('name');
                break;
            case 'price-asc':
                $booksQuery->orderBy('price');
                break;
            case 'price-desc':
                $booksQuery->orderByDesc('price');
                break;
            case 'latest':
                $booksQuery->latest();
                break;
            default:
                $booksQuery->orderBy('name');
        }

        $books = $booksQuery->paginate(12)->withQueryString();

        return view('client.author_books', compact('author', 'books'));
    }
}
