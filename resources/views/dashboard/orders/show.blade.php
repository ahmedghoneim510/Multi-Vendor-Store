@extends('layouts.dashboard')
@section('title',$order->name)
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Orders</li>
    <li class="breadcrumb-item active">{{$order->name}}</li>

@endsection

@section('content')
    <div class="mb-5">
        <a href="{{route('dashboard.orders-admins.index')}}" class="btn btn-sm btn-outline-secondary" >Return</a>
    </div>
        <div class="container col-md">
            <div class="col-md-12">
                <h1>Order Details</h1>
                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                <p><strong>Customer Name:</strong> {{ $order->user->name }}</p>
                <p><strong>Total Price:</strong> ${{ $order->total_price }}</p>
                <p><strong>Quantity:</strong> {{ $order->orderItems()->count() }}</p>
                @php
                    $count= 1;
                 @endphp
                @foreach($order->orderItems as $order_item)
                    <div class="card mb-2">
                        <div class="card-body">
                            <p><strong>Number :</strong> {{ $count++}}</p>
                            <p><strong>Product Name: </strong> {{ $order_item->product_name }}</p>
                            <p><strong>Product Price: </strong> ${{ $order_item->price }}</p>
                            <p><strong>Quantity: </strong> {{ $order_item->quantity }}</p>
                            <p class="card-text"><strong>Discription: </strong> {{ $order_item->product->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
@endsection
