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
Route::get('/murugo-login', [MurugoLoginController::class, 'redirectToMurugo'])->name('murugo.login');
Route::get('/murugo-callback', [MurugoLoginController::class, 'murugoCallback'])->name('murugo.callback');



require __DIR__ . '/auth.php';

//Routes for the admin
Route::group(['middleware' => 'role:'.Role::IS_ADMIN], function () {
    Route::resource('configs', 'App\Http\Controllers\ConfigController');
});

//Routes for the super admin
Route::group(['middleware' => 'role:'.Role::IS_SUPER_ADMIN], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::put('users/{user}', [UserController::class, 'update']);
});

//Route for payment
Route::middleware(['middleware' => 'role:'.Role::IS_USER])->prefix('user')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    Route::get('/pay', [PayController::class, 'pay'])->name('pay');
});
