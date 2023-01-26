<?php

namespace App\Http\Controllers;

use App\Models\TrainingMenu;
use App\Http\Requests\StoreTrainingMenuRequest;
use App\Http\Requests\UpdateTrainingMenuRequest;

class TrainingMenuController extends Controller
{
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
     * @param  \App\Http\Requests\StoreTrainingMenuRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTrainingMenuRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TrainingMenu  $trainingMenu
     * @return \Illuminate\Http\Response
     */
    public function show(TrainingMenu $trainingMenu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TrainingMenu  $trainingMenu
     * @return \Illuminate\Http\Response
     */
    public function edit(TrainingMenu $trainingMenu)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TrainingMenu  $trainingMenu
     * @return \Illuminate\Http\Response
     */
    public function destroy(TrainingMenu $trainingMenu)
    {
        //
    }
}
