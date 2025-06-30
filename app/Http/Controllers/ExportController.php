<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function exportProductsPdf()
    {
        $books = Book::with(['author', 'publisher', 'category', 'details'])->get();
        $pdf = Pdf::loadView('exports.products', compact('books'));
        return $pdf->download('products.pdf');
    }
}
