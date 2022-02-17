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
        // accessing the related user in our databse
        $user = $murugoUser->user;
        if (!$user) {
            // register the murugo user to our databse
            $user = $murugoUser->user()->create([
                'name' => $murugoUser->name,
            ]);

            //Assign use role to our user
            $user->attachRole(Role::IS_USER);
        }
        //Authenticate the murugo user to our service
        Auth::login($user);
        //redirect user to his dashboard
        return redirect()->route('user.dashboard');
    }
}
