<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    use HasFactory;

    protected $fillable=['name'];

    public function abilities()
    {
        return $this->hasMany(RoleAbility::class);
    }
    public static function createWithAbilites(Request $request)
    {
        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
            ]);
            foreach ($request->post('abilities') as $ability =>$value) {
                RoleAbility::create([
                    'role_id' => $role->id,
                    'ability' => $ability,
                    'type' => $value
                ]);
            }
            DB::commit();
            return $role;
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public  function updateWithAbilites(Request $request)
    {
        DB::beginTransaction();
        try {

            $this->update([
                'name' => $request->name,
            ]);
            //RoleAbility::where('role_id',$role->id)->delete();
            foreach ($request->post('abilities') as  $ability =>$value) {
                // take two array and check if the first array is exist in the database it will update it and if not it will create it
                RoleAbility::updateOrCreate( [
                    'role_id' => $this->id,
                    'ability' => $ability,
                ],[
                    'type' => $value
                ]);
            }
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $this;
    }


}
