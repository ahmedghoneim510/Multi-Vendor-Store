<?php

namespace App\Models;

use App\Observers\CartObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;
    public $incrementing=false;

    protected $fillable=[
        'cookie_id','user_id','product_id','quantity','options'
    ];

    // Events (observers) we also can make a class observer from php artisan make:observer CartObserver --model=Cart
    // Creating,created ,updating,updated,save,saved
    // deleting,deleted,restoring,restored,retrieved


    public static function booted()
    {
//         static::creating(function(Cart $cart){ // while creating(inserting) make uuid and insert it
//             $cart->id=Str::uuid();
//         });
        static::observe(CartObserver::class); // use class automatic

         static::addGlobalScope('cookie_id',function (Builder $builder){
            $builder->where('cookie_id',static::getCookieId());
         });


    }
    public  static function getCookieId(){
        $cookie_id=Cookie::get('cart_id');
        if(!$cookie_id){
            $cookie_id=Str::uuid();
            // writing cookie happen in responce , we can use queue cuz we ,made a middleware in web
            Cookie::queue('cart_id',$cookie_id,30*40*60);
        }
        return $cookie_id;
    }

    public function user(){
        return $this->belongsTo(User::class)->withDefault([
            'name'=>'Anonymouse',
        ]);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
