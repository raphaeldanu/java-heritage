<?php

namespace App\Http\Controllers;

use App\Enums\FamilyRelation;
use App\Enums\Gender;
use App\Models\Family;
use App\Http\Requests\StoreFamilyRequest;
use App\Http\Requests\UpdateFamilyRequest;
use App\Models\Employee;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Employee $employee)
    {
        if ($request->user()->cannot('create', [Family::class, $employee])) {
            return redirect()->route('employees.show', ['employee' => $employee])->with('warning', 'Not Authorized');
        }

        if($employee->gender == Gender::Male){
            $except = 'husband';
        } else {
            $except = 'wife';
        }
        $family_relation = collect(FamilyRelation::cases())->where('value', '!=', $except);
        $family_relations = [];
        foreach ($family_relation as $item) {
            $family_relations[$item->value] = $item->name;
        }
        
        return view('families.create', [
            'title' => 'Add New Family Member',
            'employee' => $employee,
            'family_relations' => $family_relations,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFamilyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFamilyRequest $request, Employee $employee)
    {
        $validated = $request->validated();
        $employee->families()->create($validated);
        return redirect()->route('employees.show', ['employee' => $employee])->with('success', 'Family Member added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Employee $employee, Family $family)
    {
        if ($request->user()->cannot('view', [$family, $employee])) {
            return redirect()->route('employees.show', ['employee' => $employee])->with('warning', 'Not Authorized');
        }

        return view('families.show', [
            'title' => 'Family Member Detail',
            'family' => $family,
            'employee' => $employee,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Employee $employee, Family $family)
    {
        if ($request->user()->cannot('update', $family)) {
            return redirect()->route('employees.show', ['employee' => $employee])->with('warning', 'Not Authorized');
        }

        if($employee->gender == Gender::Male){
            $except = 'husband';
        } else {
            $except = 'wife';
        }
        $family_relation = collect(FamilyRelation::cases())->where('value', '!=', $except);
        $family_relations = [];
        foreach ($family_relation as $item) {
            $family_relations[$item->value] = $item->name;
        }

        return view('families.edit', [
            'title' => 'Edit Family Member',
            'employee' => $employee,
            'family' => $family,
            'family_relations' => $family_relations
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFamilyRequest  $request
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFamilyRequest $request, Employee $employee, Family $family)
    {
        $validated = $request->validated();
        if ($family->update($validated)) {
            return redirect()->route('employees.show', ['employee' => $family->employee]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Family  $family
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Employee $employee, Family $family)
    {
        if ($request->user()->cannot('delete', $family)) {
            return redirect()->route('employees.show', ['employee' => $family->employee])->with('warning', 'Not Authorized');
        }

        $family->delete();
        return redirect()->route('employees.show', ['employee' => $employee])->with('success', 'Family Member Deleted Successfully');
    }
}
