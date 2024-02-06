<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class Currency
{


    public function __invoke(...$params)
    {
        return static::formatCurrency(...$params);
    }

    public static function formatCurrency($amount,$curreny=null)
    {
        $basecurrency=config('app.currency','USD');
        $formmater=New \NumberFormatter(config('app.local'),\NumberFormatter::CURRENCY);

        if($curreny===null){
            $curreny=Session::get('currency_code', $basecurrency);
        }
        if($curreny!= $basecurrency){

            $rate=Cache::get('currency_rate_'.$curreny,1);

            if($rate['info']['rate']!== null){

                $rate=$rate['info']['rate'];

                $amount=(float)($amount* $rate);
            }

        }
        return $formmater->formatCurrency($amount,$curreny);
    }

}
