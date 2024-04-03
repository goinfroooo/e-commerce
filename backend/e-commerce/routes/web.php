<?php

use Illuminate\Support\Facades\Route;
use App\http\Controllers\ArticleController;
use App\http\Controllers\IndexController;
use App\http\Controllers\UserController;
use App\http\Controllers\PanierController;
use Illuminate\Http\Request;

Route::get('/csrf-token', function (Request $request) {
    return response()->json(['csrf_token' => $request->session()->token()]);
});

Route::get('/', [IndexController::class, 'index']);

//Article routes
Route::post('/all_articles', [ArticleController::class, 'get_all']);
Route::post('/article', [ArticleController::class, 'get_one']);

//User routes

Route::post('/user/create_user', [UserController::class, 'create']);
Route::post('/user/get_profil', [UserController::class, 'get_profil']);
Route::post('/user/update_profil', [UserController::class, 'update_profil']);

//Panier routes

Route::post('/cart/add', [PanierController::class, 'add']);