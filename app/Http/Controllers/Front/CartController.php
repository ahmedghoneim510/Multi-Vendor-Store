<?php

namespace App\Http\Controllers\Front;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\CartModelRepository;

class CartController extends Controller
{
    public $cart;
    public function __construct(CartRepository $cart){
        $this->cart=$cart;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(CartRepository $cart) // laravel get it from service provider if it not exist it's will get error
    {
        // $repository=new CartModelRepository(); //instead of make new one every time we can store it in sp
      //   $repository=App::make('cart'); // get from service provider
      // instead of them all we can use class CartRepository as parameter
       // $items=$cart->get();
        return view('front.cart',[
            'cart'=>$cart
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id'=>['required','int','exists:products,id'],
            'quantity'=>['nullable','int','min:1']
        ]);
        $product=Product::findorFail($request->post('product_id'));
        //$repository=new CartModelRepository();
//        $this->cart->add();
        $this->cart->add($product,$request->post('quantity'));

        if ($request->expectsJson()) {

            return response()->json([
                'message' => 'Item added to cart!',
            ], 201);
        }
        return to_route('cart.index')
            ->with('success','product added to cart!');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity'=>['required','int','min:1']
        ]);
//        $product=Product::findorFail($request->post('product_id'));
        //$repository=new CartModelRepository();
        return $this->cart->update($id,$request->post('quantity'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
      //  $repository=new CartModelRepository();
        $this->cart->delete($id);
        return [
            'message' => 'Item deleted!',
        ];
    }
}
