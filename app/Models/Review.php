<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id', 'book_detail_id', 'rating', 'comment', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function bookDetail() {
        return $this->belongsTo(BookDetail::class);
    }
}


