<?php

namespace App\Http\Controllers;

use App\Models\TrainingMenu;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTrainingMenuRequest;
use App\Http\Requests\UpdateTrainingMenuRequest;
use App\Models\Department;
use App\Models\TrainingSubject;

class TrainingMenuController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', TrainingMenu::class)) {
            return redirectNotAuthorized('home');
        }

        $departments = Department::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();
        
        $subjects = TrainingSubject::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['subject']] )->all();

        return view('training-menus.index', [
            'title' => 'Training Menu',
            'training_menus' => TrainingMenu::filters(request(['search', 'training_menu_id', 'department_id']))->orderBy('department_id')->paginate(15)->withQueryString(),
            'departments' => $departments,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', TrainingMenu::class)) {
            return redirectNotAuthorized('training-menus');
        }

        $departments = Department::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();
        
        $subjects = TrainingSubject::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['subject']] )->all();

        return view('training-menus.create', [
            'title' => 'Create New Training Menu',
            'departments' => $departments,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTrainingMenuRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTrainingMenuRequest $request)
    {
        $validated = $request->validated();

        if(TrainingMenu::create($validated)){
            return redirect()->route('training-menus.index')->with('success', 'Training Menu saved successfully');
        }
        return back()->withInput()->with('danger', 'Cannot Save Training Menu');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrainingMenu  $trainingMenu
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, TrainingMenu $trainingMenu)
    {
        if ($request->user()->cannot('view', $trainingMenu)) {
            return redirectNotAuthorized('home');
        }

        return view('training-menus.show', [
            'title' => "Training Menu Details",
            'training_menu' => $trainingMenu,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrainingMenu  $trainingMenu
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, TrainingMenu $trainingMenu)
    {
        if ($request->user()->cannot('update', $trainingMenu)) {
            return redirectNotAuthorized('home');
        }

        $departments = Department::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();
        
        $subjects = TrainingSubject::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['subject']] )->all();

        return view('training-menus.edit', [
            'title' => 'Edit Training Menu',
            'training_menu' => $trainingMenu,
            'departments' => $departments,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTrainingMenuRequest  $request
     * @param  \App\Models\TrainingMenu  $trainingMenu
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTrainingMenuRequest $request, TrainingMenu $trainingMenu)
    {
        $validated = $request->validated();
        if ($trainingMenu->update($validated)){
            return redirectWithAlert('training-menus', 'success', 'Successfully updated the Training Menu');
        }
        return back()->withInput()->with('danger', 'Cannot Update Training Menu');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainingMenu  $trainingMenu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TrainingMenu $trainingMenu)
    {
        if ($request->user()->cannot('delete', $trainingMenu)) {
            return redirectNotAuthorized('home');
        }

        if($trainingMenu->delete()){
            return redirectWithAlert('training-menus', 'success', 'Training Menu deleted successfully');
        }
        return redirectWithAlert('training-menus', 'danger', "Failed to delete training menu");
    }
}
