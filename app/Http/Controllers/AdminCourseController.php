<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Course;
use App\Http\Requests\AdminCourseRequest;
use Illuminate\Support\Facades\DB;

class AdminCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Courses/Index', [
            'courses' => Course::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $randomSourcesQuery = DB::table('sources')->inRandomOrder()->limit(4);

        return Inertia::render('Courses/Create', [
            'defaultSources' => $randomSourcesQuery->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminCourseRequest $request)
    {
        $validated = $request->validated();
        Course::create($validated);

        session()->flash('message', 'Course created successfully');

        return redirect(route('courses.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        $courseSourceQuery = DB::table('sources')->where('id', $course->source_id);
        $randomSourcesQuery = DB::table('sources')->inRandomOrder()->whereNot('id', $course->source_id)->limit(4);

        return Inertia::render('Courses/Edit', [
            'course'         => $course,
            'defaultSources' => $randomSourcesQuery->union($courseSourceQuery)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminCourseRequest $request, Course $course)
    {
        $validated = $request->validated();
        $course->update($validated);

        session()->flash('message', 'Course updated successfully');

        return redirect(route('courses.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        session()->flash('message', 'Source deleted successfully');
        
        return redirect(route('courses.index'));
    }
}
