<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use App\Models\Source;
use App\Http\Requests\AdminSourceRequest;

class AdminSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Sources/Index', [
            'sources' => Source::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Source/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminSourceRequest $request)
    {
        $validated = $request->validated();
        Source::create($validated);

        return redirect(route('sources.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Source $source)
    {
        return Inertia::render('Course/Edit', [
            'source' => $source,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminSourceRequest $request, Source $source)
    {
        $validated = $request->validated();
        $source->update($validated);

        return redirect(route('sources.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Source $source)
    {
        $source->delete();
        return redirect(route('sources.index'));
    }
}
