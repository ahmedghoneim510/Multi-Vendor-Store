@extends('layouts.dashboard')
@section('title','Categories')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Product</li>
    <li class="breadcrumb-item active">Create Product</li>

@endsection

@section('content')
    <form action="{{route('dashboard.products.import')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <x-form.input label="Products count" name="count"  class="form-control-lg" type="text"/>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" class="form-control btn btn-primary">
        </div>
    </form>


@endsection
