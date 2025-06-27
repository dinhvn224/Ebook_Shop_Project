<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $table = 'reviews';
    public $timestamps = false;
    protected $fillable = ['user_id', 'book_detail_id','rating','comment','status'];

    protected $casts = [
        'status' => 'string',
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookDetail()
    {
        return $this->belongsTo(BookDetail::class, 'book_detail_id');
    }
        public function book()
    {
        return $this->bookDetail()->getRelated()->belongsTo(Book::class, 'book_id');
    }
}
