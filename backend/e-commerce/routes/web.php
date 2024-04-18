<?php

use Illuminate\Support\Facades\Route;
use App\http\Controllers\ArticleController;
use App\http\Controllers\IndexController;
use App\http\Controllers\UserController;
use App\http\Controllers\PanierController;
use App\http\Controllers\StripeController;
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
Route::post('/user/get_connected', [UserController::class, 'get_connected']);
Route::post('/user/update_profil', [UserController::class, 'update_profil']);
Route::get('/user/activate/{email}/{token}', [UserController::class, 'validate']);
Route::post('/user/change_mail', [UserController::class, 'change_mail']);  
Route::get('/user/activate_new_mail/{email}/{token}', [UserController::class, 'validate_new_mail']);
    // Votre logique de traitement avec le paramètre $id

//Panier routes

Route::post('/cart/add', [PanierController::class, 'add']);
Route::post('/cart/get', [PanierController::class, 'get']);
Route::post('/cart/remove_article', [PanierController::class, 'remove_article']);
Route::post('/cart/update_qte', [PanierController::class, 'update_qte']);

//Stripe routes

Route::post('/stripe/pay', [StripeController::class, 'pay']);
Route::get('/stripe/pay', [StripeController::class, 'pay']);
route::get('/stripe/import_all_products', [StripeController::class, 'import_all_products']);
Route::get('/stripe/success', [StripeController::class, 'success']);
Route::get('/cancel/sucess', [StripeController::class, 'cancel']);