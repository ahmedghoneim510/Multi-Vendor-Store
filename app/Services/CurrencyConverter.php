<?php

namespace App\Services;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

Class CurrencyConverter
{
    private $apikey;

    //protected $baseURL='https://free.currconv.com/api/v7';
    public function __construct($apikey){
        $this->apikey=$apikey;
    }
    public function convertCurrency($to, $from, $amount=1)
    {
        $client = new Client();
        $response = $client->get('https://api.apilayer.com/fixer/convert', [
            'query' => [
                'to' => $to,
                'from' => $from,
                'amount' => $amount,
            ],
            'headers' => [
                'Content-Type' => 'text/plain',
                'apikey' => $this->apikey, // Replace with your actual API key
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }


}
