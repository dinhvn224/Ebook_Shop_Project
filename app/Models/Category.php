<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Chỉ định bảng trong cơ sở dữ liệu
    protected $table = 'categories';

    // Các trường có thể gán giá trị
    protected $fillable = ['name', 'deleted'];

    // Cột 'deleted' được chuyển đổi thành kiểu boolean
    protected $casts = [
        'deleted' => 'boolean',
    ];
}
