
<div class="form-group">
    <x-form.input label="Name" class="form-control-lg" name="name" :value="$admin->name" />
</div>
<div class="form-group">
    <x-form.input label="Email" type="email" name="email" :value="$admin->email" />
</div>
<div class="form-group">
    <x-form.input label="Phone" type="text" name="phone_number" :value="$admin->phone_number" />
</div>
@if(!isset($admin_roles))
    <div class="form-group">
        <label for="password">Password</label>
        <input id="password" type="password" class="form-control" name="password"  autocomplete="new-password">
    </div>
@endif



<fieldset>
    <legend>{{ __('Roles') }}</legend>

    @foreach ($roles as $role)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" @checked(in_array($role->id,$admin_roles ?? []))>
            <label class="form-check-label">
                {{ $role->name }}
            </label>
        </div>
    @endforeach
</fieldset>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $button_label ?? 'Save' }}</button>
</div>
