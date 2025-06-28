<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['user_id', 'status', 'amount', 'total_amount', 'final_amount', 'ship_amount', 'change_amount', 'payment_method', 'order_date', 'completed_date'];

    protected $casts = [
        'status' => 'string',
        'amount' => 'float',
        'total_amount' => 'float',
        'final_amount' => 'float',
        'ship_amount' => 'float',
        'change_amount' => 'float',
        'order_date' => 'datetime',
        'completed_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
