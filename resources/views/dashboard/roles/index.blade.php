    @extends('layouts.dashboard')
    @section('title','Roles')
    @section('breadcrumb')
        @parent
        <li class="breadcrumb-item active">Roles</li>
    @endsection

    @section('content')
    <div class="mb-5">
        @can('create','App\Models\Role')
        <a href="{{route('dashboard.roles.create')}}" class="btn btn-sm btn-outline-primary" >Create</a>
        @endcan
        {{--        <a href="{{route('dashboard.roles.trash')}}" class="btn btn-sm btn-outline-dark" >Trash</a>--}}
    </div>

    <x-alert type="success"/>
    <x-alert type="info"/>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created At</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            @forelse($roles as $role)
                <tr>
                    <td>{{$role->id}}</td>
                    <td><a href="{{route('dashboard.roles.show',$role->id)}}">{{$role->name}}</a></td>
                    <td>{{$role->created_at}}</td>
                    <td>
                        {{--check if user has a perimetion--}}
                        @can('update',$role)
                             <a href="{{route('dashboard.roles.edit',[$role->id])}}" class="btn btn-sm btn-outline-success">Edit</a>
                        @endcan
                    </td>
                    <td>
                        @can('delete',$role)
                            <form method="post" action="{{route('dashboard.roles.destroy',[$role->id])}}">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4"> No Roles Found</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{$roles->withQueryString()->links()}}
@endsection
