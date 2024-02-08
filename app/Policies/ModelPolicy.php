<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ModelPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function before($user, $ability) // this function will run before any other gate
    {
        if ($user->super_admin) {
            return true;
        }
    }
    public function __call($name, $arguments) // used when the method is not found in the class
    {
        $class_name = str_replace('Policy', '', class_basename($this)); // like RolePolicy
        $class_name = Str::plural(Str::lower($class_name)); // make it roles

        if ($name == 'viewAny') { // check if the method is viewAny then change it to view
            $name = 'view';
        }
        $ability = $class_name . '.' . Str::kebab($name); // roles.view
        $user = $arguments[0]; // get the user

        if (isset($arguments[1])) {
            $model = $arguments[1];
            if ($model->store_id !== $user->store_id) {
                return false;
            }
        }

        return $user->hasAbility($ability);
    }
}
