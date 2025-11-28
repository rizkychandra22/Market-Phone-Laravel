<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'phone',
        'address',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Override username login field
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Helper tambahan (opsional)
     * Cek apakah user punya salah satu role dalam array
     */
    public function hasAnyRoles(array $roles)
    {
        return $this->hasAnyRole($roles);
    }

    /**
     * Cek apakah user punya role tertentu
     */
    public function isRole(string $role)
    {
        return $this->hasRole($role);
    }
}
