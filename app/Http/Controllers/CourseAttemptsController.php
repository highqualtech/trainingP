<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseAttemptsRequest;
use App\Http\Requests\UpdateCourseAttemptsRequest;
use App\Models\CourseAttempts;
use App\Models\CourseSlides;

class CourseAttemptsController extends Controller
{
    public function calculateprogress($courseid, $coursekey){
        $courseslides = CourseSlides::select()
            ->where('course_id','=',$courseid)
            ->count();

        $completedslides = CourseAttempts::select()
            ->where('coursekey','=',$coursekey)
            ->count();
        if(($courseslides>0)&&($completedslides>0)) {
            $percentage = (int)(($courseslides / $completedslides) * 100);
        }else{
            $percentage = 0;
        }
        return $percentage;
    }
}
