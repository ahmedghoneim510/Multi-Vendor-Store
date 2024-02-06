<?php

namespace App\Providers;

use Illuminate\Pagination\PaginationState;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('currency.converter',function (){
            return new \App\Services\CurrencyConverter(config('services.CURRENCY_CONVERTER'));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('filter',function ($attribute,$value,$param){
            return ! in_array(strtolower($value),$param);
        },"This value is not allowed..!");
        Paginator::useBootstrapFour();
    }
}
