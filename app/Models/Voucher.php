<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Book;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'description',
        'discount_type',     // enum: PERCENT or FIXED
        'discount_value',    // số chiết khấu
        'max_uses',          // số lượt dùng tối đa
        'used',              // số lượt đã dùng
        'min_order_amount',  // điều kiện áp dụng
        'is_active',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Trạng thái động của voucher: Active, Expired, Inactive, Coming Soon
     */
    public function getStatusAttribute()
    {
        $now = now();

        if (!$this->is_active) return 'Inactive';
        if ($this->start_date && $this->start_date->isFuture()) return 'Coming Soon';
        if ($this->end_date && $this->end_date->isPast()) return 'Expired';

        return 'Active';
    }

    /**
     * Quan hệ Nhiều - Nhiều: Voucher áp dụng cho nhiều Book
     */
    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_voucher');
    }
}
