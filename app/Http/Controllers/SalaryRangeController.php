<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\SalaryRange;
use Illuminate\Http\Request;
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
    public function index(Request $request)
    {
        if ($request->user()->cannot('view-salary-ranges')) {
            return redirectNotAuthorized('home');
        }

        return view('salary-ranges.index', [
            'title' => "Salary Ranges",
            'levels' => Level::all(),
            'salaryRanges' => SalaryRange::filters(request(['search', 'level_id']))->paginate(15)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create-salary-ranges')) {
            return redirectNotAuthorized('salary-ranges');
        }

        return view('salary-ranges.create', [
            'title' => "Create New Salary Ranges",
            'levels' => Level::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSalaryRangeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSalaryRangeRequest $request)
    {
        $validated = $request->validated();
        $newSalaryRange = SalaryRange::create($validated);
        if(!$newSalaryRange){
            return back()->withInput()->with('danger', 'Failed to save salary range');
        }
        return redirectWithAlert('salary-ranges', 'success', 'Salary Range created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalaryRange  $salaryRange
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, SalaryRange $salaryRange)
    {
        if ($request->user()->cannot('view-salary-ranges')) {
            return redirectNotAuthorized('home');
        }

        return view('salary-ranges.show', [
            'title' => 'Salary Range Detail',
            'salaryRange' => $salaryRange
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalaryRange  $salaryRange
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, SalaryRange $salaryRange)
    {
        if ($request->user()->cannot('update', $salaryRange)) {
            return redirectNotAuthorized('salary-ranges');
        }

        return view('salary-ranges.edit', [
            'title' => "Edit Salary Range",
            'levels' => Level::all(),
            'salaryRange' => $salaryRange,
        ]);
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
        $validated = $request->validated();
        $salaryRange->update($validated);
        
        return redirectWithAlert('salary-ranges', 'success', 'Salary range updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalaryRange  $salaryRange
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, SalaryRange $salaryRange)
    {
        if ($request->user()->cannot('delete', $salaryRange)) {
            return redirectNotAuthorized('home');
        }

        $salaryRange->delete();
        
        return redirectWithAlert('salary-ranges', 'success', 'Salary range deleted successfully');
    }
}
