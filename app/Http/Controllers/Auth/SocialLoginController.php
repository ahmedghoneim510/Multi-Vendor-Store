<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\Concerns\Has;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    public function handleProviderCallback($provider)
    {
        try {
            $user_provider = Socialite::driver($provider)->user();
            $user=User::where([
                'provider'=>$provider,
                'provider_id'=>$user_provider->id,
            ])->first();
            if(!$user){
                $user=User::create([
                    'name'=>$user_provider->name,
                    'email'=>$user_provider->email,
                    'password'=>Hash::make(Str::random(8)),
                    'provider'=>$provider,
                    'provider_id'=>$user_provider->id,
                    'provider_token'=>$user_provider->token,
                ]);
            }
            Auth::login($user,true);
            return to_route('home');
        }catch (\Exception $e){
           return redirect()->route('login')->with('error','Something went wrong');
        }
    }
}
