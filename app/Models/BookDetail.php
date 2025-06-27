<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class BookDetail extends Model
{
    protected $table = 'book_details';

    protected $fillable = [
        'book_id', 'language', 'size', 'publish_year', 'total_pages', 'description', 'quantity', 'price', 'promotion_price', 'is_active', 'deleted'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deleted' => 'boolean',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function ($builder) {
            $builder->where('deleted', false)->orWhereNull('deleted');
        });
    }
}
