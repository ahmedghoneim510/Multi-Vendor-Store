<?php
namespace App\Facades;

use App\Http\Controllers\Front\CartController;
use App\Repositories\Cart\CartRepository;
use Illuminate\Support\Facades\Facade;

class cart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return CartRepository::class; // return it from service container
    }
}
