<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'type',
        'value',
        'max_discount',
        'usage_limit',
        'used_count',
        'is_active',
        'start_at',
        'expires_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // 🟢 Trạng thái động
    public function getStatusAttribute()
    {
        $now = now();

        if (!$this->is_active) {
            return 'Inactive';
        }

        if ($this->start_at && $this->start_at->isFuture()) {
            return 'Coming Soon';
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'Expired';
        }

        return 'Active';
    }

    public function products()
{
    return $this->belongsToMany(Product::class);
}


}
