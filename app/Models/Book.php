<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Category;
use App\Models\BookDetail;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'name', 'author_id', 'publisher_id', 'category_id', 'description', 'deleted'
    ];

    protected $casts = [
        'deleted' => 'boolean',
    ];

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

    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function ($builder) {
            $builder->where('deleted', false)->orWhereNull('deleted');
        });
    }
}
