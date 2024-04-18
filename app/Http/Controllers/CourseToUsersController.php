<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseToUsersRequest;
use App\Http\Requests\UpdateCourseToUsersRequest;
use App\Mail\ResendCourseInvite;
use App\Models\Companies;
use App\Models\CourseAttempts;
use App\Models\Courses;
use App\Models\CourseSlideQuestions;
use App\Models\CourseSlides;
use App\Models\CourseToUsers;
use App\Models\IntakeGroups;
use App\Models\Participants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CourseToUsersController extends Controller
{
    public function index($id, $filter=''){
        $user = Auth::user();
        $coursedetails = Courses::select()
            ->where('courseid','=',$id)
            ->first();
        if($filter=='') {
            $pendingusers = CourseToUsers::select()
                ->where('course_id', '=', $id)
                ->whereNotNull('sent')
                ->where('completed', '=', '0')
                ->where('participants.archive', '=', 0)
                ->leftJoin('participants', 'participants.participantid', '=', 'course_to_users.user_id')
                ->get();
        }else{
            $pendingusers = CourseToUsers::select()
                ->where('course_id', '=', $id)
                ->whereNotNull('sent')
                ->where('completed', '=', '0')
                ->where('participants.archive', '=', 0)
                ->where('participants.intakegroup', '=', $filter)
                ->leftJoin('participants', 'participants.participantid', '=', 'course_to_users.user_id')
                ->get();
        }
        $pendingusersarray = array();
        foreach($pendingusers as $pu){
            $percent = (new CourseAttemptsController)->calculateprogress($pu->course_id,$pu->coursekey);
            $company = '';
            if(($pu->company!='')&&($pu->company!='0')){
                $companydetails = Companies::select()
                    ->where('companyid','=',$pu->company)
                    ->first();
                $company = $companydetails->companyname;
            }
            $pendingusersarray[] = array('participantname'=>$pu->firstname." ".$pu->lastname,'datesent'=>$pu->sent,'coursekey'=>$pu->coursekey,'progress'=>$percent,'courseallocateid'=>$pu->courseallocateid,'company'=>$company,'participantid'=>$pu->participantid);
        }
        if($filter=='') {
            $notsentusers = CourseToUsers::select()
                ->where('course_id', '=', $id)
                ->where('participants.archive', '=', 0)
                ->whereNull('sent')
                ->leftJoin('participants', 'participants.participantid', '=', 'course_to_users.user_id')
                ->get();
        }else{
            $notsentusers = CourseToUsers::select()
                ->where('course_id', '=', $id)
                ->where('participants.archive', '=', 0)
                ->whereNull('sent')
                ->where('participants.intakegroup', '=', $filter)
                ->leftJoin('participants', 'participants.participantid', '=', 'course_to_users.user_id')
                ->get();
        }
        $notsentusersarray = array();
        foreach($notsentusers as $ns){
            $company = '';
            if(($ns->company!='')&&($ns->company!='0')){
                $companydetails = Companies::select()
                    ->where('companyid','=',$ns->company)
                    ->first();
                $company = $companydetails->companyname;
            }
            $notsentusersarray[] = array('participantname'=>$ns->firstname." ".$ns->lastname,'coursekey'=>$ns->coursekey,'courseallocateid'=>$ns->courseallocateid,'company'=>$company,'participantid'=>$ns->participantid);
        }
        if($filter=='') {
            $completepassusers = CourseToUsers::select()
                ->where('course_id', '=', $id)
                ->where('completed', '=', '1')
                ->where('participants.archive', '=', 0)
                ->leftJoin('participants', 'participants.participantid', '=', 'course_to_users.user_id')
                ->get();
        }else{
            $completepassusers = CourseToUsers::select()
                ->where('course_id', '=', $id)
                ->where('completed', '=', '1')
                ->where('participants.archive', '=', 0)
                ->where('participants.intakegroup', '=', $filter)
                ->leftJoin('participants', 'participants.participantid', '=', 'course_to_users.user_id')
                ->get();
        }
        $completepassusersarray = array();
        foreach($completepassusers as $cu){
            $percent = (new CoursesController)->participantcoursepassrate($cu->course_id,$cu->participantid,$cu->coursekey);
            $company = '';
            if(($cu->company!='')&&($cu->company!='0')){
                $companydetails = Companies::select()
                    ->where('companyid','=',$cu->company)
                    ->first();
                $company = $companydetails->companyname;
            }
            $completepassusersarray[] = array('participantname'=>$cu->firstname." ".$cu->lastname,'datecomplete'=>$cu->datecompleted,'coursekey'=>$cu->coursekey,'score'=>$percent,'courseallocateid'=>$cu->courseallocateid,'company'=>$company,'participantid'=>$cu->participantid,'coursecertificate'=>$cu->coursecertificate);
        }
        if($filter=='') {
            $completefailusers = CourseToUsers::select()
                ->where('course_id', '=', $id)
                ->where('completed', '=', '2')
                ->where('participants.archive', '=', 0)
                ->leftJoin('participants', 'participants.participantid', '=', 'course_to_users.user_id')
                ->get();
        }else{
            $completefailusers = CourseToUsers::select()
                ->where('course_id', '=', $id)
                ->where('completed', '=', '2')
                ->where('participants.archive', '=', 0)
                ->where('participants.intakegroup', '=', $filter)
                ->leftJoin('participants', 'participants.participantid', '=', 'course_to_users.user_id')
                ->get();
        }
        $completefailusersarray = array();
        foreach($completefailusers as $cfu){
            $percent = (new CoursesController)->participantcoursepassrate($cfu->course_id,$cfu->participantid,$cfu->coursekey);
            $company = '';
            if(($cfu->company!='')&&($cfu->company!='0')){
                $companydetails = Companies::select()
                    ->where('companyid','=',$cfu->company)
                    ->first();
                $company = $companydetails->companyname;
            }
            $completefailusersarray[] = array('participantname'=>$cfu->firstname." ".$cfu->lastname,'datecomplete'=>$cfu->datecompleted,'coursekey'=>$cfu->coursekey,'score'=>$percent,'courseallocateid'=>$cfu->courseallocateid,'company'=>$company,'participantid'=>$cfu->participantid);
        }
        if($filter=='') {
            $completependingusers = CourseToUsers::select()
                ->where('course_id', '=', $id)
                ->where('completed', '=', '3')
                ->where('participants.archive', '=', 0)
                ->leftJoin('participants', 'participants.participantid', '=', 'course_to_users.user_id')
                ->get();
        }else{
            $completependingusers = CourseToUsers::select()
                ->where('course_id', '=', $id)
                ->where('completed', '=', '3')
                ->where('participants.archive', '=', 0)
                ->where('participants.intakegroup', '=', $filter)
                ->leftJoin('participants', 'participants.participantid', '=', 'course_to_users.user_id')
                ->get();
        }
        $completependingusersarray = array();
        foreach($completependingusers as $cpu){
            $percent = (new CoursesController)->participantcoursepassrate($cpu->course_id,$cpu->participantid,$cpu->coursekey);
            $company = '';
            if(($cpu->company!='')&&($cpu->company!='0')){
                $companydetails = Companies::select()
                    ->where('companyid','=',$cpu->company)
                    ->first();
                $company = $companydetails->companyname;
            }
            $completependingusersarray[] = array('participantname'=>$cpu->firstname." ".$cpu->lastname,'datecomplete'=>$cpu->datecompleted,'coursekey'=>$cpu->coursekey,'score'=>$percent,'courseallocateid'=>$cpu->courseallocateid,'company'=>$company,'participantid'=>$cpu->participantid);
        }

        $companies = Companies::select()
            ->orderBy('companyname','ASC')
            ->get();

        $participantarray = array();
        $participants = Participants::select()
            ->where('participants.archive','=',0)
            ->orderBy('lastname','ASC')
            ->get();
        foreach($participants as $p){
            $company = '';
            if(($p->company!='')&&($p->company!='0')){
                $companydetails = Companies::select()
                    ->where('companyid','=',$p->company)
                    ->first();
                $company = $companydetails->companyname;
            }
            $participantarray[] = array("participantid"=>$p->participantid,"participantname"=>$p->lastname.", ".$p->firstname,"company"=>$company,"participantemail"=>$p->email);
        }

        $intakegroups = IntakeGroups::select()
            ->where('grouparchive','=','0')
            ->orderBy('groupname','ASC')
            ->get();


        return view('courses.participants', compact('user','coursedetails','pendingusersarray','notsentusersarray','completepassusersarray','completefailusersarray','completependingusersarray','companies','participantarray','intakegroups'));
    }

    public function redirect($id, Request $request){
        if($request->input('intakegroup')=='0'){
            return redirect("/courseparticipants/".$id);
        }else{
            return redirect("/courseparticipantsfilter/".$id."/".$request->input('intakegroup'));
        }

    }

    public function participantallocate(Request $request){
        $user = Auth::user();
        $randomkey = $this->generateRandomKey(32);
        $courseallocation = new CourseToUsers();
        $courseallocation->course_id = $request->input('courseallocate');
        $courseallocation->user_id = $request->input('participantid');
        if($request->has('sendemail')){
            $courseallocation->sent = \Carbon\Carbon::now();
            $coursedetails = Courses::select()
                ->where('courseid','=',$request->input('courseallocate'))
                ->first();
            $participant = Participants::select()
                ->where('participantid','=',$request->input('participantid'))
                ->first();
            $coursedata = array(
                "coursekey" => $randomkey,
                "coursetitle" => $coursedetails->course_title,
                "first_name" => $participant->firstname,
                "last_name" => $participant->lastname
            );
            Mail::to($participant->email)->send(new ResendCourseInvite($coursedata));
        }
        $courseallocation->sentby = $user->id;
        $courseallocation->coursekey = $randomkey;
        $courseallocation->save();
        if($request->has('courseinvite')){
            return redirect("/courseparticipants/".$request->input('courseallocate'))->with('status', 'coursesent');
        }else{
            return redirect("/participant/".$request->input('participantid'))->with('status', 'coursesent');
        }

    }

    public function generateRandomKey($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $key = '';

        for ($i = 0; $i < $length; $i++) {
            $key .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $key;
    }

    public function participantallocateresend($id,$participantid){
        $user = Auth::user();
        $courseallocation = CourseToUsers::select()
            ->where('courseallocateid','=',$id)
            ->where('user_id','=',$participantid)
            ->first();
        $participantdetails = Participants::select()
            ->where('participantid','=',$participantid)
            ->first();
        $coursedetails = Courses::select()
            ->where('courseid','=',$courseallocation->course_id)
            ->first();
        $coursedata = array(
            "coursekey" => $courseallocation->coursekey,
            "coursetitle" => $coursedetails->course_title,
            "first_name" => $participantdetails->firstname,
            "last_name" => $participantdetails->lastname
        );
        $courseallocation->sent = \Carbon\Carbon::now();
        $courseallocation->sentby = $user->id;
        $courseallocation->save();
        Mail::to($participantdetails->email)->send(new ResendCourseInvite($coursedata));
        //return redirect("/participant/".$participantid)->with('status', 'courseresent');
        return back()->with('status', 'courseresent');
    }

    public function participantallocatedelete($id,$participantid){
        CourseToUsers::where('courseallocateid', $id)->delete();
        return redirect("/participant/".$participantid)->with('status', 'coursedeleted');
    }

    public function participantcertificate($coursekey){
        $user = Auth::user();

        $courseallocation = CourseToUsers::select()
            ->where('coursekey','=',$coursekey)
            ->first();
    }

    public function coursemark($id, $coursekey){
        $user = Auth::user();
        $coursedetails = Courses::select()
            ->where('courseid','=',$id)
            ->first();
        $courseallocation = CourseToUsers::select()
            ->where('coursekey','=',$coursekey)
            ->first();
        $participantdetails = Participants::select()
            ->where('participantid','=',$courseallocation->user_id)
            ->first();
        $slidestomark = CourseAttempts::select()
            ->where('course_id','=',$id)
            ->where('coursekey','=',$coursekey)
            ->where('user_answer_marked','=','0')
            ->get();
        $smark = array();
        foreach($slidestomark as $sm){
            $coursequestion = CourseSlideQuestions::select()
                ->where('slideid','=',$sm->question_id)
                ->first();
            $courseslide = CourseSlides::select()
                ->where('courseslideid','=',$sm->question_id)
                ->first();
            $smark[] = array('questiontext'=>$coursequestion->questiontext,'useranswer'=>$sm->user_answer,'questionid'=>$sm->question_id,'pointsavailable'=>$courseslide->slide_points,'userpoints'=>$sm->user_answer_marked_score);
        }
        $returnarray = array("participantid"=>$participantdetails->participantid,"participant"=>$participantdetails->firstname.' '.$participantdetails->lastname,"courseid"=>$id,"coursekey"=>$coursekey,"coursetitle"=>$coursedetails->course_title,"answers"=>$smark);
        return view('courses.participantmark', compact('user','returnarray'));
    }

    public function coursemarksubmit(Request $request, $id, $coursekey){
        $courseallocation = CourseToUsers::select()
            ->where('coursekey','=',$coursekey)
            ->first();
        foreach($request->input('score') as $key => $value){
            $slidestomark = CourseAttempts::select()
                ->where('course_id','=',$id)
                ->where('coursekey','=',$coursekey)
                ->where('question_id','=',$key)
                ->first();
            $slidestomark->user_answer_marked = '1';
            $slidestomark->user_answer_marked_score = $value;
            $slidestomark->save();
        }

        $coursedetails = Courses::select()
            ->where('courseid','=',$id)
            ->first();

        $participantscore = (new CoursesController)->participantcoursepassrate($courseallocation->course_id,$courseallocation->user_id,$coursekey);

        if($participantscore>=$coursedetails->passrate){ //scored and passed
            // generate certificate
            $filename = (new CoursesController)->participantgeneratecertificate($courseallocation->course_id,$coursekey);
            $courseallocation->coursescore = $participantscore;
            $courseallocation->completed = '1'; //passed
            $courseallocation->datecompleted = \Carbon\Carbon::now();
            $courseallocation->coursecertificate = $filename . '.pdf';
            $courseallocation->save();
        }else{
            $courseallocation->coursescore = $participantscore;
            $courseallocation->completed = '2'; //failed
            $courseallocation->datecompleted = \Carbon\Carbon::now();
            $courseallocation->coursecertificate = '';
            $courseallocation->save();
        }

        return redirect("/marking")->with('status', 'coursemarked');
    }

    public function marking(){
        $user = Auth::user();
        $slidestomark = CourseAttempts::select()
            ->where('user_answer_marked','=','0')
            ->groupBy('coursekey')
            ->get();
        $tomark = array();
        foreach($slidestomark as $sm){
            $participantdetails = Participants::select()
                ->where('participantid','=',$sm->user_id)
                ->first();
            $coursedetails = Courses::select()
                ->where('courseid','=',$sm->course_id)
                ->first();
            if(isset($coursedetails['courseid'])) { //ignore deleted courses
                $tomark[] = array("participantid" => $sm->user_id, "participant" => $participantdetails['firstname'] . " " . $participantdetails['lastname'], 'coursetitle' => $coursedetails['course_title'], 'courseid' => $sm->course_id, 'coursekey' => $sm->coursekey);
            }
        }
        return view('courses.marking', compact('user','tomark'));
    }

    public function bulkinvite(Request $request){
        $user = Auth::user();
        //$serialised = unserialize($request['formData2']);
        $decodedData = json_decode($request['formData'], true);
        $requestpost = print_r($decodedData,true);
        foreach($decodedData as $key=>$value) {
            $keyclean = str_replace("resend_","",$key);

            $courseallocation = CourseToUsers::select()
                ->where('courseallocateid','=',$keyclean)
                ->first();
            $participantdetails = Participants::select()
                ->where('participantid','=',$courseallocation->user_id)
                ->first();
            $coursedetails = Courses::select()
                ->where('courseid','=',$courseallocation->course_id)
                ->first();

            $coursedata = array(
                "coursekey" => $courseallocation->coursekey,
                "coursetitle" => $coursedetails->course_title,
                "first_name" => $participantdetails->firstname,
                "last_name" => $participantdetails->lastname
            );
            Mail::to($participantdetails->email)->send(new ResendCourseInvite($coursedata));
            $courseallocation->sent = \Carbon\Carbon::now();
            $courseallocation->sentby = $user->id;
            $courseallocation->save();
        }
        return true;
    }
}
