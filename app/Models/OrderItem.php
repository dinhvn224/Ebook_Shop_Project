<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $fillable = ['order_id', 'ebook_variant_id', 'price', 'promotion_price', 'quantity'];

    protected $casts = [
        'price' => 'float',
        'promotion_price' => 'float',
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function bookDetail()
    {
        return $this->belongsTo(BookDetail::class, 'ebook_variant_id');
    }
}
