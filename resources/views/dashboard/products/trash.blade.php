    @extends('layouts.dashboard')
    @section('title','Trash Products')
    @section('breadcrumb')
        @parent
        <li class="breadcrumb-item active">Products</li>
        <li class="breadcrumb-item active">Trash</li>
    @endsection

    @section('content')
    <div class="mb-5">
        <a href="{{route('dashboard.products.index')}}" class="btn btn-sm btn-outline-primary" >Back</a>
    </div>
    <x-alert type="success"/>
    <x-alert type="info"/>
    <form action="{{\Illuminate\Support\Facades\URL::current() }} " method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="Name"  class="mx-2" value="{{request('name')}}"/>
        <select name="status" class="form-control mx-1">
            <option value="">All</option>
            <option value="active" @selected(request('status')=='active')>Active</option>
            <option value="inactive" @selected(request('status')=='inactive')>Archived</option>
            <option value="draft"  @selected(request('draft')=='draft')>Draft</option>
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
                <th>Status</th>
                <th>Deleted At</th>
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
                    <td>{{$product->category->name}}</td>
                    <td>{{$product->store->name}}</td>
                    <td>{{$product->status}}</td>
                    <td>{{$product->deleted_at}}</td>
                    <td>
                        <form method="post" action="{{route('dashboard.products.restore',[$product->id])}}">
                            @csrf
                            @method('put')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Restore</button>
                        </form>
                    </td>
                    <td>
                        <form method="post" action="{{route('dashboard.products.forceDelete',[$product->id])}}">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9"> No Categories Found</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{$products->withQueryString()->links()}}
@endsection
