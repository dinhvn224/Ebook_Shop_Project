<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Image extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'book_id',
        'url',
        'caption',
        'is_main',
        'deleted',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'deleted' => 'boolean',
    ];

    /**
     * Quan hệ: Một ảnh thuộc về một sách
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }



    /**
     * Scope: Ảnh chính và chưa bị xóa
     */
    public function scopeMainActive(Builder $query)
    {
        return $query->where('is_main', 1)->where('deleted', 0);
    }

    /**
     * Scope: Ảnh chưa bị xóa
     */
    public function scopeNotDeleted(Builder $query)
    {
        return $query->where('deleted', 0);
    }

    /**
     * Scope: Ảnh là chính
     */
    public function scopeMain(Builder $query)
    {
        return $query->where('is_main', 1);
    }

    /**
     * Accessor: Trả về URL ảnh nếu có, ngược lại trả về ảnh mặc định
     */
    public function getUrlOrDefaultAttribute()
    {
        if ($this->url) {
            return asset('storage/' . $this->url);
        }

        $defaultImage = self::first();
        return $defaultImage ? asset('storage/' . $defaultImage->url) : asset('client/img/products/noimage.png');
    }
}
