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

    public function show($id)
    {
        $author = Author::findOrFail($id);
        $books = Book::where('author_id', $id)->with('category', 'publisher')->get();
        return view('client.author_books', compact('author', 'books'));
    }
}
