<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'ebook_variant_id',
        'quantity', 'price', 'promotion_price'
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
