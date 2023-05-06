<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgetController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});
Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/forgetpassword',[ForgetController::class,'ForgetPassword']);
Route::post('/resetpassword',[ResetController::class,'ResetPassword']);
Route::get('/user',[UserController::class,'user'])->middleware('auth:api');

Route::post('/updateProfile',[UserController::class,'updateProfile'])->middleware('auth:api');
