<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Models\Department;

class PositionController extends Controller
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
        if ($request->user()->cannot('view-positions')) {
            return redirectNotAuthorized('home');
        }

        return view('positions.index', [
            'title' => 'Positions',
            'positions' => Position::filters(request(['search', 'department_id']))->orderBy('department_id')->paginate(15)->withQueryString(),
            'departments' => Department::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create-positions')) {
            return redirectNotAuthorized('positions');
        }

        return view('positions.create', [
            'title' => 'Create New Positions',
            'departments' => Department::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePositionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePositionRequest $request)
    {
        $validated = $request->validated();
        
        $newPosition = Position::create($validated);

        if (! $newPosition) {
            return back()->withInput()->with('danger', 'Failed to save new position');
        }
        return redirectWithAlert('positions', 'success', 'New position saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Position $position)
    {
        if ($request->user()->cannot('view-positions')) {
            return redirectNotAuthorized('home');
        }
        return view('positions.show', [
            'title' => "Position Details",
            'position' => $position,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Position $position)
    {
        if ($request->user()->cannot('update', $position)) {
            return redirectNotAuthorized('home');
        }

        return view('positions.edit', [
            'title' => 'Edit Position',
            'position' => $position,
            'departments' => Department::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePositionRequest  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePositionRequest $request, Position $position)
    {
        $validated = $request->validated();
        $position->update($validated);
        return redirectWithAlert('positions', 'success', 'Successfully updated the position');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Position $position)
    {
        if ($request->user()->cannot('delete', $position)) {
            return redirectNotAuthorized('home');
        }

        if(!$position->delete()){
            return redirectWithAlert('positions', 'danger', "Failed to delete position");
        }

        return redirectWithAlert('positions', 'success', 'Position deleted successfully');
    }
}
