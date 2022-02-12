<?php

use App\Http\Controllers\PayController;
use App\Http\Controllers\UserController;
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


require __DIR__ . '/auth.php';

//Routes for the admin
Route::group(['middleware' => 'is_admin'], function () {
    Route::resource('configs', 'App\Http\Controllers\ConfigController');
});

//Routes for the super admin
Route::group(['middleware' => 'is_super_admin'], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::put('users/{user}', [UserController::class,'update']);
});

//Route for payment
Route::get('/pay',[PayController::class,'pay'])->middleware('auth');
