<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $table = 'publisher';

    protected $fillable = ['name', 'deleted'];

    protected $casts = [
        'deleted' => 'boolean',
    ];

    // Mặc định chỉ lấy bản ghi chưa bị ẩn
    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function ($query) {
            $query->where('deleted', false);
        });
    }
}
