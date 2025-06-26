<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',  // Sử dụng password
        'phone_number',  // Cập nhật thành phone_number
        'avatar_url',  // Để lưu URL ảnh đại diện
        'role',  // Vai trò 'user' hoặc 'admin'
        'is_active',  // Trạng thái 'active' hoặc 'inactive'
        'birth_date',  // Thêm trường birth_date
    ];

    protected $hidden = [
        'password',  // Ẩn mật khẩu khi trả về dữ liệu
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'birth_date' => 'date',  // Đảm bảo xử lý ngày sinh như kiểu date
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            if (isset($user->password) && !empty($user->password)) {
                $user->password = Hash::make($user->password);  // Mã hóa mật khẩu
            }
        });
    }
}
