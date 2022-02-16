<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RwandaBuild\MurugoAuth\Facades\MurugoAuth;


class MurugoLoginController extends Controller
{
    public function redirectToMurugo()
    {
        return MurugoAuth::redirect();
    }

     public function murugoCallback()
    {
        $murugoUser = MurugoAuth::user();
        $user = $murugoUser->user;
        if(!$user){
            $user->create([
                'name' =>$murugoUser->name,
            ]);
        }
        Auth::login($user);
    }
}
