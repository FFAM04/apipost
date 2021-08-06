<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,PostController,UserController};
use App\Http\Middleware\JwtMiddleware;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login_api']);
    Route::post('/register', [AuthController::class, 'register_api']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);     
});
 
Route::group(['middleware' => 'jwt.verify'], function(){
    Route::post('/add_post', [PostController::class, 'add_post']);    
    Route::get('/get_all_post', [PostController::class, 'get_all_post']);    
    Route::get('/get_detail_post', [PostController::class, 'get_detail_post']);    
    Route::post('/delete_post', [PostController::class, 'delete_post']);    
    Route::get('/search_post', [PostController::class, 'search_post']);    
    Route::post('/update_post', [PostController::class, 'update_post']);  

    Route::post('/add_user', [UserController::class, 'add_user']);    
    Route::get('/get_user', [UserController::class, 'get_user']);    
    Route::get('/get_detail_user', [UserController::class, 'get_detail_user']);    
    Route::post('/delete_user', [UserController::class, 'delete_user']);    
    Route::get('/search_user', [UserController::class, 'search_user']);    
    Route::post('/update_user', [UserController::class, 'update_user']);   
});