<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_member',
        'royalty_points',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * returns the role relationship
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * returns true if user is admin and false if not
     */
    public function isAdmin(): Bool
    {
        return in_array(auth()->user()->role_id, [Role::IS_ADMIN]);
    }

    /**
     * returns true if user is super admin and false if not
     */
    public function isSuperAdmin(): Bool
    {
        return in_array(auth()->user()->role_id, [Role::IS_SUPER_ADMIN]);

    }
}
