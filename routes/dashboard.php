<?php

use App\Http\Controllers\Dashboard\CategoriesController;
use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProductsController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\AdminsController;
use App\Http\Controllers\Dashboard\UsersController;
use App\Http\Controllers\Dashboard\ImportProductsController;
use App\Http\Controllers\Dashboard\OrdersController;
Route::group([ // we want to make this route to login with guard admin so we use auth:admin
    'middleware'=>['auth:admin'], // must be auth , we made a middleware to enter dash :mean we 'll pass parameter
    'as'=>'dashboard.', // to make route name like dashboard.categories.index
    'prefix'=>'admin/dashboard', // make a prefix before each resource (link)
],function (){
    /*------------------------------------------------------------------------------------------*/

    Route::get('/profile',[ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class,'update'])->name('profile.update');

    /*------------------------------------------------------------------------------------------*/

    Route::get('/',[DashboardController::class,'index'])
        ->name('dashboard');
    Route::get('/categories/trashed',[CategoriesController::class,'trash'])
        ->name('categories.trash');
    Route::put('categories/{category}/restore',[CategoriesController::class,'restore'])
        ->name('categories.restore');
    Route::delete('categories/{category}/force-delete',[CategoriesController::class,'forceDelete'])
        ->name('categories.forceDelete');

    /*------------------------------------------------------------------------------------------*/

    Route::get('products/import',[ImportProductsController::class,'create'])
        ->name('products.import');
    Route::post('products/import',[ImportProductsController::class,'store']);


    /*------------------------------------------------------------------------------------------*/

    Route::get('/products/trashed',[ProductsController::class,'trash'])
        ->name('products.trash');
    Route::put('products/{product}/restore',[ProductsController::class,'restore'])
        ->name('products.restore');
    Route::delete('products/{product}/force-delete',[ProductsController::class,'forceDelete'])
        ->name('products.forceDelete');


    /*------------------------------------------------------------------------------------------*/

    Route::resource('/categories',CategoriesController::class);
    Route::resource('/products',ProductsController::class);
    Route::resource('roles',RolesController::class);
    Route::resource('/admins',AdminsController::class);
    Route::resource('/users',UsersController::class);
    Route::resource('/orders-admins',OrdersController::class);

    /*------------------------------------------------------------------------------------------*/


});
