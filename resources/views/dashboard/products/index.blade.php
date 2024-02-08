    @extends('layouts.dashboard')
    @section('title','Products')
    @section('breadcrumb')
        @parent
        <li class="breadcrumb-item active">Products</li>

    @endsection

    @section('content')
    <div class="mb-5">
        @can('create','App\Models\Product')
        <a href="{{route('dashboard.products.create')}}" class="btn btn-sm btn-outline-primary" >Create</a>
        @endcan
        <a href="{{route('dashboard.products.trash')}}" class="btn btn-sm btn-outline-dark" >Trash</a>
    </div>

    <x-alert type="success"/>
    <x-alert type="info"/>
    <form action="{{\Illuminate\Support\Facades\URL::current() }} " method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="Name"  class="mx-2" value="{{request('name')}}"/>
        <select name="status" class="form-control mx-1">
            <option value="">All</option>
            <option value="active" @selected(request('status')=='active')>Active</option>
            <option value="inactive" @selected(request('status')=='inactive')>draft</option>
            <option value="draft" @selected(request('status')=='draft')>Archived</option>
        </select>
        <button class="btn btn-dark">Filter</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Store</th>
                <th>price</th>
                <th>status</th>
                <th>Created At</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            @forelse($products as $product)
                <tr>
                    <td>
                        @if (isset($product->image))
                            <img src="{{asset('storage/'.$product->image)}}" height="50px" >
                        @endif
                    </td>
                    <td>{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    {{-- select * from category where id=$product->category_id   n time  --> low preformance --}}
                    {{-- so inseted of that we can make a better way in controller --}}
                    <td>{{$product->category->name}}</td>  {{-- we use category (relation name) then get the name --}}
                    <td>{{$product->store->name}}</td>
                    <td>{{$product->price}}</td>
                    <td>{{$product->status}}</td>
                    <td>{{$product->created_at}}</td>
                    <td>
                        @can('update',$product)
                        <a href="{{route('dashboard.products.edit',[$product->id])}}" class="btn btn-sm btn-outline-success">Edit</a>
                        @endcan
                    </td>
                    <td>
                        @can('delete',$product)
                        <form method="post" action="{{route('dashboard.products.destroy',[$product->id])}}">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10"> No Products Found</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{$products->withQueryString()->links()}}
@endsection
