<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Intl\Locale;

class ProfileController extends Controller
{
    public function edit(){
        Locale::setDefault('en');
        $user=Auth::user();
        $countries = Countries::getNames();
        $locales=Languages::getNames();


        return view('dashboard.profile.edit',[
            'user'=>$user,
            'countries'=>$countries,
            'locales'=>$locales,
        ]);
    }
//    public function update(Request $request)
//    {
//        $request->validate([
//            'first_name' => ['required', 'string', 'max:255'],
//            'last_name' => ['required', 'string', 'max:255'],
//            'birthday' => ['nullable', 'date', 'before:today'],
//            'gender' => ['required', 'in:male,female'],
//            'county' => ['required', 'size:2'],
//        ]);
//
//        $user = $request->user();
//
//
//        // Assuming there is a one-to-one relationship between User and Profile
//
//        $user->profile()->updateOrCreate([], $request->all())->save();
//
//        // You can also update the User model if needed
//        // $userData = $request->only(['other_user_fields']);
//        // $user->update($userData);
//
//        return redirect()->route('dashboard.profile.edit')->with('success', 'Profile Updated!');
//    }
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birthday' => ['nullable', 'date'],
            'gender' => ['required', 'in:male,female'],
            'country'=>['required','string','size:2']
        ]);

        $user = $request->user();
        $user->profile->fill($request->all())->save();
        return redirect()->route('dashboard.profile.edit')->with('success','Profile Updated !');
    }
}
/*

        $user=$request;
     //   $user->profile->fill($request->all())->save();
        // nested of all the next code  save used to insert if it's new and update if it exist
        // incase of using relation forigen key value will be set with value

        $profile=$user->profile;
        if(isset($profile->first_name)){
            $user->profile->update($request->all())->save();
        }else{
            $request->merge([
                'user_id'=>$user->id,
            ]);
            Profile::create($request->all());
           // $user->profile()->create($request->all())->save(); // create a profile based on relation and id then pass data
        }

      //  return redirect()->route('dashboard.profile.edit')->with('success','Profile Updated !');



*/
