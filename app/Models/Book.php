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
        return $this->belongsTo(Author::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function details()
    {
        return $this->hasMany(BookDetail::class);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'book_voucher');
    }
    

    // Global scope để loại bỏ sách đã bị đánh dấu xóa
    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function ($builder) {
            $builder->where('deleted', false)->orWhereNull('deleted');
        });
    }
}
