<?php

namespace App\Providers;

use App\Repositories\Cart\CartModelRepository;
use App\Repositories\Cart\CartRepository;
use Illuminate\Support\ServiceProvider;
class CartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void // used to store in serviceproider
    {
        $this->app->bind(CartRepository::class,function(){ // we store object under name of class of interface
            return new CartModelRepository();
        });
        //we can use app->singleton to return same object
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
