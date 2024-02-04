<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request=request();
        //$user=Auth::user();    we use global scope nested of this
        //        if($user->store_id){
        //            $products=Product::where('store_id',$user->store_id)->paginate();
        //        }
        //        else{
        //        $products=Product::paginate();
        //        }
        $products=Product::with('store','category')->filter($request->query())->paginate(10);
        // select * from product
        // select * from catogries where id in (*)
        //select * from stores where id in (*)

        // in case we use category
        // $categories=category::with('products')->find(1);
        // foreach($categories->products as $product) { $product->name  } every category has many product so we use foreach

        return view('dashboard.products.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $product=new Product();
        return view('dashboard.products.create',compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>['required','string','max:255'],
            'status'=>['required','in:active,archived,draft'],
            'price'=>['required','numeric'],
        ]);
//        dd($request);
        $request->merge([
            'slug'=>Str::slug($request->name),
        ]);
        $data=$request->except('image');
        $data['image']=$this->upload_image($request);
        Product::create($data);
        return to_route('dashboard.products.index')->with('success',' product created !');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $product=Product::findorFail($id);
        $tags=implode(',',$product->tags()->pluck('name')->toArray()); // get tag name and turn to array then impolde to stirng
        //dd($tags);
        return view('dashboard.products.edit',compact('product','tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product )
    {
        $request->validate([
            'name'=>['required','string','max:255'],
            'status'=>['required'],
        ]);

        $old_image=$product->image; // get old-image name so i could delete it
        $data=$request->except('image'); // get all data except image so can i add key image and insert it in table simplly
        // use function upload_image here
        $new_image=$this->upload_image($request);
        if($new_image){
            $data['image']=$new_image;
        }







        $product->update($request->except('tags'));

        // tags come like this cotton,stripted,egyption
        $tags=json_decode($request->post('tags'));
        //dd($tags);
        $tag_ids=[];
        $saved_tag=Tag::all(); // get all data from tag rather than every loop i get it
        foreach ($tags as $item){
            $slug=Str::slug($item->value);
//            $tag=Tag::where('slug',$slug)->first();
            $tag=$saved_tag->where('slug',$slug)->first();
            if(!$tag){
                $tag=Tag::create([
                    'name'=>$item->value,
                    'slug'=>$slug
                ]);

            }

            $tag_ids[]=$tag->id;
        }
        //dd($tag_ids);
        $product->tags()->sync($tag_ids); // save all new ids and delete old
        $old_image=$product->image; // get old-image name so i could delete it
        $data=$request->except('image'); // get all data except image so can i add key image and insert it in table simplly
        // use function upload_image here
        $new_image=$this->upload_image($request);
        if($new_image){
            $data['image']=$new_image;
        }
        if($old_image && $new_image){
            Storage::disk('public')->delete($old_image);
        }
        $product->update($data);
        return to_route('dashboard.products.index')->with('success','Product Updated');
    }

    public function upload_image(Request $request)
    {
        if(!$request->hasFile('image')){
            return;
        }
        $file=$request->file('image');
        $path=$file->store('uploads',[
            'disk'=>'public'
        ]);

        return $path;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $product=Product::findOrFail($id);
        $product->delete();
        return to_route('dashboard.products.index')->with('success','Products Deleted !');
    }
    public function trash(){
        $request=request();
        $products=Product::onlyTrashed()->filter($request->query())->paginate();
        return view('dashboard.products.trash',compact('products'));
    }
    public function restore(Request $request , $id){
        $product=Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return to_route('dashboard.products.trash')
            ->with('success','Category Restored!');
    }
    public function forceDelete( $id){
        $product=Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();
        if ($product->image) {
            $imagePath = $product->image;

            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }
        return to_route('dashboard.products.trash')
            ->with('success','Category Deleted!');
    }




}
