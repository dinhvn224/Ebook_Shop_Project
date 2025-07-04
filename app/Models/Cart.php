<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'created_date',
    ];

    public $timestamps = false; // Vì bạn dùng created_date riêng

    /**
     * Mối quan hệ: Cart có nhiều CartItem
     */
    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    /**
     * Mối quan hệ: Cart thuộc về người dùng
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope để chỉ lấy items chưa bị xóa (deleted = false)
     */
    public function activeItems()
    {
        return $this->items()->where('deleted', false);
    }
}
