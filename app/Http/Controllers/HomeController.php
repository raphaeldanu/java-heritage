<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function editPassword()
    {
        return view('change-password', [
            'title' => 'Change Password',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'old_password' => 'required|current_password',
            'new_password' => 'required|confirmed|min:8',
        ];
        $request->validate($rules);

        $user = $request->user();
        $user->password = Hash::make($request->new_password);
        $user->save();
        $request->user()->fresh();

        return redirect()->route('home')->with('success', "Your password changed successfully");
    }
}
