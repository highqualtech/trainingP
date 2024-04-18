<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CourseToUsersController;

use App\Http\Requests\StoreCourseSlidesRequest;
use App\Http\Requests\UpdateCourseSlidesRequest;
use App\Models\Courses;
use App\Models\CourseSlideQuestions;
use App\Models\CourseSlides;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseSlidesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $user = Auth::user();
        $course = Courses::select()
            ->where('courseid','=',$id)
            ->first();
        $courseslides = CourseSlides::select()
            ->where('course_id','=',$id)
            ->orderBy('slide_sort','ASC')
            ->get();

        return view('courseslides.index', compact('user','course','courseslides'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createslide($id)
    {

        $slidesort = $this->getcoursehighestsort($id);
        if($slidesort==''){
            $slidesort = 1;
        }else{ $slidesort++;}

        $randomstring = (new CourseToUsersController)->generateRandomKey('32');

        $courseslide = new CourseSlides();
        $courseslide->course_id = $id;
        $courseslide->slide_sort = $slidesort;
        $courseslide->slidehtml = '';
        $courseslide->slide_type = '1';
        $courseslide->slide_key = $randomstring;
        $courseslide->save();
        return redirect("/courseslides/".$id."/".$courseslide->courseslideid);
    }

    private function getcoursehighestsort($id){
        $courseslide = CourseSlides::select()
            ->where('course_id','=',$id)
            ->orderBy('slide_sort','DESC')
            ->first();
        if(isset($courseslide->slide_sort)){
            return $courseslide->slide_sort;
        }else{
            return false;
        }

    }

    public function editslide($id, $slideid){
        $user = Auth::user();
        $course = Courses::select()
            ->where('courseid','=',$id)
            ->first();
        $courseslide = CourseSlides::select()
            ->where('course_id','=',$id)
            ->where('courseslideid','=',$slideid)
            ->first();
        $courseslide->questiontext = '';
        $courseslide->answertext1 = '';
        $courseslide->answertext2 = '';
        $courseslide->answertext3 = '';
        $courseslide->answertext4 = '';
        $courseslide->answertext5 = '';
        $courseslide->answertext6 = '';
        $courseslide->correct_answer = array();

        $courseslidequestion = CourseSlideQuestions::select()
            ->where('course_id','=',$id)
            ->where('slideid','=',$slideid)
            ->first();
        if(isset($courseslidequestion)){
            $courseslide->questiontext = $courseslidequestion->questiontext;
            $courseslide->answertext1 = $courseslidequestion->answertext1;
            $courseslide->answertext2 = $courseslidequestion->answertext2;
            $courseslide->answertext3 = $courseslidequestion->answertext3;
            $courseslide->answertext4 = $courseslidequestion->answertext4;
            $courseslide->answertext5 = $courseslidequestion->answertext5;
            $courseslide->answertext6 = $courseslidequestion->answertext6;
            $courseslide->correct_answer = explode(';',$courseslidequestion->correct_answer);
        }

        $url = Storage::url('slideimage/'.$courseslide->slide_image);

        $courseslide->image = $url;


        return view('courseslides.edit', compact('user','course','courseslide'));
    }

    public function editslidedev($id, $slideid){
        $user = Auth::user();
        $course = Courses::select()
            ->where('courseid','=',$id)
            ->first();
        $courseslide = CourseSlides::select()
            ->where('course_id','=',$id)
            ->where('courseslideid','=',$slideid)
            ->first();
        $courseslide->questiontext = '';
        $courseslide->answertext1 = '';
        $courseslide->answertext2 = '';
        $courseslide->answertext3 = '';
        $courseslide->answertext4 = '';
        $courseslide->correct_answer = '';

        $courseslidequestion = CourseSlideQuestions::select()
            ->where('course_id','=',$id)
            ->where('slideid','=',$slideid)
            ->first();
        if(isset($courseslidequestion)){
            $courseslide->questiontext = $courseslidequestion->questiontext;
            $courseslide->answertext1 = $courseslidequestion->answertext1;
            $courseslide->answertext2 = $courseslidequestion->answertext2;
            $courseslide->answertext3 = $courseslidequestion->answertext3;
            $courseslide->answertext4 = $courseslidequestion->answertext4;
            $courseslide->correct_answer = $courseslidequestion->correct_answer;
        }

        $url = Storage::url('slideimage/'.$courseslide->slide_image);

        $courseslide->image = $url;


        return view('courseslides.editdev2', compact('user','course','courseslide'));
    }

    public function previewslide($id, $slideid){
        $user = Auth::user();
        $course = Courses::select()
            ->where('courseid','=',$id)
            ->first();
        $courseslide = CourseSlides::select()
            ->where('course_id','=',$id)
            ->where('courseslideid','=',$slideid)
            ->first();
        $courseslide->questiontext = '';
        $courseslide->answertext1 = '';
        $courseslide->answertext2 = '';
        $courseslide->answertext3 = '';
        $courseslide->answertext4 = '';
        $courseslide->answertext5 = '';
        $courseslide->answertext6 = '';
        $courseslide->correct_answer = array();

        $courseslidequestion = CourseSlideQuestions::select()
            ->where('course_id','=',$id)
            ->where('slideid','=',$slideid)
            ->first();
        if(isset($courseslidequestion)){
            $courseslide->questiontext = $courseslidequestion->questiontext;
            $courseslide->answertext1 = $courseslidequestion->answertext1;
            $courseslide->answertext1id = $courseslidequestion->questionid."_1";
            $courseslide->answertext2 = $courseslidequestion->answertext2;
            $courseslide->answertext2id = $courseslidequestion->questionid."_2";
            $courseslide->answertext3 = $courseslidequestion->answertext3;
            $courseslide->answertext3id = $courseslidequestion->questionid."_3";
            $courseslide->answertext4 = $courseslidequestion->answertext4;
            $courseslide->answertext4id = $courseslidequestion->questionid."_4";
            $courseslide->answertext5 = $courseslidequestion->answertext5;
            $courseslide->answertext5id = $courseslidequestion->questionid."_5";
            $courseslide->answertext6 = $courseslidequestion->answertext6;
            $courseslide->answertext6id = $courseslidequestion->questionid."_6";
            $correctanswers = explode(';',$courseslidequestion->correct_answer);
            $courseslide->correct_answer = $correctanswers;
            $courseslide->correct_answernumber = sizeof($correctanswers);
        }

        $url = Storage::url('slideimage/'.$courseslide->slide_image);

        $courseslide->image = $url;


        return view('courseslides.preview', compact('user','course','courseslide'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseSlidesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseSlides $courseSlides)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CourseSlides $courseSlides)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, $slideid)
    {
        $courseslide = CourseSlides::select()
            ->where('courseslideid','=',$slideid)
            ->where('course_id','=',$id)
            ->first();
        $courseslide->slide_title = $request->input('slide_title');
        $courseslide->slide_type = $request->input('slide_type');
        if($request->input('slide_type')=='4'){
            $courseslide->slidehtml = $request->input('slidehtml2');
            $courseslide->confirmation_text = $request->input('confirmationtext');
        }else{
            $courseslide->slidehtml = $request->input('slidehtml');
        }

        $courseslide->slide_points = $request->input('slide_points');
        if ($request->hasFile('audiodescription')) {
            $randomstring = (new CourseToUsersController)->generateRandomKey('10');
            $finalFile = $randomstring."_".$request->file('audiodescription')->getClientOriginalName();
            $filename = $request->file('audiodescription')->storeAs('userfiles/audio/',$finalFile);
            $courseslide->audiofile = $finalFile;
        }

        $courseslide->slide_questiontype = $request->input('slidequestiontype');
        if(($request->input('slide_type')=='2')||($request->input('slide_type')=='6')){
            if ($request->hasFile('slideimage')) {
                $randomstring = (new CourseToUsersController)->generateRandomKey('10');
                $finalFile = $randomstring."_".$request->file('slideimage')->getClientOriginalName();
                $filename = $request->file('slideimage')->storeAs('userfiles/image/',$finalFile);
                $courseslide->slide_image = $finalFile;
            }
        }
        $courseslide->save();

        if(($request->input('slide_type')=='3')||($request->input('slide_type')=='5')||($request->input('slide_type')=='6')){
            $courseslidequestion = CourseSlideQuestions::select()
                ->where('course_id','=',$id)
                ->where('slideid','=',$slideid)
                ->first();
            if(isset($courseslidequestion)){
                $correctanswers = array();
                foreach($request->input('correctanswer') as $key => $correctanswervalue){
                    $correctanswers[] = $correctanswervalue;
                }
                $correctanswers = implode(';',$correctanswers);
                $courseslidequestion->course_id =$id;
                $courseslidequestion->slideid =$slideid;
                $courseslidequestion->questiontext = $request->input('question');
                $courseslidequestion->answertext1 = $request->input('answer1');
                $courseslidequestion->answertext2 = $request->input('answer2');
                $courseslidequestion->answertext3 = $request->input('answer3');
                $courseslidequestion->answertext4 = $request->input('answer4');
                $courseslidequestion->answertext5 = $request->input('answer5');
                $courseslidequestion->answertext6 = $request->input('answer6');
                $courseslidequestion->correct_answer = $correctanswers;
                $courseslidequestion->save();
            }else{
                $correctanswers = array();
                if($request->has('correctanswer')) {
                    foreach ($request->input('correctanswer') as $key => $correctanswervalue) {
                        $correctanswers[] = $correctanswervalue;
                    }
                }
                $correctanswers = implode(';',$correctanswers);
                $courseslidequestion = new CourseSlideQuestions();
                $courseslidequestion->course_id =$id;
                $courseslidequestion->slideid =$slideid;
                $courseslidequestion->questiontext = $request->input('question');
                $courseslidequestion->answertext1 = $request->input('answer1');
                $courseslidequestion->answertext2 = $request->input('answer2');
                $courseslidequestion->answertext3 = $request->input('answer3');
                $courseslidequestion->answertext4 = $request->input('answer4');
                $courseslidequestion->answertext5 = $request->input('answer5');
                $courseslidequestion->answertext6 = $request->input('answer6');
                $courseslidequestion->correct_answer = $correctanswers;
                $courseslidequestion->save();
            }
        }

        if($request->has('submitsave')){
           // $slidesort = $this->getcoursehighestsort($id);
           // if($slidesort==''){
          //      $slidesort = 1;
           // }else{ $slidesort++;}

            $currentsort = $courseslide->slide_sort;

            $remainingslides = CourseSlides::select()
                ->where('course_id','=',$id)
                ->where('slide_sort','>',$currentsort)
                ->get();
            foreach($remainingslides as $rs){
                $newsort = (int)$rs->slide_sort+1;
                CourseSlides::select()->where('courseslideid','=',$rs->courseslideid)->update(['slide_sort' => $newsort]);
            }

            $randomstring = (new CourseToUsersController)->generateRandomKey('32');

            $courseslide = new CourseSlides();
            $courseslide->course_id = $id;
            $courseslide->slide_sort = (int)$currentsort+1;;
            $courseslide->slidehtml = '';
            $courseslide->slide_type = '1';
            $courseslide->slide_key = $randomstring;
            $courseslide->save();
            return redirect("/courseslides/".$id."/".$courseslide->courseslideid);
        }
        elseif($request->has('submitremain')){
            return redirect("/courseslides/".$id."/".$courseslide->courseslideid)->with('status', 'courseslideupdated');
        }
        else{
            return redirect("/courseslides/".$id);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, $slideid)
    {
        CourseSlides::where('courseslideid', $slideid)->delete();
        return redirect("/courseslides/".$id)->with('status', 'courseslidedeleted');
    }

    public function courseslidesort(Request $request,$courseid){
        $printarray = print_r($request->input('post_order_ids'),true);
        foreach($request->input('post_order_ids') as $key => $slidesort){
            $courselide = CourseSlides::select()
                ->where('course_id','=',$courseid)
                ->where('courseslideid','=',$slidesort)
                ->first();
            $courselide->slide_sort = $key+1;
            $courselide->save();
        }
    }
}
