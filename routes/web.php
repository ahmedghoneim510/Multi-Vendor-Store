<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\Auth\TwoFactorAuthenticationController;
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

Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/products',[\App\Http\Controllers\Front\ProductsController::class,'index'])->name('products.index');
Route::get('/products/{product:slug}',[\App\Http\Controllers\Front\ProductsController::class,'show'])->name('products.show');
// we use :slug to tell larvel we use slug nested of id
Route::resource('cart', CartController::class);

/** {Route for checkout} **/

Route::get('checkout',[\App\Http\Controllers\Front\CheckoutController::class,'create'])->name('checkout');
Route::post('checkout',[\App\Http\Controllers\Front\CheckoutController::class,'store'])->name('checkout.store');
/**{End of checkout}**/
Route::get('/dash', function () {
    return view('dashboard');
});

Route::get('auth/user/2fa',[TwoFactorAuthenticationController::class,'index'])->name('front.2fa');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//require __DIR__.'/auth.php';
require __DIR__.'/dashboard.php';


