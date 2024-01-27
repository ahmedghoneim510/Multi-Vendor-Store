<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
      'name','slug','image','description','category_id','store_id','price','compare_price','status',
        'feature','rating','options'
    ];
    protected static function booted()
    {
        static::addGlobalScope('store',new StoreScope());
    }
    public function category(){
        return $this->belongsTo(Category::class,'category_id','id')->withDefault();
    }
    public function store(){
       return $this->belongsTo(Store::class,'store_id','id')->withDefault();
    }
    public function tags(){
        return $this->belongsToMany(Tag::class);
        // if we don't name as laravel want we must write all this
            //return $this->belongsToMany(Tag::class,
            //'product_tag', => pivot table name
            //'product_id', => fk for pivot table for current model
            //'tag_id',  => fk for pivot table for related model
            //'id',  => pk current model
            //'id');  => pk for related model
    }
    public function scopeFilter(Builder $builder ,$filters){ // local scope
        if($filters['name'] ?? false){
            $builder->where('name','like',"%{$filters['name']}%");
        }
        if($filters['status']?? false){
            $builder->where('status',$filters['status']);
        }
    }
    public function ScopeActive(Builder $builder){
        $builder->where('status','active');
    }
    // accessor => used attribute don't exist
    public function getImageUrlAttribute(){ // when we use it product->image_url
        if(!$this->image){
        return 'https://smithcodistributing.com/wp-content/themes/hello-elementor/assets/default_product.png';
        }
        if(str::startsWith($this->image,['https://','http://']))
        {
            return $this->image;
        }
        return asset('storage/'.$this->image);

    }
    public function getSalePrecentAttribute(){
        $discount=100-($this->price*100/$this->compare_price);
            return number_format($discount,1) ;
    }
}
