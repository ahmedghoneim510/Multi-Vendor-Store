<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;

class AccessTokensControlller extends Controller
{


    public function store(Request $request){
        $request->validate([
            'email'=>'required|email|max:255',
            'password'=>'required|string|min:6',
            'device_name'=>'string|max:255',
            'abilities'=>'nullable|array'
        ]);

        $user=User::where('email',$request->email)->first();
        // model user must use HasApiTokens tarit in model so we can generate token
        if ($user && Hash::check($request->password, $user->password)) {
            $device_name=$request->post('device_name',$request->userAgent()); // get device name or useragent
            if($request->filled('abilities')){
            $token = $user->createToken($device_name, $request->post('abilities'));
            }else{
                $token = $user->createToken($device_name);
            }
            return Response::json([
                'code'=>1,
                'token'=>$token->plainTextToken,
                'user'=>$user,

            ],201);
        }
        return Response::json([
            'code'=>0,
            'msg'=>"Invalid Credentials",
        ],401);
    }



    public function destroy($token=null){
        $user=auth('sanctum')->user();
       // return $user;
        if($token==null){
            $user->currentAccessToken()->delete();
            return;
        }
        $personalAccessToken=PersonalAccessToken::findtoken($token);
        if($user->id==$personalAccessToken->tokenable_id && get_class($user) == $personalAccessToken->tokenable_type){
            $personalAccessToken->delete();
        }
        return;

    }
}
