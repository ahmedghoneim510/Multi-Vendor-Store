<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Stmt\Case_;
use GuzzleHttp\Client;

class CurrencyConverter extends Controller
{
    public function store(Request $request){
        $request->validate([
            'currency_code'=>'required|string|size:3'
        ]);

        $base_currency=config('app.currency'); // main

        $currency_code=$request->post('currency_code'); // change to

        $cache_key='currency_rate_'.$currency_code; // cash for changed currency

        $rate=Cache::get($cache_key,0);

        if(!$rate){
            $converter=app('currency.converter');
            $rate=$converter->convertCurrency($base_currency,$currency_code);

            Cache::put($cache_key,$rate);
        }
        Session::put('currency_code',$currency_code);// put currency in session
        return redirect()->back();
    }


}

