@props([
        'type'=>'text',  // this mean we give a defult value to type . we don't need to use {this ?? 'text'} '
        'name',
        'value'=>'',
        'label'=>false,
        'accept'=>' '
])
{{--$attributes used to print all variable that pass to com so we use props to prevent print it twice and we can use -> to chice spacific att--}}
@if($label )
<label>{{$label}}</label>
@endif

<input
    type="{{$type }}"
    name="{{$name}}"
    accept="{{$accept}}"
    value="{{old($name,$value) }}"
    {{$attributes->class([
        'form-control',
        'is-invalid'=>$errors->has($name)
    ])}}
>
@error($name)
<div class="invalid-feedback">
    {{'* '.$message}}
</div>
@enderror




{{--
@class(['form-control',
    'is-invalid'=>$errors->has($name)
    ])
--}}
