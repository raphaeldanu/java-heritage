<?php

namespace App\Http\Controllers;

use App\Models\SalaryRange;
use App\Http\Requests\StoreSalaryRangeRequest;
use App\Http\Requests\UpdateSalaryRangeRequest;

class SalaryRangeController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSalaryRangeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalaryRangeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalaryRange  $salaryRange
     * @return \Illuminate\Http\Response
     */
    public function show(SalaryRange $salaryRange)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalaryRange  $salaryRange
     * @return \Illuminate\Http\Response
     */
    public function edit(SalaryRange $salaryRange)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSalaryRangeRequest  $request
     * @param  \App\Models\SalaryRange  $salaryRange
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSalaryRangeRequest $request, SalaryRange $salaryRange)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalaryRange  $salaryRange
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalaryRange $salaryRange)
    {
        //
    }
}
