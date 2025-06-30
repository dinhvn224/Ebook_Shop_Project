<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use App\Models\BookDetail;
use App\Models\Voucher;  // nhớ import Voucher
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'name',
        'author_id',
        'publisher_id',
        'category_id',
        'description',
        'deleted'
    ];

    protected $casts = [
        'deleted' => 'boolean',
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function details()
    {
        return $this->hasMany(BookDetail::class, 'book_id');
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'book_voucher', 'book_id', 'voucher_id');
    }

    public function images()
    {
        return $this->hasMany(\App\Models\Image::class, 'book_id');
    }


    // Global scope để loại bỏ sách đã bị đánh dấu xóa
    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function ($builder) {
            $builder->where('deleted', false)->orWhereNull('deleted');
        });
    }
}
