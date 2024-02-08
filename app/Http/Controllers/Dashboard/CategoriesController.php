<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;


class CategoriesController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Gate::denies('categories.view')) //check if user has permission to view categories
        {
            abort(403); // we also can use gate::allows
        }
        $request=request();
        //$request->input(); // get data from url  , ->query('name') get name from url
//        $query=Category::query();
//        if($name=$request->query('name')){
//            $query->where('name','like',"%{$name}%");
//        }
//        if($status=$request->query('status')){
//            $query->where('status',$status);
//        }
        $categories=Category::with('parent') // realation with parent
            /*leftJoin('categories as parents' , 'parents.id','=','categories.parent_id')
            ->select([
                'parents.name as parent_name',
                'categories.*'
            ])*/
            ->withCount([
                'products as products_number'=>function($query){  // return number of product and status= active
                    $query->where('status','active');
                }
                //this same of -> selectRow('select count(*) from products where category_id=categories.id and status='active' as product_number ')
            ])
            ->filter($request->query())
            ->paginate(4);
            //->orderBy('categories.name')

      //  $categories=$query->paginate(4); // return collection (object of class objection)
        return view('dashboard.categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(!Gate::allows('categories.create')) //check if user has permission to view categories
        {
            abort(403); // we also can use gate::allows
        }
        $parents=Category::all();
        $category=new Category();
       return view('dashboard.categories.create',compact('category','parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('categories.create'); //check if user has permission to create  categories
        // validation
        $request->validate(Category::rules(),[
            'required'=>'This field (:attribute) is required !',
            'unique'=>'This name already exists!'
        ]);
        //  request merge (slug) to insert in db
        $request->merge([
            'slug'=>Str::slug($request->name),
        ]);

        // get data expect image-field to take it from upload_image fun
        $data=$request->except('image');

        $data['image']=$this->upload_image($request);

        // create new row
        Category::create($data);
        // PRG
        return to_route('dashboard.categories.index')->with('success','category created !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        Gate::authorize('categories.view'); //check if user has permission to view categories
        return view('dashboard.categories.show',compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Gate::authorize('categories.update'); //check if user has permission to update categories

//        $parents=Category::where('id','<>',$id)
//            ->whereNotNull('parent_id')
//            ->orwhere('parent_id','<>',$id)
//            ->get();

            $parents = Category::where('id', '!=', $id)
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id')
                    ->orWhere('parent_id', '<>', $id); // != is equal <>
            })->get();

            $category=Category::find($id);
            if(!$category){
            return to_route('dashboard.categories.index')->with('info','Record not found!');
            }
        return view('dashboard.categories.edit',compact('category','parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request,  $id) // use commend to create it make:request + name
    {
        Gate::authorize('categories.update'); //check if user has permission to update categories
        //dd($id);
        // validation
        //$request->validate(Category::rules($id));

        $category=Category::findOrFail($id);

        $old_image=$category->image; // get old-image name so i could delete it
        $data=$request->except('image'); // get all data except image so can i add key image and insert it in table simplly
        // use function upload_image here
        $new_image=$this->upload_image($request);
        if($new_image){
            $data['image']=$new_image;
        }
        // mass assignment
        $category->update($data);
        if($old_image && $new_image){
            Storage::disk('public')->delete($old_image);
        }
        // prg
        return to_route('dashboard.categories.index')->with('success','category Updated !');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('categories.delete'); //check if user has permission to delete categories
        $category=Category::findOrFail($id);
        $category->delete();

       // Category::destroy($id);
        return to_route('dashboard.categories.index')->with('success','Category Deleted !');

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
    public function trash(){
        $categories=Category::onlyTrashed()->paginate();
        return view('dashboard.categories.trash',compact('categories'));
    }
    public function restore(Request $request , $id){
        $category=Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        return to_route('dashboard.categories.trash')
            ->with('success','Category Restored!');
    }
    public function forceDelete( $id){
        $category=Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();
        if($category->image){
            Storage::disk('public')->delete($category->image);
        }
        return to_route('dashboard.categories.trash')
            ->with('success','Category Deleted!');
    }
}
