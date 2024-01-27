@extends('layouts.dashboard')
@section('title','Categories')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Categories</li>
    <li class="breadcrumb-item active">Create Catregory</li>

@endsection

@section('content')
   <form action="{{route('dashboard.categories.store')}}" method="post" enctype="multipart/form-data">
        @csrf
       @include('dashboard.categories._from')
   </form>


@endsection
