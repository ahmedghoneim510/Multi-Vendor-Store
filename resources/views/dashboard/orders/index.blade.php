    @extends('layouts.dashboard')
    @section('title','Orders')
    @section('breadcrumb')
        @parent
        <li class="breadcrumb-item active">Orders</li>

    @endsection

    @section('content')
    <div class="mb-5">
        <a href="{{route('dashboard.orders.create')}}" class="btn btn-sm btn-outline-primary" >Create</a>
    </div>

    <x-alert type="success"/>
    <x-alert type="info"/>


    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Store Name</th>
                <th>Number</th>
                <th>Status</th>
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
                    <td>{{$order->total_price}}</td>
                    <td>{{$order->created_at}}</td>
                    <td>

                        <a href="{{route('dashboard.orders.edit',[$order->id])}}" class="btn btn-sm btn-outline-success">Edit</a>
                    </td>
                    <td>
                        <form method="post" action="{{route('dashboard.orders.destroy',[$order->id])}}">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
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
