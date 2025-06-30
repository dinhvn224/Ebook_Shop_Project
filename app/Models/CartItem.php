<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'book_detail_id', 'quantity', 'created_at', 'updated_at', 'deleted'];

    public function bookDetail()
    {
        return $this->belongsTo(BookDetail::class);
    }
}
