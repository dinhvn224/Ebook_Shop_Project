<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'description', 'deleted'];
    protected $casts = [
        'deleted' => 'boolean',
    ];
    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function ($builder) {
            $builder->where('deleted', false)->orWhereNull('deleted');
        });
    }
}

