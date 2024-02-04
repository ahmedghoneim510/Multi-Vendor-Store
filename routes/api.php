<?php

use App\Http\Controllers\Api\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccessTokensControlller;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::resourceAp('/product',[ProductsController::class,'index']);
//Route::apiResource('products',ProductsController::class);
Route::apiResource('products', ProductsController::class);

Route::post('auth/access-tokens',[AccessTokensControlller::class,'store'])
    ->middleware('guest:sanctum'); // he isn't auth from guard sanctum

Route::delete('auth/access-tokens/{token?}',[AccessTokensControlller::class, 'destroy'])
    ->middleware('auth:sanctum');
