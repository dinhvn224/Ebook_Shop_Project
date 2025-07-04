<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table = 'cart_items';

    protected $fillable = [
        'cart_id',
        'book_detail_id',
        'quantity',
        'deleted',
    ];

    public $timestamps = true; // Sử dụng created_at và updated_at tự động

    /**
     * Mối quan hệ: CartItem thuộc về Cart
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    /**
     * Mối quan hệ: CartItem thuộc về BookDetail
     */
    public function bookDetail()
    {
        return $this->belongsTo(BookDetail::class, 'book_detail_id');
    }
}
