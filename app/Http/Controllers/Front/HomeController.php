<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){ // igger loading with
        $products=Product::with('category')->active()->limit(8)->get(); // here get active product (scope) get 8 from it
        return view('front.home',compact('products'));
    }
}
