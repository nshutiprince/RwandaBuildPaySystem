<?php

namespace App\Services;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * In charge of updating the user royalty points
     */
    public function updateUserRoyaltyPoints($points){
        Auth::user()->update([
            'royalty_points'=>$points
        ]);
        return $this;
    }
}
