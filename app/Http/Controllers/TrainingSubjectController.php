<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingSubject;
use App\Http\Requests\StoreTrainingSubjectRequest;
use App\Http\Requests\UpdateTrainingSubjectRequest;

class TrainingSubjectController extends Controller
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
        if ($request->user()->cannot('viewAny', TrainingSubject::class)) {
            return redirect()->route('home')->with('warning', 'Not Authorized');
        }

        return view('training-subjects.index', [
            'title' => "Training Subjects",
            'training_subjects' => TrainingSubject::search(request('search'))->paginate(15)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', TrainingSubject::class)) {
            return redirectNotAuthorized('training-subjects');
        }

        return view('training-subjects.create', [
            'title' => "Create New Training Subject",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTrainingSubjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTrainingSubjectRequest $request)
    {
        $validated = $request->validated();

        if ($newTrainingSubject = TrainingSubject::create($validated)) {
            return redirectWithAlert('training-subjects', 'success', 'Training Subject saved successfully');
        }
        return back()->withInput()->with('danger', 'Failed to save Training Subject');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrainingSubject  $trainingSubject
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, TrainingSubject $trainingSubject)
    {
        if ($request->user()->cannot('view', $trainingSubject)) {
            return redirect()->route('training-subjects.index')->with('warning', 'Not Authorized');
        }

        return view('training-subjects.show', [
            'title' => "Training Subject Detail",
            'training_subject' => $trainingSubject,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrainingSubject  $trainingSubject
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, TrainingSubject $trainingSubject)
    {
        if ($request->user()->cannot('update', $trainingSubject)) {
            return redirectNotAuthorized('training-subjects');
        }

        return view('training-subjects.edit', [
            'title' => "Edit Training Subject",
            'training_subject' => $trainingSubject,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTrainingSubjectRequest  $request
     * @param  \App\Models\TrainingSubject  $trainingSubject
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTrainingSubjectRequest $request, TrainingSubject $trainingSubject)
    {
        if ($request->subject != $trainingSubject->subject) {
            $validated = $request->validated();
            $trainingSubject->update($validated);

            if (!$trainingSubject->wasChanged()) {
                return back()->withInput()->with('danger', 'Failed to update training subject');
            }
        }

        return redirectWithAlert('training-subjects', 'success', 'Training Subject Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainingSubject  $trainingSubject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, TrainingSubject $trainingSubject)
    {
        if ($request->user()->cannot('delete', $trainingSubject)) {
            return redirectNotAuthorized('training-subjects');
        }

        $trainingSubject->delete();

        return redirectWithAlert('training-subjects', 'success', 'Training Subject deleted successfully');
    }
}
