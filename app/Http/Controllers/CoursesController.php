<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCoursesRequest;
use App\Http\Requests\UpdateCoursesRequest;
use App\Mail\ResendCourseInvite;
use App\Models\Companies;
use App\Models\CourseAttempts;
use App\Models\Courses;
use App\Models\CourseSlideQuestions;
use App\Models\CourseSlides;
use App\Models\CourseToUsers;
use App\Models\IntakeGroups;
use App\Models\Participants;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $courses = Courses::select()
            ->where('course_archive','=','0')
            ->orderBy('course_title','ASC')
            ->get();

        return view('courses.index', compact('user','courses'));
    }

    public function indexarchived()
    {
        $user = Auth::user();
        $courses = Courses::select()
            ->where('course_archive','=','1')
            ->orderBy('course_title','ASC')
            ->get();

        return view('courses.indexarchive', compact('user','courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createcourse(Request $request)
    {
        $course = new Courses();
        $course->course_title = $request->input('course_title');
        $course->course_reference = $request->input('course_reference');
        $course->course_description = $request->input('course_description');
        $course->instructor = $request->input('instructor');
        $course->coursettpe = $request->input('coursettpe');
        $course->passrate = $request->input('passrate');
        $course->coursecertify = $request->input('coursecertify');
        $course->certification_period = $request->input('certification_period');
        $course->keypoint1 = $request->input('certificate1');
        $course->keypoint2 = $request->input('certificate2');
        $course->keypoint3 = $request->input('certificate3');
        $course->keypoint4 = $request->input('certificate4');
        $course->keypoint5 = $request->input('certificate5');
        if ($request->hasFile('document')) {
            $randomstring = (new CourseToUsersController)->generateRandomKey('10');
            $finalFile = $randomstring."_".$request->file('document')->getClientOriginalName();
            $filename = $request->file('document')->storeAs('public/',$finalFile);
            $course->document = $finalFile;
        }
        $course->save();

        //add preview user
        $randomkey = (new CourseToUsersController)->generateRandomKey(32);
        $preview = new CourseToUsers();
        $preview->user_id = '26';
        $preview->coursekey = $randomkey;
        $preview->course_id = $course->courseid;
        $preview->save();


        return redirect("/courses")->with('status', 'coursecreated');
    }

    public function preview($id){
        CourseAttempts::where('course_id', $id)->where('user_id','26')->delete();
        $cusers = CourseToUsers::where('course_id', $id)->where('user_id','26')->first();
        $cusers->completed = 0;
        $cusers->datecompleted = NULL;
        $cusers->coursecertificate = '';
        $cusers->save();
        return redirect("/c/".$cusers->coursekey);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCoursesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Courses $courses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $course = Courses::select()
            ->where('courseid','=',$id)
            ->first();
        return view('courses.edit', compact('user','course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $course = Courses::select()
            ->where('courseid','=',$id)
            ->first();
        $course->course_title = $request->input('course_title');
        $course->course_reference = $request->input('course_reference');
        $course->course_description = $request->input('course_description');
        $course->instructor = $request->input('instructor');
        $course->coursettpe = $request->input('coursettpe');
        $course->passrate = $request->input('passrate');
        $course->coursecertify = $request->input('coursecertify');
        $course->certification_period = $request->input('certification_period');
        $course->keypoint1 = $request->input('certificate1');
        $course->keypoint2 = $request->input('certificate2');
        $course->keypoint3 = $request->input('certificate3');
        $course->keypoint4 = $request->input('certificate4');
        $course->keypoint5 = $request->input('certificate5');
        if ($request->hasFile('document')) {
            $randomstring = (new CourseToUsersController)->generateRandomKey('10');
            $finalFile = $randomstring."_".$request->file('document')->getClientOriginalName();
            $filename = $request->file('document')->storeAs('public/',$finalFile);
            $course->document = $finalFile;
        }
        $course->save();
        return redirect("/courses")->with('status', 'courseupdated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Courses::where('courseid', $id)->delete();
        return redirect("/courses")->with('status', 'coursedeleted');
    }

    public function participantcourse($coursekey){
        $courseallocation = CourseToUsers::select()
            ->where('coursekey','=',$coursekey)
            ->first();
        if(isset($courseallocation)){
            $coursedetails = Courses::select()
                ->where('courseid','=',$courseallocation->course_id)
                ->first();
            if(isset($coursedetails)){
                $participantdetails = Participants::select()
                    ->where('participantid','=',$courseallocation->user_id)
                    ->first();
                if(isset($participantdetails)){
                    if(($courseallocation->completed=='1')||($courseallocation->completed=='2')||($courseallocation->completed=='3')){
                        return view('participantcourse.complete', compact('coursekey','coursedetails','participantdetails','courseallocation'));
                    }else{
                        //get progress
                        $progress = CourseAttempts::select()
                            ->where('course_id','=',$coursedetails->courseid)
                            ->where('user_id','=',$participantdetails->participantid)
                            ->where('coursekey','=',$coursekey)
                            ->orderBy('question_id','DESC')
                            ->first();
                        if(isset($progress)){ //participant has started - direct them to next question
                            $inprogress = '1';
                            $currentslide = $this->getcurrentslide($coursedetails->courseid,$progress->slidekey);
                            $nextsort = $currentslide->slide_sort+1;
                            $nextslide = $this->getnextslide($coursedetails->courseid,$nextsort);
                            $nextquestion = $nextslide->slide_key;
                        }else{ //direct to first question
                            $inprogress = '0';
                            $firstslide = $this->getfirstslide($coursedetails->courseid);
                            $nextquestion = $firstslide->slide_key;
                        }
                        return view('participantcourse.start', compact('coursekey','coursedetails','participantdetails','inprogress','nextquestion'));
                    }
                }else{ //participant not found
                    $reason = '11';
                    return view('participantcourse.courseloaderror', compact('coursekey','reason'));
                }
            }else{ //course details not douns
                $reason = '12';
                return view('participantcourse.courseloaderror', compact('coursekey','reason'));
            }
        }else{//allocation not found
            $reason = '13';
            return view('participantcourse.courseloaderror', compact('coursekey','reason'));
        }
    }

    public function participantcourseslide($coursekey, $slidekey){
        $courseallocation = CourseToUsers::select()
            ->where('coursekey','=',$coursekey)
            ->first();
        if(isset($courseallocation)){
            $course = Courses::select()
                ->where('courseid','=',$courseallocation->course_id)
                ->first();
            if(isset($course)){
                $participantdetails = Participants::select()
                    ->where('participantid','=',$courseallocation->user_id)
                    ->first();
                if(isset($participantdetails)){
                    if(($courseallocation->completed=='1')||($courseallocation->completed=='2')||($courseallocation->completed=='3')){
                        return view('participantcourse.complete', compact('coursekey','course','participantdetails'));
                    }else{
                        $hascompletedslide = CourseAttempts::select()
                            ->where('course_id','=',$courseallocation->course_id)
                            ->where('user_id','=',$courseallocation->user_id)
                            ->where('coursekey','=',$coursekey)
                            ->where('slidekey','=',$slidekey)
                            ->first();
                        if(isset($hascompletedslide)){
                            $courseslide = $this->getcurrentslide($courseallocation->course_id, $slidekey);
                            $nextsort = $courseslide->slide_sort+1;
                            $nextslide = $this->getnextslide($course->courseid,$nextsort);
                            if(isset($nextslide->slide_key)){
                                return redirect('/c/'.$coursekey."/".$nextslide->slide_key);
                            }else{
                                $lastslide = $this->getlastslide($courseallocation->course_id);
                                if($lastslide->slide_key==$courseslide->slide_key){

                                        // last slide so calculate pass rate
                                        if($course->coursettpe=='2'){
                                            $checkfortobemarked = CourseAttempts::select()
                                                ->where('course_id','=',$courseallocation->course_id)
                                                ->where('user_id','=',$courseallocation->user_id)
                                                ->where('coursekey','=',$coursekey)
                                                ->where('user_answer_marked','=','0')
                                                ->count();
                                            if($checkfortobemarked==0){ //no text answers to be marked
                                                //calculate passrate
                                                $participantscore = $this->participantcoursepassrate($courseallocation->course_id,$courseallocation->user_id,$coursekey);
                                                if($participantscore>=$course->passrate){ //scored and passed
                                                    // generate certificate
                                                    $filename = $this->participantgeneratecertificate($courseallocation->course_id,$coursekey);

                                                    $courseallocation->completed = '1'; //passed
                                                    $courseallocation->datecompleted = \Carbon\Carbon::now();
                                                    $courseallocation->coursecertificate = $filename . '.pdf';
                                                    $courseallocation->save();
                                                }else{
                                                    $courseallocation->completed = '2'; //failed
                                                    $courseallocation->datecompleted = \Carbon\Carbon::now();
                                                    $courseallocation->coursecertificate = '';
                                                    $courseallocation->save();
                                                }
                                            }else{
                                                $courseallocation->completed = '3'; //awaiting marking
                                                $courseallocation->datecompleted = \Carbon\Carbon::now();
                                                $courseallocation->coursecertificate = '';
                                                $courseallocation->save();
                                            }
                                        }else {
                                            // generate certificate
                                            $filename = $this->participantgeneratecertificate($courseallocation->course_id,$coursekey);

                                            $courseallocation->completed = '1';
                                            $courseallocation->datecompleted = \Carbon\Carbon::now();
                                            $courseallocation->coursecertificate = $filename . '.pdf';
                                            $courseallocation->save();
                                        }

                                    return redirect('/complete/'.$coursekey);
                                }
                            }
                        }else{//load the slide
                            $courseslide = $this->getcurrentslide($courseallocation->course_id, $slidekey);

                            $courseslide->questiontext = '';
                            $courseslide->answertext1 = '';
                            $courseslide->answertext2 = '';
                            $courseslide->answertext3 = '';
                            $courseslide->answertext4 = '';
                            $courseslide->answertext5 = '';
                            $courseslide->answertext6 = '';
                            $courseslide->correct_answer = array();
                            $courseslide->correct_answernumber = '';

                            $courseslidequestion = CourseSlideQuestions::select()
                                ->where('course_id','=',$courseallocation->course_id)
                                ->where('slideid','=',$courseslide->courseslideid)
                                ->first();
                            if(isset($courseslidequestion)){

                                $correctanswers = explode(';',$courseslidequestion->correct_answer);

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
                                $courseslide->correct_answer = $correctanswers;
                                $courseslide->correct_answernumber = sizeof($correctanswers);

                            }

                            $headers = [
                                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                                'Pragma' => 'no-cache',
                                'Expires' => '0',
                            ];


                            return response()->view('participantcourse.slide', compact('coursekey','course','participantdetails','courseslide'))
                                ->withHeaders($headers);
                        }
                    }
                }else{ //participant not found
                    $reason = '1';
                    return view('participantcourse.courseloaderror', compact('coursekey','reason'));
                }
            }else{ //course details not found
                $reason = '2';
                return view('participantcourse.courseloaderror', compact('coursekey','reason'));
            }
        }else{//allocation not found
            $reason = '3';
            return view('participantcourse.courseloaderror', compact('coursekey','reason'));
        }
    }

    public function participantcourseslideprocess(Request $request,$coursekey,$slidekey){
        $courseallocation = CourseToUsers::select()
            ->where('coursekey','=',$coursekey)
            ->first();
        if(isset($courseallocation)){
            $coursedetails = Courses::select()
                ->where('courseid','=',$courseallocation->course_id)
                ->first();
            if(isset($coursedetails)){
                $participantdetails = Participants::select()
                    ->where('participantid','=',$courseallocation->user_id)
                    ->first();
                if(isset($participantdetails)){
                    $slidedetails = CourseSlides::select()
                        ->where('course_id','=',$courseallocation->course_id)
                        ->where('slide_key','=',$slidekey)
                        ->first();
                    if(isset($slidedetails)){
                        $courseattempt = new CourseAttempts();
                        $courseattempt->course_id = $courseallocation->course_id;
                        $courseattempt->user_id = $courseallocation->user_id;
                        $courseattempt->coursekey = $coursekey;
                        if(($slidedetails->slide_questiontype=='5')&&(($slidedetails->slide_type=='3')||($slidedetails->slide_type=='5')||($slidedetails->slide_type=='6'))){//is text answer question marked
                            $courseattempt->question_id = $slidedetails->courseslideid;
                            $courseattempt->slidekey = $slidedetails->slide_key;
                            $courseattempt->answer_id = '0';
                            $courseattempt->user_answer = $request->input('answer');
                            $courseattempt->user_answer_marked = '0';
                        }
                        elseif(($slidedetails->slide_questiontype=='4')&&(($slidedetails->slide_type=='3')||($slidedetails->slide_type=='5')||($slidedetails->slide_type=='6'))){//is text answer question non-marked
                            $courseattempt->question_id = $slidedetails->courseslideid;
                            $courseattempt->slidekey = $slidedetails->slide_key;
                            $courseattempt->answer_id = '0';
                            $courseattempt->user_answer = $request->input('answer');
                            $courseattempt->user_answer_marked = '1';
                        }else{ //is slide
                            $courseattempt->question_id = $slidedetails->courseslideid;
                            $courseattempt->slidekey = $slidedetails->slide_key;
                            $courseattempt->answer_id = '0';
                        }
                        $courseattempt->save();
                        $nextsort = $slidedetails->slide_sort+1;
                        $nextslide = $this->getnextslide($coursedetails->courseid,$nextsort);
                        if(isset($nextslide->slide_key)){
                            return redirect('/c/'.$coursekey."/".$nextslide->slide_key);
                        }else{
                            $lastslide = $this->getlastslide($courseallocation->course_id);
                            if($lastslide->slide_key==$slidedetails->slide_key){
                                // last slide so calculate pass rate
                                if($coursedetails->coursettpe=='2'){
                                    $checkfortobemarked = CourseAttempts::select()
                                        ->where('course_id','=',$courseallocation->course_id)
                                        ->where('user_id','=',$courseallocation->user_id)
                                        ->where('coursekey','=',$coursekey)
                                        ->where('user_answer_marked','=','0')
                                        ->count();
                                    if($checkfortobemarked==0){ //no text answers to be marked
                                        //calculate passrate
                                        $participantscore = $this->participantcoursepassrate($courseallocation->course_id,$courseallocation->user_id,$coursekey);
                                        if($participantscore>=$coursedetails->passrate){ //scored and passed
                                            // generate certificate
                                            $filename = $this->participantgeneratecertificate($courseallocation->course_id,$coursekey);

                                            $courseallocation->completed = '1'; //passed
                                            $courseallocation->datecompleted = \Carbon\Carbon::now();
                                            $courseallocation->coursecertificate = $filename . '.pdf';
                                            $courseallocation->save();
                                        }else{
                                            $courseallocation->completed = '2'; //failed
                                            $courseallocation->datecompleted = \Carbon\Carbon::now();
                                            $courseallocation->coursecertificate = '';
                                            $courseallocation->save();
                                        }
                                    }else{
                                        $courseallocation->completed = '3'; //awaiting marking
                                        $courseallocation->datecompleted = \Carbon\Carbon::now();
                                        $courseallocation->coursecertificate = '';
                                        $courseallocation->save();
                                    }
                                }else {
                                    // generate certificate
                                    $filename = $this->participantgeneratecertificate($courseallocation->course_id,$coursekey);

                                    $courseallocation->completed = '1';
                                    $courseallocation->datecompleted = \Carbon\Carbon::now();
                                    $courseallocation->coursecertificate = $filename . '.pdf';
                                    $courseallocation->save();
                                }

                                return redirect('/complete/'.$coursekey);
                            }
                        }

                    }else{ // slide not linked to course
                        $reason = '4';
                        return view('participantcourse.courseloaderror', compact('coursekey','reason'));
                    }
                }else{ //participant not found
                    $reason = '5';
                    return view('participantcourse.courseloaderror', compact('coursekey','reason'));
                }
            }else{ //course details not found
                $reason = '6';
                return view('participantcourse.courseloaderror', compact('coursekey','reason'));
            }
        }else{//allocation not found
            $reason = '7';
            return view('participantcourse.courseloaderror', compact('coursekey','reason'));
        }
    }

    public function participantcoursecomplete($coursekey){
        $courseallocation = CourseToUsers::select()
            ->where('coursekey','=',$coursekey)
            ->first();
        if(isset($courseallocation)){
            $coursedetails = Courses::select()
                ->where('courseid','=',$courseallocation->course_id)
                ->first();
            if(isset($coursedetails)){
                $participantdetails = Participants::select()
                    ->where('participantid','=',$courseallocation->user_id)
                    ->first();
                if(isset($participantdetails)){
                        return view('participantcourse.complete', compact('coursekey','coursedetails','participantdetails','courseallocation'));
                }else{ //participant not found
                    $reason = '8';
                    return view('participantcourse.courseloaderror', compact('coursekey','reason','courseallocation'));
                }
            }else{ //course details not douns
                $reason = '9';
                return view('participantcourse.courseloaderror', compact('coursekey','reason','courseallocation'));
            }
        }else{//allocation not found
            $reason = '10';
            return view('participantcourse.courseloaderror', compact('coursekey','reason'));
        }
    }

    private function getcurrentslide($courseid, $slidekey){
        $currentslide = CourseSlides::select()
            ->where('course_id','=',$courseid)
            ->where('slide_key','=',$slidekey)
            ->first();
        return $currentslide;
    }

    private function getnextslide($courseid, $sortid){
        $nextslide = CourseSlides::select()
            ->where('course_id','=',$courseid)
            ->where('slide_sort','>=',$sortid)
            ->orderBy('slide_sort','ASC')
            ->first();
        return $nextslide;
    }

    private function getfirstslide($courseid){
        $firstslide = CourseSlides::select()
            ->where('course_id','=',$courseid)
            ->orderBy('slide_sort','ASC')
            ->first();
        return $firstslide;
    }

    private function getlastslide($courseid){
        $lastslide = CourseSlides::select()
            ->where('course_id','=',$courseid)
            ->orderBy('slide_sort','DESC')
            ->first();
        return $lastslide;
    }

    public function participantcoursemarkadmin(Request $request, $courseid, $slidekey){


        $slidedetails = CourseSlides::select()
            ->where('slide_key','=',$slidekey)
            ->first();
        $courseslidequestion = CourseSlideQuestions::select()
            ->where('course_id','=',$courseid)
            ->where('slideid','=',$slidedetails->courseslideid)
            ->first();

        $answercode1 = $request->input('answercode1');
        $answercode2 = $request->input('answercode2');
        $answercode3 = $request->input('answercode3');
        $answercode4 = $request->input('answercode4');
        $answercode5 = $request->input('answercode5');
        $answercode6 = $request->input('answercode6');
        $answerarray = array($answercode1,$answercode2,$answercode3,$answercode4,$answercode5,$answercode6);
        $answercodesplit = implode(";",$answerarray);


        if(($slidedetails->slide_questiontype=='1')||($slidedetails->slide_questiontype=='2')){ //non-test correct answer shown and not shown until correct

            $correctanswerarray = explode(";",$courseslidequestion->correct_answer);
            $correctresult = 1;
            $correctanswercount = 0;
            if($answercode1=='1'){
                if(!in_array('1',$correctanswerarray)){
                    $correctresult = 0;
                }else{
                    $correctanswercount++;
                }
            }
            if($answercode2=='1'){
                if(!in_array('2',$correctanswerarray)){
                    $correctresult = 0;
                }else{
                    $correctanswercount++;
                }
            }
            if($answercode3=='1'){
                if(!in_array('3',$correctanswerarray)){
                    $correctresult = 0;
                }else{
                    $correctanswercount++;
                }
            }
            if($answercode4=='1'){
                if(!in_array('4',$correctanswerarray)){
                    $correctresult = 0;
                }else{
                    $correctanswercount++;
                }
            }
            if($answercode5=='1'){
                if(!in_array('5',$correctanswerarray)){
                    $correctresult = 0;
                }else{
                    $correctanswercount++;
                }
            }
            if($answercode6=='1'){
                if(!in_array('6',$correctanswerarray)){
                    $correctresult = 0;
                }else{
                    $correctanswercount++;
                }
            }

            //if($answercodesplit[1]==$courseslidequestion->correct_answer){
            if($correctresult=='1'){
                $nextsort = $slidedetails->slide_sort+1;
                $nextslide = $this->getnextslide($courseid,$nextsort);
                $response = array(
                    'status' => 'success',
                    'message' => $nextslide->slide_key."!!".$courseslidequestion->correct_answer."!!".sizeof($correctanswerarray)."_".$correctanswercount
                );
                $returnvalue = json_encode($response);

            }else{
                $response = array(
                    'status' => 'error',
                    'message' => "2!!".$courseslidequestion->questionid."_".$courseslidequestion->correct_answer."!!".sizeof($correctanswerarray)."_".$correctanswercount
                );
                $returnvalue = json_encode($response);

            }
            //header('Content-Type: application/json');
            return $returnvalue;
        }
        elseif($slidedetails->slide_questiontype=='3'){ //multichoice - scored - answer not shown
            $nextsort = $slidedetails->slide_sort+1;
            $nextslide = $this->getnextslide($courseallocation->course_id,$nextsort);
            if((isset($nextslide->slide_key))&&($nextslide->slide_key!='')){
                $nextmessage = $nextslide->slide_key;
            }else{
                $nextmessage = '999999';
            }
            $response = array(
                'status' => 'success',
                'message' => $nextmessage
            );
            $returnvalue = json_encode($response);
            return $returnvalue;
        }
    }
    public function participantcourseslidemark(Request $request, $coursekey, $slidekey){
        $courseallocation = CourseToUsers::select()
            ->where('coursekey','=',$coursekey)
            ->first();

        $slidedetails = CourseSlides::select()
            ->where('slide_key','=',$slidekey)
            ->first();
        $courseslidequestion = CourseSlideQuestions::select()
            ->where('course_id','=',$courseallocation->course_id)
            ->where('slideid','=',$slidedetails->courseslideid)
            ->first();

        $answercode1 = $request->input('answercode1');
        $answercode2 = $request->input('answercode2');
        $answercode3 = $request->input('answercode3');
        $answercode4 = $request->input('answercode4');
        $answercode5 = $request->input('answercode5');
        $answercode6 = $request->input('answercode6');
        $answerarray = array($answercode1,$answercode2,$answercode3,$answercode4,$answercode5,$answercode6);
        $answercodesplit = implode(";",$answerarray);



        $courseattempt = new CourseAttempts();
        $courseattempt->course_id = $courseallocation->course_id;
        $courseattempt->user_id = $courseallocation->user_id;
        $courseattempt->coursekey = $coursekey;
        if((($slidedetails->slide_questiontype!='5')&&($slidedetails->slide_questiontype!='4'))&&(($slidedetails->slide_type=='3')||($slidedetails->slide_type=='5')||($slidedetails->slide_type=='6'))){ //is multi answer question
            $courseattempt->question_id = $slidedetails->courseslideid;
            $courseattempt->slidekey = $slidedetails->slide_key;
            $courseattempt->answer_id = $answercodesplit;
            $courseattempt->user_answer_marked = '1';
        }
        $courseattempt->save();


        if(($slidedetails->slide_questiontype=='1')||($slidedetails->slide_questiontype=='2')){ //non-test correct answer shown and not shown until correct

            $correctanswerarray = explode(";",$courseslidequestion->correct_answer);
            $correctresult = 1;
            $correctanswercount = 0;
            if($answercode1=='1'){
                if(!in_array('1',$correctanswerarray)){
                    $correctresult = 0;
                }else{
                    $correctanswercount++;
                }
            }
            if($answercode2=='1'){
                if(!in_array('2',$correctanswerarray)){
                    $correctresult = 0;
                }else{
                    $correctanswercount++;
                }
            }
            if($answercode3=='1'){
                if(!in_array('3',$correctanswerarray)){
                    $correctresult = 0;
                }else{
                    $correctanswercount++;
                }
            }
            if($answercode4=='1'){
                if(!in_array('4',$correctanswerarray)){
                    $correctresult = 0;
                }else{
                    $correctanswercount++;
                }
            }
            if($answercode5=='1'){
                if(!in_array('5',$correctanswerarray)){
                    $correctresult = 0;
                }else{
                    $correctanswercount++;
                }
            }
            if($answercode6=='1'){
                if(!in_array('6',$correctanswerarray)){
                    $correctresult = 0;
                }else{
                    $correctanswercount++;
                }
            }

            //if($answercodesplit[1]==$courseslidequestion->correct_answer){
            if($correctresult=='1'){
                $nextsort = $slidedetails->slide_sort+1;
                $nextslide = $this->getnextslide($courseallocation->course_id,$nextsort);
                $response = array(
                    'status' => 'success',
                    'message' => $nextslide->slide_key."!!".$courseslidequestion->correct_answer."!!".sizeof($correctanswerarray)."_".$correctanswercount
                );
                $returnvalue = json_encode($response);

            }else{
                $response = array(
                    'status' => 'error',
                    'message' => "2!!".$courseslidequestion->questionid."_".$courseslidequestion->correct_answer."!!".sizeof($correctanswerarray)."_".$correctanswercount
                );
                $returnvalue = json_encode($response);

            }
            //header('Content-Type: application/json');
            return $returnvalue;
        }
        elseif($slidedetails->slide_questiontype=='3'){ //multichoice - scored - answer not shown
            $nextsort = $slidedetails->slide_sort+1;
            $nextslide = $this->getnextslide($courseallocation->course_id,$nextsort);
            if((isset($nextslide->slide_key))&&($nextslide->slide_key!='')){
                $nextmessage = $nextslide->slide_key;
            }else{
                $nextmessage = '999999';
            }
            $response = array(
                'status' => 'success',
                'message' => $nextmessage
            );
            $returnvalue = json_encode($response);
            return $returnvalue;
        }
    }

    public function participantcoursepassrate($courseid, $userid, $coursekey){
        $coursedetails = Courses::select()
            ->where('courseid','=',$courseid)
            ->first();

        $courseslides = CourseSlides::select()
            ->where('course_id','=',$courseid)
            ->where(function ($courseslides) {
                $courseslides->where('slide_type','=','3')
                    ->orWhere('slide_type','=','4')
                    ->orWhere('slide_type','=','5');
            })
            ->where('slide_questiontype','=','3')
            ->get();
        $slidecount = 0;
        $correctanswercount = 0;
        foreach($courseslides as $cs){

            //$slidecount++;
            $questiondetails = CourseSlideQuestions::select()
                ->where('course_id','=',$courseid)
                ->where('slideid','=',$cs->courseslideid)
                ->first();
            $courseattempt = CourseAttempts::select()
                ->where('coursekey','=',$coursekey)
                ->where('course_id','=',$courseid)
                ->where('user_id','=',$userid)
                ->where('question_id','=',$questiondetails->slideid)
                ->first();

            $correctanswers = explode(";",$questiondetails->correct_answer);
            $slidecount = $slidecount+sizeof($correctanswers);

            $useranswers = explode(";",$courseattempt->answer_id);
            foreach($useranswers as $key => $ua){
                if($ua=='1') {
                    $answerkey = $key+1;
                    if (in_array($answerkey, $correctanswers)) {
                        $correctanswercount++;
                    }
                }
            }
          //  if($courseattempt->answer_id==$questiondetails->correct_answer){
          //      $correctanswercount++;
          //  }
        }

        $textcoursedlides = CourseSlides::select()
            ->where('course_id','=',$courseid)
            ->where(function ($courseslides) {
                $courseslides->where('slide_type','=','3')
                    ->orWhere('slide_type','=','4')
                    ->orWhere('slide_type','=','5');
            })
            ->where('slide_questiontype','=','5')
            ->get();
        foreach($textcoursedlides as $tcs){
            $slidecount = $slidecount+(int)$tcs->slide_points;

            $questiondetails = CourseSlideQuestions::select()
                ->where('course_id','=',$courseid)
                ->where('slideid','=',$tcs->courseslideid)
                ->first();

            $courseattempt = CourseAttempts::select()
                ->where('coursekey','=',$coursekey)
                ->where('course_id','=',$courseid)
                ->where('user_id','=',$userid)
                ->where('question_id','=',$questiondetails->slideid)
                ->first();
            if(isset($courseattempt)){
                $correctanswercount = $correctanswercount+$courseattempt->user_answer_marked_score;
            }

            }

        if($slidecount>0){
            $score = ($correctanswercount/$slidecount)*100;
        }else{
            $score = 100;
        }

        return $score;
    }

    public function participantgeneratecertificate($courseid, $coursekey){
        $coursedetails = Courses::select()
            ->where('courseid','=',$courseid)
            ->first();
        $courseallocation = CourseToUsers::select()
            ->where('coursekey','=',$coursekey)
            ->first();
        $participantdetails = Participants::select()
            ->where('participantid','=',$courseallocation->user_id)
            ->first();
        $data = array(
            "coursetitle" => $coursedetails['course_title'],
            "keypoint1" => $coursedetails['keypoint1'],
            "keypoint2" => $coursedetails['keypoint2'],
            "keypoint3" => $coursedetails['keypoint3'],
            "keypoint4" => $coursedetails['keypoint4'],
            "keypoint5" => $coursedetails['keypoint5'],
            "completedate" => \Carbon\Carbon::parse($courseallocation->datecompleted)->format('d-m-Y'),
            "staffname" => $participantdetails['firstname'] . " " . $participantdetails['lastname'],
            "instructor" => $coursedetails['instructor'],
            "uniqueid" => $coursedetails['course_reference']."/".$courseallocation['courseallocateid'],
            "certificationperiod" => $coursedetails['certification_period']
        );

        $pdf = PDF::loadView('participantcourse.pdfcertificate', $data);

        $filename = $coursedetails['course_title'] . "-" . $participantdetails['firstname'] . "_" . $participantdetails['lastname'] . "-" . date("dmY") . "-" . $coursekey;
        $pdf->save(storage_path('app/fileuploads/' . $filename . '.pdf'));
        return $filename;
    }

    public function quickinvite(){
        $user = Auth::user();
        $courses = Courses::select()
            ->orderBy('course_title','ASC')
            ->get();
        $companies = Companies::select()
            ->orderBy('companyname','ASC')
            ->get();
        $participants = Participants::select()
            ->orderBy('lastname','ASC')
            ->get();
        $intakegroups = IntakeGroups::select()
            ->where('grouparchive','=',0)
            ->orderBy('groupname')
            ->get();
        return view('courses.quickinvite', compact('courses','companies','participants','user','intakegroups'));
    }

    public function quickinvitesend(Request $request){
        $user = Auth::user();
        if($request->input('newcompany')!=''){
            $newcompany = new Companies();
            $newcompany->companyname = $request->input('newcompany');
            $newcompany->save();
            $companyid = $newcompany->companyid;
        }else{
            $companyid = $request->input('company');
        }

        $participant = new Participants();
        $participant->firstname = $request->input('firstname');
        $participant->lastname = $request->input('lastname');
        $participant->email = $request->input('email');
        $participant->phone = $request->input('phone');
        $participant->company = $companyid;
        $participant->intakegroup = $request->input('intakegroup');
        $participant->save();

        $randomkey = (new CourseToUsersController)->generateRandomKey(32);
        $courseallocation = new CourseToUsers();
        $courseallocation->course_id = $request->input('course');
        $courseallocation->user_id = $participant->participantid;
        if($request->has('sendemail')){
            $courseallocation->sent = \Carbon\Carbon::now();
            $coursedetails = Courses::select()
                ->where('courseid','=',$request->input('course'))
                ->first();
            $coursedata = array(
                "coursekey" => $randomkey,
                "coursetitle" => $coursedetails->course_title,
                "first_name" => $request->input('firstname'),
                "last_name" => $request->input('lastname')
            );
            Mail::to($request->input('email'))->send(new ResendCourseInvite($coursedata));
        }
        $courseallocation->sentby = $user->id;
        $courseallocation->coursekey = $randomkey;
        $courseallocation->save();
        if($request->has('coursepage')){
            return redirect("/courseparticipants/".$request->input('course'))->with('status', 'coursesent');
        }else{
            return redirect("/quickinvite")->with('status', 'coursesent');
        }

    }

    public function coursearchive($id){
        $user = Auth::user();
        $course = Courses::select()
            ->where('courseid','=',$id)
            ->first();
        $course->course_archive = 1;
        $course->save();
        return redirect("/courses")->with('status', 'coursearchived');
    }
    public function courseunarchive($id){
        $user = Auth::user();
        $course = Courses::select()
            ->where('courseid','=',$id)
            ->first();
        $course->course_archive = 0;
        $course->save();
        return redirect("/archivedcourses")->with('status', 'coursearchived');
    }

    public function courseduplicate($id){
        $existingCourse = Courses::find($id);
        $newCourse = $existingCourse->replicate();
        $newCourse->course_title = $existingCourse->course_title." (copy)";
        $newCourse->save();

        $existingCourseSlides = CourseSlides::select()
            ->where('course_id','=',$id)
            ->get();
        foreach($existingCourseSlides as $ecs){
            $newslide = $ecs->replicate();
            $newslide->course_id = $newCourse->courseid;
            $newslide->save();

            $slidequestion = CourseSlideQuestions::select()
                ->where('course_id','=',$id)
                ->where('slideid','=',$ecs->courseslideid)
                ->get();
            foreach($slidequestion as $sq){
                $newslidequestion = $sq->replicate();
                $newslidequestion->course_id = $newCourse->courseid;
                $newslidequestion->slideid = $newslide->courseslideid;
                $newslidequestion->save();
            }
        }
        return redirect("/courses")->with('status', 'courseduplicated');
    }
}
