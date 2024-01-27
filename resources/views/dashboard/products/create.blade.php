@extends('layouts.dashboard')
@section('title','Categories')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Product</li>
    <li class="breadcrumb-item active">Create Product</li>

@endsection

@section('content')
   <form action="{{route('dashboard.products.store')}}" method="post" enctype="multipart/form-data">
        @csrf
       @include('dashboard.products._from')
   </form>


@endsection
