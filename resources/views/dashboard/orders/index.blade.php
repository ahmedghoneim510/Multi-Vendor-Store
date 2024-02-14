    @extends('layouts.dashboard')
    @section('title','Orders')
    @section('breadcrumb')
        @parent
        <li class="breadcrumb-item active">Orders</li>

    @endsection

    @section('content')
    <div class="mb-5">
        <a href="{{route('dashboard.orders-admins.create')}}" class="btn btn-sm btn-outline-primary" >Create</a>
    </div>

    <x-alert type="success"/>
    <x-alert type="info"/>

    <form action="{{\Illuminate\Support\Facades\URL::current() }} " method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="Name"  class="mx-2" value="{{request('name')}}"/>
        <select name="payment_status" class="form-control mx-1">
            <option value="">All</option>
            <option value="pending" @selected(request('payment_status')=='pending')>Pending</option>
            <option value="paid" @selected(request('payment_status')=='paid')>Paid</option>
            <option value="failed" @selected(request('payment_status')=='failed')>Failed</option>
        </select>
        <button class="btn btn-dark">Filter</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Store Name</th>
                <th>Order Number</th>
                <th>Status</th>
                <th>Payment Status</th>
                <th>Total</th>
                <th>Created At</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            @forelse($orders as $order)
                <tr>
                    <td>{{$order->id}}</td>
                    <td>{{$order->user->name}}</td>
                    <td>{{$order->store->name}}</td>
                    <td>{{$order->number}}</td>
                    <td>{{$order->status}}</td>
                    <td>{{$order->payment_status}}</td>
                    <td>{{$order->total_price}}</td>
                    <td>{{$order->created_at}}</td>
                    <td>

                        <a href="{{route('dashboard.orders-admins.edit',[$order->id])}}" class="btn btn-sm btn-outline-success">Edit</a>
                    </td>
                    <td>
                        <form method="post" action="{{route('dashboard.orders-admins.destroy',[$order->id])}}">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('dashboard.orders-admins.show', $order->id) }}" class="btn btn-sm btn-outline-info">View Order</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10"> No Products Found</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{$orders->withQueryString()->links()}}
@endsection
