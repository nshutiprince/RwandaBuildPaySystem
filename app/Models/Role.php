<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    public $guarded = [];

    public const IS_SUPER_ADMIN = 1;
    public const IS_ADMIN = 2;
    public const IS_USER = 3;

}
