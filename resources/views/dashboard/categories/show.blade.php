@extends('layouts.dashboard')
@section('title',$category->name)
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Categories</li>
    <li class="breadcrumb-item active">{{$category->name}}</li>
@endsection

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Store</th>
            <th>status</th>
            <th>Created At</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @php
            $products=$category->products()->with('store')->paginate(6);
        @endphp
        @forelse($products as $product)
            <tr>
                <td>
                    @if (isset($product->image))
                        <img src="{{asset('storage/'.$product->image)}}" height="50px" >
                    @endif
                </td>
                <td>{{$product->name}}</td>
                <td>{{$product->store->name}}</td>
                <td>{{$product->status}}</td>
                <td>{{$product->created_at}}</td>

            </tr>
        @empty
            <tr>
                <td colspan="5"> No Products Found</td>
            </tr>
        @endforelse
        </tbody>

    </table>
        {{$products->withQueryString()->links()}}

@endsection
