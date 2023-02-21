<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
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
    public function index()
    {
        return view('users.index', [
            'title' => 'User Management',
            'users' => User::filters(request(['search', 'status', 'role']))->paginate(15)->withQueryString(),
            'roles' => Role::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create-users')){
            return redirectNotAuthorized('users');
        }

        return view('users.create', [
            'title' => "Create New Users",
            'roles' => Role::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->safe()->only(['username']);

        $validated['password'] = Hash::make($request->password);

        $newUser = User::create($validated);
        $newUser->assignRole($request->role);

        if (!$newUser) {
            return back()->withInput()->with('danger', 'Failed to create new employee level');
        }

        return redirectWithAlert('users', 'success', 'New User Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', [
            'title' => "User Details",
            'user' => $user,
            'role' => $user->role(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        if ($request->user()->cannot('update', $user)){
            return redirectNotAuthorized('users');
        } 
        return view('users.edit', [
            'title' => "Edit User",
            'roles' => Role::all(),
            'user' => $user,
            'user_role' => $user->role()->id ?? null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if($request->username != $user->username){
            $validated = $request->safe()->only(['username']);
            $user->update($validated);
        } 
        
        $user->syncRoles($request->role);

        return redirectWithAlert('users', 'success', 'User Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        if($request->user()->cannot('delete', $user)){
            return redirectNotAuthorized('users');
        }

        $user->syncRoles();
        $user->delete();

        return redirectWithAlert('users', 'success', 'User deleted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, User $user)
    {
        if($request->user()->cannot('activate', $user)){
            return redirectNotAuthorized('users');
        }

        $user->changeStatus();

        return redirectWithAlert('users', 'success', 'User status changed');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request, User $user)
    {
        if($request->user()->cannot('change-password', $user)){
            return redirectNotAuthorized('users');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data = [
            'password' => Hash::make($request->password),
        ];

        $user->update($data);

        return redirectWithAlert('users', 'success', 'Password changed successfully');
    }
}
