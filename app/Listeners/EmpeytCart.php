<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Repositories\Cart\CartRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Facades\cart;
class EmpeytCart
{
    public  $cart;

    public function __construct(CartRepository $cart)
    {
        $this->cart=$cart;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $this->cart->empty();
    }
}
