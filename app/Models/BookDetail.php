<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Book;
use App\Models\Image;

class BookDetail extends Model
{
    use HasFactory;

    protected $table = 'book_details';

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
        'deleted'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'deleted' => 'boolean',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'book_id', 'book_id');
    }

    public function mainImage()
    {
        return $this->hasOne(\App\Models\Image::class, 'book_id', 'book_id')->where('is_main', 1);
    }

    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function ($builder) {
            $builder->where('deleted', false)->orWhereNull('deleted');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
