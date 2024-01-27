<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    public function index(){
        $user="Ahmed Ghoneim";
        return view('dashboard/index', compact('user'));
    }
}
