<?php

namespace App\Models;

use App\Rules\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[ // acceptable parameter
        'name','parent_id','description','slug','image','status'
    ];

    protected static function booted()
    {
        static::creating(function(Category $category){ // while creating(inserting) make uuid and insert it
            $category->slug=Str::slug($category->name);
        });
    }
    public function products(){
        return $this->hasMany(Product::class,'category_id','id');
    }
    public function parent(){
        return $this->belongsTo(Category::class,'parent_id','id')->withDefault(
            [
                'name'=>'-'
            ]
        );
    }
    public function children(){
        return $this->hasMany(Category::class,'parent_id','id');
    }
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



    public function scopeFilter(Builder $builder ,$filters){ // local scope
        $builder->when($filters['name'] ?? false, function ($builder,$value){
            $builder->where('categories.name','like',"%{$value}%");
        });
        $builder->when($filters['status']?? false, function ($builder,$value){
            $builder->where('categories.status',$value);
        });

//        if($filters['name'] ?? false){
//            $builder->where('name','like',"%{$filters['name']}%");
//        }
//        if($filters['status']?? false){
//            $builder->where('status',$filters['status']);
//        }
    }
    public static function rules($id=0){ // validation
        return [
            'name'=>[
                'required' ,
                'string' , 'min:3' , 'max:255',
                // unique:categories,name,$id
                Rule::unique('categories','name')->ignore($id),
                //new Filter(['laravel','php','css','c++','html']),
                'filter:php,laravel,css,html',

            ],
            'parent_id'=>[
                'nullable','int','exists:categories,id'
            ], // check if int type and exist in categories table in col id
            'image'=>[
                'image','max:100000000'
            ],
            'status'=>'required','in:active,archived'
        ];
    }
}
