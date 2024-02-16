@extends('layouts.dashboard')
@section('title','Edit Order')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Orders</li>
    <li class="breadcrumb-item active">Edit Order</li>
@endsection

@section('content')
   <form action="{{route('dashboard.orders-admins.update',$order->id)}}" method="post" enctype="multipart/form-data">
        @csrf
       @method('put')
        @include('dashboard.orders._from',[
            'button_label'=>'Update'
        ])
   </form>


@endsection
