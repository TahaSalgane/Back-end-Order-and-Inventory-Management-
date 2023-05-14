<?php

use App\Http\Controllers\ArticleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\ForgetController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReclamationController;
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


Route::controller(ArticleController::class)->group(function () {
    Route::get('/articles','getArticles')->middleware('auth:api') ;
    Route::post('/addArticle','addArticle')->middleware('auth:api') ;
    Route::put('/editActicle','editActicle')->middleware('auth:api') ;
    Route::delete('/deleteArticle','deleteArticle')->middleware('auth:api') ;
});

Route::controller(ClasseController::class)->group(function () {
    Route::get('/classes','getClasses')->middleware('auth:api') ;
    Route::post('/addClasse','addClasse')->middleware('auth:api') ;
    Route::put('/editClasse','editClasse')->middleware('auth:api') ;
    Route::delete('/deleteClasse','deleteClasse')->middleware('auth:api') ;

    Route::get('/articlesClasse','articlesClasse')->middleware('auth:api') ;
    Route::get('/addArticlesOnClasse','addArticlesOnClasse')->middleware('auth:api') ;
    Route::delete('/deleteArticlesOnClasse','deleteArticlesOnClasse')->middleware('auth:api') ;
    Route::put('/editArticlesOnClasse','editArticlesOnClasse')->middleware('auth:api') ;
});

Route::middleware('auth:api')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
});

Route::controller(OrderController::class)->group(function (){
    Route::get('/orders','orders')->middleware('auth:api') ;
    Route::post('/addOrder','addOrder')->middleware('auth:api') ;
    Route::put('/editOrder','editOrder')->middleware('auth:api') ;
    Route::delete('/deleteOrder','deleteOrder')->middleware('auth:api') ;
}) ;

Route::controller(ReclamationController::class)->group(function (){
    Route::post('/addReclamation','addReclamation')->middleware('auth:api') ;
    Route::put('/editReclamation','editReclamation')->middleware('auth:api') ;
    Route::put('/repReclamation','repReclamation')->middleware('auth:api') ;
    Route::delete('/deleteReclamation','deleteReclamation')->middleware('auth:api') ;
}) ;

