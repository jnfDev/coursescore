<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

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
        return Inertia::render('Sources/Create', [
            'channels' => Source::CHANNELS,
        ]);
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

        session()->flash('message', 'Source created successfully');

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
        return Inertia::render('Sources/Edit', [
            'source' => $source,
            'channels' => Source::CHANNELS 
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

        session()->flash('message', 'Source updated successfully');

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
        session()->flash('message', 'Source deleted successfully');

        return redirect(route('sources.index'));
    }

    /**
     * Search resource from storage.
     * 
     * @param string $search
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(string $search)
    {
        $foundSources = DB::table('sources')
                            ->where('name', 'LIKE', "%$search%")
                            ->orWhere('description', 'LIKE', "%$search%")
                            ->get();

        return response()->json($foundSources);
    }
}
