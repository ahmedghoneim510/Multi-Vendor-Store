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
        if(!$this->item->count()){

            $this->item= Cart::with('product')->get();
        }
        return $this->item;
    }
    public function add(Product $product,$quantity=1)
    {
        $item=Cart::where('product_id',$product->id)->first();
        if(!$item){
            $cart= Cart::create([
                // 'id'=>Str::uuid(), we use observer to do it
                //'cookie_id'=>$this->getCookieId(),
                'user_id'=>Auth::id(),
                'product_id'=>$product->id,
                'quantity'=>$quantity,
            ]);
            return $this->get()->push($cart);
        }
        return $item->increment('quantity',$quantity);
    }
    public function update($id,$quantity=1){
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
        Cart::query()->delete();
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
