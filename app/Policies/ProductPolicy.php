<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy extends ModelPolicy
{

    public function update( $user, Product $product): bool
    {
        return $user->hasAbility('roles.update');
    }
        public function delete( $user, Product $product): bool
    {
        return $user->hasAbility('roles.delete');
    }

}
