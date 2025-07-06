<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

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
        return $this->belongsTo(Author::class)->withDefault();
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class)->withDefault();
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    public function details()
    {
        return $this->hasMany(BookDetail::class)->where('is_active', true);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'book_voucher', 'book_id', 'voucher_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function mainImage()
    {
        return $this->hasOne(Image::class)->where('is_main', true);
    }

    // Scope để chỉ lấy sách chưa bị xóa
    protected static function booted()
    {
        static::addGlobalScope('not_deleted', function (Builder $builder) {
            $builder->where(function ($q) {
                $q->where('deleted', false)->orWhereNull('deleted');
            });
        });
    }

    // Scope mở rộng nếu muốn override global scope
    public function scopeWithTrashedFlag($query)
    {
        return $query->withoutGlobalScope('not_deleted');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'book_detail_id', 'id');
    }

    /**
     * Lấy URL ảnh chính của sách
     */
    public function getMainImageUrlAttribute()
    {
        $mainImage = $this->images()
            ->where('is_main', true)
            ->where('deleted', false)
            ->first();

        if (!$mainImage) {
            $mainImage = $this->images()
                ->where('deleted', false)
                ->first();
        }

        if ($mainImage && !empty($mainImage->url)) {
            return asset('storage/' . $mainImage->url);
        }

        // Fallback to default image
        $defaultImage = Image::first();
        return $defaultImage ? asset('storage/' . $defaultImage->url) : asset('client/img/products/noimage.png');
    }
}
