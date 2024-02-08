    <div class="form-group">
            <x-form.input label="Role Name" name="name"  :value="$role->name" type="text"/>
    </div>
    <feildset>
        <legend>{{__('Abilities')}}</legend>
        @foreach(app('abilities') as $ability_code=>$ability_name)
           <div class="row form-group mb-2">
               <div class="col-md-6 mb-1 ">
                    {{$ability_name}}
               </div>
               <div class="col-md-2">
                   <input type="radio" name="abilities[{{ $ability_code }}]" value="allow" value="allow" @checked(($role_abilities[$ability_code]??'')=='allow') >
                   Allow
               </div>
               <div class="col-md-2">
                   <input type="radio" name="abilities[{{$ability_code}}]" value="deny" @checked(($role_abilities[$ability_code]??'')=='deny')>
                   Deny
               </div>
               <div class="col-md-2">
                   <input type="radio" name="abilities[{{$ability_code}}]" value="inherit" @checked(($role_abilities[$ability_code]??'')=='inherit')>
                   Inherit
               </div>
           </div>
            <hr>
        @endforeach
    </feildset>
    <div class="form-group">
        <input type="submit" name="submit" value="{{$button_label ?? 'Save'}}" class="form-control btn btn-primary">
    </div>
