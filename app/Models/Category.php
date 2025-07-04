<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'slug',
        'icon',
        'deleted',
    ];

    protected $casts = [
        'deleted' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function (Builder $builder) {
            $builder->where('deleted', false)->orWhereNull('deleted');
        });
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'category_id');
    }

    // Optional: đảm bảo icon luôn có giá trị mặc định
    public function getIconAttribute($value)
    {
        return $value ?: 'fa-book';
    }
}
