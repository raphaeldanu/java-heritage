<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('view-roles')){
            return redirect('/home')->with('warning', 'Not Authorized');
        }

        return view('roles.index', [
            'title' => 'Roles Management',
            'roles' => Role::paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create-roles')){
            return redirect('/roles')->with('warning', 'Not Authorized');
        }

        return view('roles.create', [
            'title' => 'Create New Role',
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('create-roles')){
            return redirect('/roles')->with('warning', 'Not Authorized');
        }

        $validated = $request->validate([
            'name' => 'string|required|unique:roles,name',
        ]);

        $permit = $request->collect()
                          ->except(['_token', 'name', 'submit'])
                          ->keys();

        $newRoles = Role::create($validated);
        foreach ($permit as $permit_id){
            $newRoles->givePermissionTo($permit_id);
        }

        return redirect('roles')->with('success', 'Role Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Role $role)
    {
        if ($request->user()->cannot('view-roles')){
            return redirect('/home')->with('warning', 'Not Authorized');
        }

        return view('roles.show', [
            'title' => 'Roles Information',
            'role' => $role,
            'permissions' => $role->permissions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Role $role)
    {
        if ($request->user()->cannot('update-roles')){
            return redirect('/roles')->with('warning', 'Not Authorized');
        }

        return view('roles.edit', [
            'title' => 'Edit Role',
            'role' => $role,
            'old_permit' => collect($role->permissions),
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        if ($request->user()->cannot('update-roles')){
            return redirect('/roles')->with('warning', 'Not Authorized');
        }

        $validated = $request->validate([
            'name' => ['string', 
                       'required', 
                       Rule::unique('roles')->ignore($role->id),
                    ],
        ]);

        $permit = $request->collect()
                          ->except(['_token', 'name', 'submit', '_method'])
                          ->keys();

        $permissions = Permission::whereIn('id', $permit)->get();

        $role->update($validated);
        $role->syncPermissions($permissions);

        return redirect('roles')->with('success', 'Role Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Role $role)
    {
        if ($request->user()->cannot('delete', $role)){
            return redirect('/roles')->with('warning', 'Not Authorized');
        }

        $role->permissions()->detach();
        $role->delete();
        
        return redirect('roles')->with('success', 'Roles deleted Successfully');
    }
}
