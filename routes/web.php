<?php

use App\Http\Controllers\login\MurugoLoginController;
use App\Http\Controllers\PayController;
use App\Http\Controllers\UserController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/murugoLogin', [MurugoLoginController::class, 'redirectToMurugo'])->name('murugoLogin');
Route::get('/callback', [MurugoLoginController::class, 'murugoCallback'])->name('callback');



require __DIR__ . '/auth.php';

//Routes for the admin
Route::group(['middleware' => 'role:administrator'], function () {
    Route::resource('configs', 'App\Http\Controllers\ConfigController');
});

//Routes for the super admin
Route::group(['middleware' => 'role:superadministrator'], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::put('users/{user}', [UserController::class, 'update']);
});

//Route for payment
Route::middleware(['middleware' => 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('userDashboard');

    Route::get('/pay', [PayController::class, 'pay'])->name('pay')->middleware('role:user');
});
