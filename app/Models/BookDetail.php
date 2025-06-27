<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'book_id', 'language', 'size', 'publish_year', 'total_pages',
        'description', 'quantity', 'price', 'promotion_price', 'is_active'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    
}
