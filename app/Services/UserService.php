<?php

namespace App\Services;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * @param $points
     * In charge of updating the user loyalty points
     */
    public function updateUserLoyaltyPoints($points){
        Auth::user()->update([
            'loyalty_points'=>$points
        ]);
        return $this;
    }
}
