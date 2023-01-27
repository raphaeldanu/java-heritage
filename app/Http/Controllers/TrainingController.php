<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\Department;
use App\Models\TrainingMenu;
use Illuminate\Http\Request;
use App\Models\TrainingSubject;
use App\Http\Requests\StoreTrainingRequest;
use App\Http\Requests\UpdateTrainingRequest;
use App\Models\Employee;

class TrainingController extends Controller
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
        if ($request->user()->cannot('viewAny', Training::class)) {
            return redirectNotAuthorized('home');
        }

        $dateConfig = [
            'format' => 'YYYY-MM-DD'
        ];

        $departments = Department::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['name']] )->all();

        $menus = TrainingMenu::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['title']] )->all();
        
        $subjects = TrainingSubject::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['subject']] )->all();

        return view('trainings.index', [
            'title' => 'Training',
            'trainings' => Training::filters(request(['training_date', 'training_menu_id', 'training_subject_id', 'department_id']))->orderBy('id', 'desc')->paginate(15)->withQueryString(),
            'menus' => $menus,
            'subjects' => $subjects,
            'departments' => $departments,
            'date_config' => $dateConfig,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Training::class)) {
            return redirectNotAuthorized('trainings');
        }

        $menus = TrainingMenu::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['title'].' - '.$item['trainingSubject']['subject']] )->all();

        $dateConfig = [
            'format' => 'YYYY-MM-DD'
          ];

        return view('trainings.create', [
            'title' => 'Create New Training',
            'dateConfig' => $dateConfig,
            'menus' => $menus,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTrainingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTrainingRequest $request)
    {
        $validated = $request->validated();

        if (Training::create($validated)) {
            return redirect()->route('trainings.index')->with('success', 'Training Saved Successfully');
        }
        return back()->withInput()->with('danger', 'Failed to save training');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Training $training)
    {
        if ($request->user()->cannot('view', $training)) {
            return redirectNotAuthorized('trainings');
        }

        return view('trainings.show', [
            'title' => 'Training Details',
            'training' => $training,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Training $training)
    {
        if ($request->user()->cannot('update', $training)) {
            return redirectNotAuthorized('trainings');
        }

        $menus = TrainingMenu::all()->mapWithKeys( fn($item, $key) => [$item['id'] => $item['title'].' - '.$item['trainingSubject']['subject']] )->all();

        $dateConfig = [
            'format' => 'YYYY-MM-DD'
          ];

        return view('trainings.edit', [
            'title' => 'Edit Training',
            'training' => $training,
            'dateConfig' => $dateConfig,
            'menus' => $menus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTrainingRequest  $request
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTrainingRequest $request, Training $training)
    {
        $validated = $request->validated();
        if($training->update($validated)){
            return redirect()->route('trainings.index')->with('success', "Training Updated Successfully");
        }
        return back()->withInput()->with('danger', "Failed to Update Training");
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Training $training)
    {
        if ($request->user()->cannot('delete', $training)) {
            return redirectNotAuthorized('trainings');
        }

        $training->employees()->detach();
        if ($training->delete()) {
            return redirect()->route('trainings.index')->with('success', "Training Delete Successfully");
        }
        return back()->withInput()->with('danger', "Failed to Delete Training");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function addAttendant(Request $request, Training $training)
    {
        if ($request->user()->cannot('update', $training)) {
            return redirectNotAuthorized('trainings');
        }

        if($request->routeIs('trainings.add-attendants')){
            $breadcrumb = 'show';
        } elseif($request->routeIs('trainings.edit-attendants')){
            $breadcrumb = 'edit';
        } else {
            return back();
        }

        return view('trainings.pick-attendants', [
            'title' => 'Add Training Attendants',
            'training' => $training,
            'attendants' => $training->employees,
            'employees' => Employee::whereNotIn('id', $training->employees->pluck('id'))->filters(request(['search']))->paginate(15)->withQueryString(),
            'breadcrumb' => $breadcrumb,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function storeAttendant(Request $request, Training $training, Employee $employee)
    {
        if ($request->user()->cannot('update', $training)) {
            return redirectNotAuthorized('trainings');
        }

        $training->employees()->attach($employee->id);
        $training->attendants = $training->attendants + 1;
        $training->save();
        $training->fresh();
        
        if($request->routeIs('trainings.store-attendants')){
            return redirect()->route('trainings.add-attendants', ['training' => $training])->with('success', 'Employee Added to Attendants');
        } elseif($request->routeIs('trainings.store-edit-attendants')){
            return redirect()->route('trainings.edit-attendants', ['training' => $training])->with('success', 'Employee Added to Attendants');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Training  $training
     * @return \Illuminate\Http\Response
     */
    public function removeAttendant(Request $request, Training $training, Employee $employee)
    {
        if ($request->user()->cannot('update', $training)) {
            return redirectNotAuthorized('trainings');
        }

        $training->employees()->detach($employee->id);
        $training->attendants = $training->attendants - 1;
        $training->save();
        $training->fresh();

        if($request->routeIs('trainings.remove-attendants')){
            return redirect()->route('trainings.add-attendants', ['training' => $training])->with('success', 'Employee Removed from Attendants');
        } elseif($request->routeIs('trainings.remove-edit-attendants')){
            return redirect()->route('trainings.edit-attendants', ['training' => $training])->with('success', 'Employee Removed from Attendants');
        }
    }
}
