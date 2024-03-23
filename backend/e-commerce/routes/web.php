<?php

use Illuminate\Support\Facades\Route;
use App\http\Controllers\ArticleController;
use App\http\Controllers\IndexController;
use Illuminate\Http\Request;

Route::get('/csrf-token', function (Request $request) {
    return response()->json(['csrf_token' => $request->session()->token()]);
});

Route::get('/', [IndexController::class, 'index']);
Route::post('/all_articles', [ArticleController::class, 'get_all']);
Route::post('/article', [ArticleController::class, 'get_one']);
