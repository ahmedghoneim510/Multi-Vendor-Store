<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory;
    protected $fillable=[
        'store_id','user_id','payment_method','status','payment_status',
    ];
    public function store(){
        return $this->belongsTo(Store::class);
    }
    public function user(){
        return $this->belongsTo(User::class)->withDefault(['name'=>'Guest Customer']);
    }
    public static function booted()
    {
        //parent::booted(); // TODO: Change the autogenerated stub
        static::creating(function (Order $order){
            // 2024+0000
            $order->number=Order::getNextOrderNumber();
        });
    }
    public function products(){
        return $this->belongsToMany(Product::class,'order_items','order_id','product_id','id','id')
            ->using(OrderItem::class) // to tell laravel that povit table has model
            ->withPivot(['prodcut_name','price','options']); // get data from table to store in povit table
    }
    public function addresses(){
        return  $this->hasMany(OrderAddress::class); // has 2 billing and shipping
    }
    public function billingAddress(){
//        return $this->addresses()->where('type','billing'); here will return collection
        return $this->hasOne(OrderAddress::class,'order_id','id')
            ->where('type','billing'); // here 'll return model
    }
    public function shippingAddress(){
        return $this->hasOne(OrderAddress::class,'order_id','id')
            ->where('type','shipping');
    }
    public static function getNextOrderNumber(){

        // select max(number) from orders
        $year=Carbon::now()->year;
        $num= Order::whereYear('created_at',$year)->max('number');
        if($num){
            return $num+1;
        }
        return $year."000001";

    }
}