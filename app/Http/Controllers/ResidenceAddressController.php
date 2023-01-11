<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\ResidenceAddress;
use App\Http\Requests\StoreResidenceAddressRequest;
use App\Http\Requests\UpdateResidenceAddressRequest;

class ResidenceAddressController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Employee $employee)
    {
        if ($request->user()->cannot('create', [ResidenceAddress::class, $employee])){
            return back()->with('warning', 'Not Authorized');
        }

        if ($employee->residence != null){
            return back()->with('warning', $employee->name.' already had a residence address!');
        }

        return view('residence-address.create', [
            'title' => 'Add Residence Address',
            'employee' => $employee,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreResidenceAddressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResidenceAddressRequest $request, Employee $employee)
    {
        $validated = $request->validated();
        if($employee->residence != null){
            return redirect()->route('employees.show', ['employee' => $employee])->with('warning', $employee->name.' already have a residence address!'); 
        }
        $employee->residence()->create($validated);
        $newResidence = $employee->residence;
        if(!$newResidence){
            return back()->withInput()->with('danger', 'Failed to add address');
        };
        return redirect()->route('employees.show', ['employee' => $employee])->with('success', 'Residence Address added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ResidenceAddress  $residenceAddress
     * @return \Illuminate\Http\Response
     */
    public function show(ResidenceAddress $residenceAddress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ResidenceAddress  $residenceAddress
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Employee $employee)
    {
        if ($request->user()->cannot('update', [$employee->residence, $employee])){
            return back()->with('warning', 'Not Authorized');
        }

        return view('residence-address.edit', [
            'title' => 'Edit Residence Address',
            'employee' => $employee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateResidenceAddressRequest  $request
     * @param  \App\Models\ResidenceAddress  $residenceAddress
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResidenceAddressRequest $request, Employee $employee)
    {
        $validated = $request->validated();
        if (!$employee->residence()->update($validated)) {
            return back()->withInput()->with('danger', 'Failed to update address');
        };
        return redirect()->route('employees.show', ['employee' => $employee])->with('success', 'Residence Address updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Employee $employee)
    {
        if ($request->user()->cannot('delete', [$employee->residence, $employee])){
            return back()->with('warning', 'Not Authorized');
        }

        $employee->residence()->delete();
        return redirect()->route('employees.show', ['employee' => $employee])->with('success', 'Residence deleted successfully');
    }
}
