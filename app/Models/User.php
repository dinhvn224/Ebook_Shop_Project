<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'ADMIN';
    const ROLE_USER  = 'USER';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'address',
        'birth_date',
        'avatar_url',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'birth_date' => 'date',
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

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    // ================= HELPERS =================

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function getAvatarUrlAttribute($value)
    {
        return $value ?: 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
    }
}
