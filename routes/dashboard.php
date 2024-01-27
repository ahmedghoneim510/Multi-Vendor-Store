<?php

use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProfileController;
Route::group([
    'middleware'=>['auth','auth.type:super-admin,admin'], // must be auth , we made a middleware to enter dash :mean we 'll pass parameter
    'as'=>'dashboard.', // to make route name like dashboard.categories.index
    'prefix'=>'dashboard', // make a prefix before each resource
],function (){
    Route::get('/profile',[ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class,'update'])->name('profile.update');
    Route::get('/',[DashboardController::class,'index'])
        ->name('dashboard');
    Route::get('/categories/trashed',[CategoriesController::class,'trash'])
        ->name('categories.trash');
    Route::put('categories/{category}/restore',[CategoriesController::class,'restore'])
        ->name('categories.restore');
    Route::delete('categories/{category}/force-delete',[CategoriesController::class,'forceDelete'])
        ->name('categories.forceDelete');
    //
    Route::get('/products/trashed',[ProductsController::class,'trash'])
        ->name('products.trash');
    Route::put('products/{product}/restore',[ProductsController::class,'restore'])
        ->name('products.restore');
    Route::delete('products/{product}/force-delete',[ProductsController::class,'forceDelete'])
        ->name('products.forceDelete');
    //
    Route::resource('/categories',CategoriesController::class);
    Route::resource('/products',ProductsController::class);

    });
