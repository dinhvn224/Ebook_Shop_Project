<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'authors';

    protected $fillable = ['name', 'deleted'];

    protected $casts = [
        'deleted' => 'boolean',
    ];

    // Global scope để tự động ẩn các bản ghi bị đánh dấu xóa
    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function ($builder) {
            $builder->where('deleted', false)->orWhereNull('deleted');
        });
    }
}
