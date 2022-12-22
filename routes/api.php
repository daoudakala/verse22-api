<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerseController;

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
//user controller 
Route::get('/user/getall',[AuthController::class,'index']);
Route::get('/user/getone/{id}',[AuthController::class,'show']);  
Route::put('/user/update/{id}',[AuthController::class,'update']);
Route::delete('/user/delete/{id}',[AuthController::class,'destroy']);
Route::post('/signin',[AuthController::class, 'login']); 
Route::post('/signup',[AuthController::class,'store']); 

//verses controller 
Route::get('/verse/getall',[VerseController::class,'index']);
Route::get('/verse/getone/{id}',[VerseController::class,'show']);


Route::group(["middleware" =>['auth:sanctum']], function(){
    Route::post('/verse/create',[VerseController::class,'store']);
    Route::put('/verse/update/{id}',[VerseController::class,'update']);
    Route::delete('/verse/delete/{id}',[VerseController::class,'destroy']);
    Route::post('/logout',[AuthController::class,'logout']);
});
