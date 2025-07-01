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

    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function ($builder) {
            $builder->where('deleted', false)->orWhereNull('deleted');
        });
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'author_id');
    }
}
