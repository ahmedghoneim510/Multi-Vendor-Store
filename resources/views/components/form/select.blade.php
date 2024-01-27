
@props([
    'options', 'selected','name','label'=>false,
    ])
@if($label)
    <label>{{$label}}</label>
@endif
<select name="{{$name}}" {{ $attributes->class(
    [
        'form-control',
        'form-select',
        'is-invalid'=>$errors->has($name)
    ]) }}
>


    @foreach ($options as $value => $label)
        <option value="{{ $value }}" @selected($selected==$value)>
            {{ $label }}
        </option>
    @endforeach
</select>
@error($name)
<div class="invalid-feedback">
    {{'* '.$message}}
</div>
@enderror
