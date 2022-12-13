<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use Illuminate\Http\Request;

class LevelController extends Controller
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
        if ($request->user()->cannot('view-levels')){
            return redirectNotAuthorized('home');
        }

        return view('levels.index', [
            'title' => 'Employee Level',
            'levels' => Level::search(request('search'))->paginate(15)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create-levels')){
            return redirectNotAuthorized('home');
        }

        return view('levels.create', [
            'title' => 'Create New Employee Level',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLevelRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLevelRequest $request)
    {
        $validated = $request->validated();
        $newLevel = Level::create($validated);

        if(!$newLevel){
            return back()->withInput()->with('danger', 'Failed to create new employee level');
        }
        
        return redirectWithAlert('levels', 'success', 'Employee Level Created Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Level $level)
    {
        if ($request->user()->cannot('view-levels')){
            return redirectNotAuthorized('home');
        }

        return view('levels.show', [
            'title' => 'Employee Level',
            'level' => $level,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Level $level)
    {
        if ($request->user()->cannot('update', $level)){
            return redirectNotAuthorized('home');
        }

        return view('levels.edit', [
            'title' => 'Edit Employee Level',
            'level' => $level,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLevelRequest  $request
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLevelRequest $request, Level $level)
    {
        if ($request->name != $level->name) {
            $validated = $request->validated();
            $level->update($validated);
            if (!$level->wasChanged()) {
                return back()->withInput()->with('danger', 'Failed to update level');
            }
        }
        
        return redirectWithAlert('levels', 'success', 'Employee Level Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Level  $level
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Level $level)
    {
        if ($request->user()->cannot('delete', $level)){
            return redirectNotAuthorized('home');
        }

        $level->delete();
        
        return redirectWithAlert('levels', 'success', 'Level deleted successfully');
    }
}
