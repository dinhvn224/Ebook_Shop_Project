<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'address',
        'birth_date',
        'avatar_url',
        'role',
        'is_active'
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


    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class)
            ->withPivot('used_at')
            ->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}
