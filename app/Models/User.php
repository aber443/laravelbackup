<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
        'password',
        'role',
        'is_active',
        'notifications_enabled',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'notifications_enabled' => 'boolean',
        ];
    }

    // علاقة المستخدم بالأطفال
    public function children()
    {
        return $this->hasMany(
            Child::class,
            'user_id',
            'user_id'
        );
    }

    // علاقة المستخدم بأجهزة الإشعارات
    public function deviceTokens()
    {
        return $this->hasMany(
            DeviceToken::class,
            'user_id',
            'user_id'
        );
    }

    // التحقق من صلاحية الأدمن
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // التقارير التي أنشأها المستخدم
    public function reports()
    {
        return $this->hasMany(
            Report::class,
            'generated_by',
            'user_id'
        );
    }
}