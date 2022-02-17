<?php

namespace App\Http\Controllers\login;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RwandaBuild\MurugoAuth\Facades\MurugoAuth;


class MurugoLoginController extends Controller
{
    /**
     * The function redirects the user to the murugo login page
     */
    public function redirectToMurugo()
    {
        return MurugoAuth::redirect();
    }

    /**
     * The function is called after the authentication of murugo
     * Then we use the murugo user to create our own user with the role of user
     * Then authenticate the user
     * Then he is redirected to the dashboard
     */
    public function murugoCallback()
    {
        $murugoUser = MurugoAuth::user();
        // accessing the related user
        $user = $murugoUser->user;
        if (!$user) {
            User::create([
                'name' => $murugoUser->name,
                'murugo_user_id' => $murugoUser->id
            ]);
            $user->attachRole(Role::IS_USER);
        }
        Auth::login($user);
        return redirect()->route('userDashboard');
    }
}
