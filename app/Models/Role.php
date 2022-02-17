<?php

namespace App\Models;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    public $guarded = [];

    public const IS_SUPER_ADMIN = 'superadministrator';
    public const IS_ADMIN = 'administrator';
    public const IS_USER = 'user';

}
