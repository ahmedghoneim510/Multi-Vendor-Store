<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class DeductProductQuantity
{
    /**
     *
     * Create the event listener.
     */
    public  $cart;
    public function __construct(CartRepository $cart)
    {
        $this->cart=$cart;
    }

    /**
     * Handle the event.
     */
    public function handle($event): void // we can contact the event with listener from eventServiceProvider
    { // incase we use this listener in multi events we may not spacified the event type
        $order=$event->order; // we get it from event
        foreach ($order->products as $product){
           // dd($order->products);
            $product->decrement('quantity',$product->pivot->quantity);
//            Product::where('id',$item->product_id)->update([
//                'quantity'=>DB::row("qantity - {$item->quantity}"),
//            ]);
        }
    }
}
