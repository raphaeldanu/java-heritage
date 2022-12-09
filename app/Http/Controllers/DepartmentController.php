<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use Illuminate\Http\Request;

use function Pest\Laravel\delete;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('view-departments')) {
            return redirectNotAuthorized('home');
        }

        return view('departments.index', [
            'title' => "Departments",
            'departments' => Department::paginate(15),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create-departments')) {
            return redirectNotAuthorized('departments');
        }

        return view('departments.create', [
            'title' => "Create New Department",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDepartmentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDepartmentRequest $request)
    {
        $validated = $request->validated();

        Department::create($validated);

        return redirectWithAlert('departments', 'success', 'New Department Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Department $department)
    {
        if ($request->user()->cannot('view-departments')) {
            return redirectNotAuthorized('home');
        }

        return view('departments.show', [
            'title' => "Departments",
            'department' => $department,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Department $department)
    {
        if ($request->user()->cannot('edit-departments')) {
            return redirectNotAuthorized('departments');
        }

        return view('departments.edit', [
            'title' => "Edit Department",
            'department' => $department,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDepartmentRequest  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        if ($request->name != $department->name) {
            $validated = $request->validated();
            $department->update($validated);
        }

        return redirectWithAlert('departments', 'success', 'Departments Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Department $department)
    {
        if ($request->user()->cannot('delete-departments')) {
            return redirectNotAuthorized('departments');
        }

        $department->delete();

        return redirectWithAlert('departments', 'success', 'Department deleted successfully');
    }
}
