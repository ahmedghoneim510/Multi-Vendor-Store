<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Intl\Countries;

class OrderAddress extends Model
{
    use HasFactory;
    protected $fillable=[
        'order_id','type','first_name','last_name','phone','phone_number','street_address','postal_code',
        'city','country','state'
    ];
    public function getNameAttribute(){ // accessor to get all name (this is such as create col in db called name )
        return $this->first_name.' '.$this->last_name;
    }
    public function getCountryNameAttribute(){
        return Countries::getName($this->country); // get countryname from lib using it's code
    }
}
