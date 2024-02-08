<?php

namespace App\Concerns;

use App\Models\Role;

trait HasRoles
{
    public function roles()
    {
        return $this->morphToMany(Role::class, 'autherizable','role_user');
        // table name and morph column in pivot table then table name
    }
    public function hasAbility($ability)
    {
        $denied= $this->roles()->whereHas('abilities',function($q) use($ability){
            $q->where('ability',$ability)
                ->where('type','deny');
        })->exists();
        if($denied){
            return false;
        }
        return $this->roles()->whereHas('abilities',function($q) use($ability){
                $q->where('ability',$ability)
                ->where('type','allow');
        })->exists();

    }
}
