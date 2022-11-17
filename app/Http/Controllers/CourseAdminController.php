<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

use App\Models\Course;

class CourseAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Courses/Index', [
            'courses' => [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Courses/Form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // TODO: Move to a Form Request class
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string|max:65535|nullable',
            'url' => 'string|url|nullable',
            'score' => 'not_in',
            'user_id' => 'required|integer',
            'source_id' => 'required|integer'
        ]);

        Course::create($validated);

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
        return Inertia::render('Courses/Form', [
            'course' => $course,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        // TODO: Move to a Form Request class
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string|max:65535|nullable',
            'url' => 'string|url|nullable',
            'score' => 'not_in',
        ]);

        $course->update($validated);

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
        return redirect(route('courses.index'));
    }
}
