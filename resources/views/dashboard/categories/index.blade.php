    @extends('layouts.dashboard')
    @section('title','Categories')
    @section('breadcrumb')
        @parent
        <li class="breadcrumb-item active">Categories</li>
    @endsection

    @section('content')
    <div class="mb-5">
        @can('categories.create')
        <a href="{{route('dashboard.categories.create')}}" class="btn btn-sm btn-outline-primary" >Create</a>
        @endcan
        <a href="{{route('dashboard.categories.trash')}}" class="btn btn-sm btn-outline-dark" >Trash</a>
    </div>

    <x-alert type="success"/>
    <x-alert type="info"/>

    <form action="{{\Illuminate\Support\Facades\URL::current() }} " method="get" class="d-flex justify-content-between mb-4">
        <x-form.input name="name" placeholder="Name"  class="mx-2" value="{{request('name')}}"/>
        <select name="status" class="form-control mx-1">
            <option value="">All</option>
            <option value="active" @selected(request('status')=='active')>Active</option>
            <option value="inactive" @selected(request('status')=='inactive')>Archived</option>
        </select>
        <button class="btn btn-dark">Filter</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>ID</th>
                <th>Name</th>
                <th>Parent</th>
                <th>Product Number</th>
                <th>Status</th>
                <th>Created At</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            @forelse($categories as $category)
                <tr>
                    <td>
                        @if (isset($category->image))
                            <img src="{{asset('storage/'.$category->image)}}" height="50px" >
                        @endif
                    </td>
                    <td>{{$category->id}}</td>
                    <td><a href="{{route('dashboard.categories.show',$category->id)}}">{{$category->name}}</a></td>
                    <td> {{$category->parent->name}}</td> {{-- object form parent and get the name --}}
                    <td>{{$category->products_number}}</td>
                    <td>{{$category->status}}</td>
                    <td>{{$category->created_at}}</td>
                    <td>
                        {{--check if user has a perimetion--}}
                        @can('categories.update')
                             <a href="{{route('dashboard.categories.edit',[$category->id])}}" class="btn btn-sm btn-outline-success">Edit</a>
                        @endcan
                    </td>
                    <td>
                        @can('categories.delete')
                            <form method="post" action="{{route('dashboard.categories.destroy',[$category->id])}}">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9"> No Categories Found</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{$categories->withQueryString()->links()}}
@endsection
