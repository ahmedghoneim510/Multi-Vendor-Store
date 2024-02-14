<?php

namespace App\Http\Controllers\Front;
use App\Models\User;
use http\Cookie;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Listeners\EmpeytCart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrdersController extends Controller
{
    public $start;
    public function __construct()
    {
        $this->start = 10000;
    }
    public function checkingOut($id)
    {
        try {
            $order = Order::where('id', $id)->first();
            $orders=Order::where('user_id',Auth::id())->where('status','!=','paid')->get();
            $all_sum=$orders->sum(function ($order){
                return (float) $order->total_price;
            });
            //dd($all_sum);
            $all_sum*=100;
            $all_sum=intval($all_sum);
            $integration_id=env('PAYMOB_CARD_INTEGRATION_ID');
            $iframe_id_or_wallet_number=env('PAYMOB_CARD_IFRAME_ID');


            // step 1: login to paymob
            $response = Http::withHeaders([
                'content-type' => 'application/json'
            ])->post('https://accept.paymobsolutions.com/api/auth/tokens',[
                "api_key"=> env('PAYMOB_API_KEY')
            ]);
            $json=$response->json();


            // step 2: send order data

            $response_final=Http::withHeaders([
                'content-type' => 'application/json'
            ])->post('https://accept.paymobsolutions.com/api/ecommerce/orders',[
                "auth_token"=>$json['token'],
                "delivery_needed"=>"false",
                "amount_cents"=>$all_sum,
                "merchant_order_id" =>$id,
                "items"=>[],
            ]);
            $json_final=$response_final->json();

           // dd($json_final);

            // step 3: send payment key
            $response_final_final=Http::withHeaders([
                'content-type' => 'application/json'
            ])->post('https://accept.paymobsolutions.com/api/acceptance/payment_keys',[
                "auth_token"=>$json['token'],
                "expiration"=> 36000,
                "amount_cents"=>$all_sum,
                "order_id"=>$json_final['id'],
                "billing_data"=>[
                    "first_name"            => $order->billingAddress->first_name,
                    "last_name"             => $order->billingAddress->last_name,
                    "phone_number"          => $order->billingAddress->phone_number ?: "NA",
                    "email"                 => $order->billingAddress->email ?: auth()->user()->email,
                    "apartment"             => "NA",
                    "floor"                 => "NA",
                    "street"                => $order->billingAddress->street_address,
                    "building"              => "NA",
                    "shipping_method"       => "NA",
                    "postal_code"           => $order->billingAddress->postal_code ?:" ",
                    "city"                  => $order->billingAddress->city,
                    "state"                 => $order->billingAddress->state ?: "NA",
                    "country"               => $order->billingAddress->country,
                ],
                "currency"=>"EGP",
                "integration_id"=>$integration_id
            ]);

            $response_final_final_json=$response_final_final->json();

            return redirect('https://accept.paymobsolutions.com/api/acceptance/iframes/'. $iframe_id_or_wallet_number .'?payment_token=' . $response_final_final_json['token']);
        }catch (\Exception $e){
            dd($e->getMessage());
            //return to_route('home')->with('info','Something went wrong in order processing');
        }
    }

    public function callback(Request $request)
    {
        $order_id= $request->merchant_order_id;
        $order = Order::where('id',$order_id)->first();
//        dd($order);
        $user=User::where('id',$order->user_id)->first();
        Auth::login($user,true);
        $payment_details = json_encode($request->all());
        if ($request->success === "true")
        {
            $orders=Order::where('user_id',Auth::id())->get();
            foreach ($orders as $order){
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'payment_method'=>'card',
                ]);
            }

            event(new EmpeytCart());
            return to_route('home')->with('info','Payment Successfull');
        } else {
            $orders=Order::where('user_id',Auth::id())->get();
            foreach ($orders as $order){
                $order->delete();
            }
            return to_route('home')->with('info','Payment Failed');
        }
    }

    public function show(Order $order)
    {
        $user=auth()->user();
        if($user->id !== $order->user_id){
            return abort(403);
        }
        $delivery = $order->delivery()->select([
            'id',
            'order_id',
            'status',
            DB::raw("ST_Y(current_location) AS lng"),// to get the longitude from the point
            DB::raw("ST_X(current_location) AS lat"),// to get the latitude from the point
        ])->first();

        return view('front.orders.show', [
            'order' => $order,
            'delivery' => $delivery,
        ]);
    }

}
