<?php

namespace App\Http\Controllers\Api;

use App\Events\DeliveryLocationUpdated;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveriesController extends Controller
{
    public function show( $id){
           $delivery = Delivery::query()->select([
                'id',
                'status',
                'order_id',
                DB::raw("ST_X(current_location) AS lat"), // to get the latitude from the point
                DB::raw("ST_Y(current_location) AS lng"), // to get the longitude from the point
           ])->where('id',$id)->firstOrFail();
           return $delivery;
    }
    public function update(Request $request,Delivery $delivery){

        $request->validate([
            'lng'=>['required','numeric'],
            'lat'=>'required|numeric',
        ]);
        $delivery->update([
           'current_location'=> DB::raw("POINT({$request->lat},{$request->lng})"),
        ]);
        event(new DeliveryLocationUpdated($request->lng,$request->lat,$delivery));
        return $delivery;
    }
}
