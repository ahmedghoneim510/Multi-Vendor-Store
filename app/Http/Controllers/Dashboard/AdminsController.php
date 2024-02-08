<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('admins.view');
        $admins=Admin::paginate();
        return view('dashboard.admins.index',compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('admins.create');
        return view('dashboard.admins.create',[
            'admin'=>new Admin(),
            'roles'=>Role::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('admins.create');
        $request->validate([
            'name'=>'required|string|max:255',
            'roles'=>'required|array',
        ]);
        $admin=Admin::create($request->all());
        $admin->roles()->attach($request->roles);
        return redirect()->route('dashboard.admins.index')->with('success','Admin created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        Gate::authorize('admins.update');
        $roles=Role::all();
        $admin_roles=$admin->roles()->pluck('id')->toArray();
        return view('dashboard.admins.edit',compact('admin','roles','admin_roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Admin $admin)
    {
        Gate::authorize('admins.update');
        $request->validate([
            'name'=>'required|string|max:255',
            'roles'=>'required|array',
        ]);
        $admin->update($request->all());
        $admin->roles()->sync($request->roles);
        return to_route('dashboard.admins.index')->with('success','Admin updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('admins.delete');
        Admin::destroy($id);
        return redirect()->route('dashboard.admins.index')->with('success','Admin deleted successfully');
    }
}
