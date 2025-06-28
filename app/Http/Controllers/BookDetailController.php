<?php

// app/Http/Controllers/BookDetailController.php
namespace App\Http\Controllers;

use App\Models\BookDetail;

class BookDetailController extends Controller
{
    public function show($id)
    {
        $bookDetail = BookDetail::findOrFail($id);
        return view('client.product.detail', compact('bookDetail'));
    }
}
