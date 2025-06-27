<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'author_id', 'publisher_id', 'category_id', 'description'];

    public function details()
    {
        return $this->hasMany(BookDetail::class);
    }
}
