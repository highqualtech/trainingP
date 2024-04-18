<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseSlideQuestionsRequest;
use App\Http\Requests\UpdateCourseSlideQuestionsRequest;
use App\Models\CourseSlideQuestions;

class CourseSlideQuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseSlideQuestionsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseSlideQuestions $courseSlideQuestions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseSlideQuestions $courseSlideQuestions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseSlideQuestionsRequest $request, CourseSlideQuestions $courseSlideQuestions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseSlideQuestions $courseSlideQuestions)
    {
        //
    }
}
