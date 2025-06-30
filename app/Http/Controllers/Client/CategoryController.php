<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
// ... existing code ...

class CategoryController extends Controller
{
    public function show($id)
    {
        $category = Category::findOrFail($id);
        $books = $category->books()->with(['author', 'publisher', 'details.images'])->get();
        return view('client.category', compact('category', 'books'));
    }
}
