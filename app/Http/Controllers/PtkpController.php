<?php

namespace App\Http\Controllers;

use App\Models\Ptkp;
use App\Enums\TaxStatus;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StorePtkpRequest;
use App\Http\Requests\UpdatePtkpRequest;

class PtkpController extends Controller
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
        if ($request->user()->cannot('viewAny', Ptkp::class)) {
            return redirectNotAuthorized('home');
        }

        return view('ptkps.index', [
            'title' => "Tarif PTKP",
            'ptkps' => Ptkp::search(request('search'))->paginate(15)->withQueryString(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->user()->cannot('create', Ptkp::class)) {
            return redirectNotAuthorized('ptkps');
        }

        $taxStatus = TaxStatus::cases();

        $statusPajak = [];
        foreach ($taxStatus as $item) {
        $statusPajak[$item->value] = Str::headline($item->name);
        }

        return view('ptkps.create', [
            'title' => "Create New PTKP Fee",
            'statusPajak' => $statusPajak,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePtkpRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePtkpRequest $request)
    {
        $validated = $request->validated();

        if ($newPtkp = Ptkp::create($validated)){
            return redirectWithAlert('ptkps', 'success', 'New PTKP Fee Created');
        }
        return back()->withInput()->with('danger', 'Failed to create PTKP Fee');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ptkp  $ptkp
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Ptkp $ptkp)
    {
        if ($request->user()->cannot('view', $ptkp)) {
            return redirectNotAuthorized('home');
        }

        return view('ptkps.show', [
            'title' => "PTKP Fee Detail",
            'ptkp' => $ptkp,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ptkp  $ptkp
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Ptkp $ptkp)
    {
        if ($request->user()->cannot('update', $ptkp)) {
            return redirectNotAuthorized('ptkps');
        }

        $taxStatus = TaxStatus::cases();

        $statusPajak = [];
        foreach ($taxStatus as $item) {
        $statusPajak[$item->value] = Str::headline($item->name);
        }

        return view('ptkps.edit', [
            'title' => "Edit PTKP Fee",
            'ptkp' => $ptkp,
            'statusPajak' => $statusPajak,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePtkpRequest  $request
     * @param  \App\Models\Ptkp  $ptkp
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePtkpRequest $request, Ptkp $ptkp)
    {
        $validated = $request->safe()->except(['tax_status']);
        if ($request->tax_status != $ptkp->tax_status) {
            $validated['tax_status'] = $ptkp->tax_status;
        }
        if ($ptkp->update($validated)){
            return redirectWithAlert('ptkps', 'success', 'PTKP Fee Updated Successfully');
        }
        return back()->withInput()->with('danger', 'Failed to update PTKP Fee');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ptkp  $ptkp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Ptkp $ptkp)
    {
        if ($request->user()->cannot('delete', $ptkp)) {
            return redirectNotAuthorized('ptkps');
        }

        $ptkp->delete();

        return redirectWithAlert('ptkps', 'success', 'PTKP Fee deleted successfully');
    }
}
