<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class InvalidOrderException extends Exception
{
//    public function report(){ // how can i return a report of the exception
//
//    }
    public function render(Request $request){ // how can i reutrn error message
        return Redirect::route('home')->withInput()->withErrors([
            'message' =>$this->getMessage(),
        ])->with('info',$this->getMessage());

    }
}
