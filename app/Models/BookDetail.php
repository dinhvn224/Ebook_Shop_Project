<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'language',
        'size',
        'publish_year',
        'total_pages',
        'description',
        'quantity',
        'price',
        'promotion_price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'publish_year' => 'integer',
        'total_pages' => 'integer',
        'quantity' => 'integer',
        'price' => 'float',
        'promotion_price' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function ($builder) {
            $builder->where('deleted', false)->orWhereNull('deleted');
        });
    }
}
