<?php

namespace App\Http\Controllers;

use App\Enums\EmploymentStatus;
use App\Models\Employee;
use App\Models\Training;
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
    public function index(Request $request)
    {
        $employee_count = Employee::where('employment_status', '!=', EmploymentStatus::Resigned)->get()->count();
        $department_employee_count = 0;
        $training_hour = 0;
        if (isset($request->user()->employee)) {
            $department_employee_count = Employee::where('employment_status', '!=', EmploymentStatus::Resigned)->whereHas('position', fn($query) => $query->where('department_id', ));

            $training_hour = $request->user()->employee->trainings;
            if (isset($training_hour)) {
                $training_hour = $training_hour->sum('training_length');
            } else {
                $training_hour = 0;
            }

        }

        return view('home', [
            'employee_count' => $employee_count,
            'department_employee_count' => $department_employee_count,
            'training_hour' => $training_hour,
        ]);
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
