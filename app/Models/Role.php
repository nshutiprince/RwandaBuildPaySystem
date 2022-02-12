<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public const IS_USER = 1;
    public const IS_ADMIN = 2;
    public const IS_SUPER_ADMIN = 3;

    /**
     * returns users relationship
     */
    public function users(){
        return $this->hasMany(User::class);
    }
}
