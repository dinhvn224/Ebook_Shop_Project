<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'customer_name',
        'shipping_address',
        'phone_number',
        'final_amount',
        'status',
        'payment_method',
        'order_date',
        'completed_date',
        'change_amount',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'completed_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        // Thêm withTrashed() nếu bạn muốn lấy luôn item đã xóa mềm
        return $this->hasMany(OrderItem::class);
    }
}
