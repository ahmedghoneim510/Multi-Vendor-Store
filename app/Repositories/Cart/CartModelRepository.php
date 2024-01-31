<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CartModelRepository implements CartRepository
{
    protected $item;
    public function __construct()
    {
        $this->item=collect([]);
    }

    public function get() :Collection
    {
        if(!$this->item->count()){ // check if there isn't item in cart return empty object

            $this->item= Cart::with('product')->get();
        }
        return $this->item; // return element in collection
    }
    public function add(Product $product,$quantity=1)
    {
        $item=Cart::where('product_id',$product->id)->first();  // check if product not in cart before
        if(!$item){  // if it not in cart
            $cart= Cart::create([ // create it
                // 'id'=>Str::uuid(), we use observer to do insert it in the creating
                //'cookie_id'=>$this->getCookieId(),
                'user_id'=>Auth::id(),
                'product_id'=>$product->id,
                'quantity'=>$quantity,
            ]);
            return $this->get()->push($cart); // push the element in collection
        }
        return $item->increment('quantity',$quantity); // update if it exist
    }
    public function update($id,$quantity=1){ // used to update the quantity
         Cart::where('id',$id)
         ->update([
            'quantity'=>$quantity,
         ]);
    }
    public function delete($id){
        Cart::where('id',$id)
        ->delete();
    }
    public function empty(){
        Cart::query()->delete(); // delete all data in table
    }
    public function total() : float
    {
//        return (float) Cart::join('products','products.id','=','carts.product_id')
//        ->selectRaw('SUM(products.price * carts.quantity) as total')
//        ->value('total');

        return (float) $this->get()->sum(function ($item){
           return (float) $item->quantity * $item->product->price;
        });
    }


}
