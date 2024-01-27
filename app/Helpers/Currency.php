<?php
namespace App\Helpers;

class Currency
{


    public function __invoke(...$params)
    {
        return static::formatCurrency(...$params);
    }

    public static function formatCurrency($amount,$curreny=null)
    {
        $formmater=New \NumberFormatter(config('app.local'),\NumberFormatter::CURRENCY);

        if($curreny===null){
            $curreny=config('app.currency','USD');
        }
        return $formmater->formatCurrency($amount,$curreny);
    }

}
