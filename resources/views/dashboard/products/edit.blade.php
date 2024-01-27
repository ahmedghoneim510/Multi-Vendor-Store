@extends('layouts.dashboard')
@section('title','Edit Categories')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Categories</li>
    <li class="breadcrumb-item active">Edit Category</li>
@endsection

@section('content')
   <form action="{{route('dashboard.products.update',$product->id)}}" method="post" enctype="multipart/form-data">
        @csrf
       @method('put')
        @include('dashboard.products._from',[
            'button_label'=>'Update'
        ])
   </form>


@endsection
