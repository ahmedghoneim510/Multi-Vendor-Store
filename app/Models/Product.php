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
    protected $hidden=[ // used to hidden some cols when we use api (return data without this columns)
        'created_at','update_at','deleted_at','image'
    ];
    protected $appends = [ // append it like col to use in api
        'image_url', // should make accessor function
    ];

    protected static function booted()
    {
        static::creating(function(Product $product){ // while creating(inserting) make uuid and insert it
            $product->slug=Str::slug($product->name);
        });
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

//    public function scopeFilter(Builder $builder ,$filters){ // local scope
//        if($filters['name'] ?? false){
//            $builder->where('name','like',"%{$filters['name']}%");
//        }
//        if($filters['status']?? false){
//            $builder->where('status',$filters['status']);
//        }
//    }
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
    public function scopeFilter(Builder $builder ,$filters){
        $options=array_merge([
            'store_id'=>null,
            'category_id'=>null,
            'tag_id'=>null,
            'status'=>'active',
        ],$filters);
        $builder->when($options['status'],function ($builder,$value){
            $builder->where('status',$value);
        });
        if($filters['name'] ?? false){
             $builder->where('name','like',"%{$filters['name']}%");
        }
        $builder->when($options['store_id'],function ($builder,$value){
            $builder->where('store_id',$value);
        });
        $builder->when($options['category_id'],function ($builder,$value){
            $builder->where('category_id',$value);
        });
        $builder->when($options['tag_id'],function ($builder,$value){ // exists = id in ()
//            $builder->whereRow('EXISTS (select 1 from product_tag where tag_id ? AND product_id=products.id )'); // mean we 'll a query statment
            $builder->whereExists(function($query) use ($value) {
                $query->select(1)
                    ->from('product_tag')
                    ->whereRaw('product_id = products.id')
                    ->where('tag_id', $value);
            });
        });

    }
}
