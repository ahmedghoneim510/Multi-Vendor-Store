
<div class="form-group">
    <x-form.input label="Name" class="form-control-lg" name="name" :value="$user->name" />
</div>
<div class="form-group">
    <x-form.input label="Email" type="email" name="email" :value="$user->email" />
</div>
@if(!isset($user_roles))
    <div class="form-group">
        <label for="password">Password</label>
        <input id="password" type="password" class="form-control" name="password"  autocomplete="new-password">
    </div>
@endif

<fieldset>
    <legend>{{ __('Roles') }}</legend>

    @foreach ($roles as $role)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" @checked(in_array($role->id,$user_roles ?? []))>
            <label class="form-check-label">
                {{ $role->name }}
            </label>
        </div>
    @endforeach
</fieldset>

<div class="form-group">
    <button type="submit" class="btn btn-primary">{{ $button_label ?? 'Save' }}</button>
</div>
