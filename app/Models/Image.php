<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'book_id',
        'url',
        'caption',
        'is_main',
        'deleted'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
