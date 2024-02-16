<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Response;
class CategoriesController extends Controller
{
    public function __construct(){
        $this->middleware('auth:sanctum')->except('index','show');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories=Category::filter(request()->query())->with('parent:name')->paginate();
        return CategoriesResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'nullable|string|max:255',
            'parent_id'=>'nullable|exists:categories,id',
            'status'=>'in:active,archived',
        ]);
        $user=$request->user();
        if(!$user->tokenCan('categories.create')){
           return Response::json([
               'code'=>0,
               'msg'=>"Not allowed"
           ],403);
        }
        $category=Category::create($request->all());
        return Response::json($category,201, [
            'Location' => route('categories.show', $category->id),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category=Category::find($id);
        if($category){
        return new CategoriesResource($category);
        }
        return Response::json([
            'code'=>0,
            'msg'=>"Category not found",
        ],404);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category=Category::findOrFail($id);
        if(!$category){
            return Response::json([
                'code'=>0,
                'msg'=>"Category not found",
            ],404);
        }
        $request->validate([
            'name'=>'sometimes|required|string|max:255',
            'description'=>'nullable|string|max:255',
            'parent_id'=>'nullable|exists:categories,id',
            'status'=>'in:active,inactive',
        ]);
        $user=$request->user();
        if(!$user->tokenCan('categories.update')){
            return Response::json([
                'code'=>0,
                'msg'=>"Not allowed"
            ],403);
        }
        //return $request->status;
        $category->update($request->all());
        return Response::json($category,201, [
            'Location' => route('categories.show', $category->id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category=Category::findOrFail($id);
        if(!$category){
            return Response::json([
                'code'=>0,
                'msg'=>"Category not found",
            ],404);
        }
        return Response::json([
            'code'=>1,
            'msg'=>"Category deleted",
        ],201);
    }
}
