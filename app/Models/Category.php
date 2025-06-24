<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['name', 'deleted'];

    protected $casts = [
        'deleted' => 'boolean',
    ];

    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function (Builder $builder) {
            $builder->where('deleted', false)->orWhereNull('deleted');
        });
    }
}
