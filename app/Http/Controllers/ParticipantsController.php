<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantsRequest;
use App\Http\Requests\UpdateParticipantsRequest;
use App\Models\Companies;
use App\Models\Courses;
use App\Models\CourseToUsers;
use App\Models\IntakeGroups;
use App\Models\Participants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $participants = Participants::select()
            ->leftJoin('companies','companies.companyid','=','participants.company')
            ->leftJoin('intake_groups','intake_groups.intakegroupid','=','participants.intakegroup')
            ->where('archive','=',0)
            ->orderBy('firstname','ASC')
            ->get();
        $companies = Companies::select()
            ->orderBy('companyname','ASC')
            ->get();
        $intakegroups = IntakeGroups::select()
            ->where('grouparchive','=',0)
            ->orderBy('groupname')
            ->get();
        return view('participants.index', compact('user','participants','companies','intakegroups'));
    }

    public function indexarchived()
    {
        $user = Auth::user();
        $participants = Participants::select()
            ->leftJoin('companies','companies.companyid','=','participants.company')
            ->leftJoin('intake_groups','intake_groups.intakegroupid','=','participants.intakegroup')
            ->where('archive','=',1)
            ->orderBy('firstname','ASC')
            ->get();
        $companies = Companies::select()
            ->orderBy('companyname','ASC')
            ->get();
        $intakegroups = IntakeGroups::select()
            ->where('grouparchive','=',0)
            ->orderBy('groupname')
            ->get();
        return view('participants.indexarchive', compact('user','participants','companies','intakegroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createparticipant(Request $request)
    {

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
        return redirect("/participants")->with('status', 'participantcreated');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParticipantsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Participants $participants)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $participant = Participants::select()
            ->where('participantid','=',$id)
            ->first();
        $companies = Companies::select()
            ->orderBy('companyname','ASC')
            ->get();
        $courses = Courses::select()
            ->orderBy('course_title','ASC')
            ->get();
        $courseallocations = CourseToUsers::select()
            ->where('user_id','=',$id)
            ->leftJoin('courses','courses.courseid','=','course_to_users.course_id')
            ->get();
        $courseprogress = array();
        foreach($courseallocations as $ca){
            $percent = (new CourseAttemptsController)->calculateprogress($ca->course_id,$ca->coursekey);
            $courseprogress[$ca->coursekey] = $percent;
        }

        $intakegroups = IntakeGroups::select()
            ->where('grouparchive','=',0)
            ->orderBy('groupname')
            ->get();

        return view('participants.edit', compact('user','participant','companies','courses','courseallocations','courseprogress','intakegroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if($request->input('newcompany')!=''){
            $newcompany = new Companies();
            $newcompany->companyname = $request->input('newcompany');
            $newcompany->save();
            $companyid = $newcompany->companyid;
        }else{
            $companyid = $request->input('company');
        }
        $participant = Participants::select()
            ->where('participantid','=',$id)
            ->first();
        $participant->firstname = $request->input('firstname');
        $participant->lastname = $request->input('lastname');
        $participant->email = $request->input('email');
        $participant->phone = $request->input('phone');
        $participant->company = $companyid;
        $participant->intakegroup = $request->input('intakegroup');
        $participant->save();
        return redirect("/participants")->with('status', 'participantupdated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Participants::where('participantid', $id)->delete();
        CourseToUsers::where('user_id',$id)->delete();
        return redirect("/participants")->with('status', 'participantdeleted');
    }

    public function csvimport(){
        $user = Auth::user();
        return view('participants.csvimport', compact('user'));
    }

    public function csvimportprocess(Request $request){

        $file = $request->file('csvfile');
        $fileContents = file($file->getPathname());

        $i=0;
        foreach ($fileContents as $line) {
            if($i>0) {
                $data = str_getcsv($line);
                $dataprint = print_r($data,true);


                if($data[5]==''){
                    $data[5] = 0;
                }
                if($data[4]==''){
                    $data[4] = 0;
                }
                $participantid = Participants::create([
                    'firstname' => $data[0],
                    'lastname' => $data[1],
                    'email' => $data[2],
                    'phone' => $data[3],
                    'company' => $data[4],
                    'intakegroup' => $data[5],
                ]);
                if ($data[6] != '') {
                    $randomkey = (new CourseToUsersController)->generateRandomKey(32);
                    CourseToUsers::create([
                        'course_id' => $data[6],
                        'user_id' => $participantid->participantid,
                        'coursekey' => $randomkey
                    ]);
                }
                if ($data[7] != '') {
                    $randomkey = (new CourseToUsersController)->generateRandomKey(32);
                    CourseToUsers::create([
                        'course_id' => $data[7],
                        'user_id' => $participantid->participantid,
                        'coursekey' => $randomkey
                    ]);
                }
                if ($data[8] != '') {
                    $randomkey = (new CourseToUsersController)->generateRandomKey(32);
                    CourseToUsers::create([
                        'course_id' => $data[8],
                        'user_id' => $participantid->participantid,
                        'coursekey' => $randomkey
                    ]);
                }
                if ($data[9] != '') {
                    $randomkey = (new CourseToUsersController)->generateRandomKey(32);
                    CourseToUsers::create([
                        'course_id' => $data[9],
                        'user_id' => $participantid->participantid,
                        'coursekey' => $randomkey
                    ]);
                }
                if ($data[10] != '') {
                    $randomkey = (new CourseToUsersController)->generateRandomKey(32);
                    CourseToUsers::create([
                        'course_id' => $data[10],
                        'user_id' => $participantid->participantid,
                        'coursekey' => $randomkey
                    ]);
                }

            }
            $i++;
        }
        return redirect("/csvimport")->with('status', 'csvimportcomplete');
    }

    public function bulkarchive(Request $request){
        $decodedData = json_decode($request['formData'], true);
        $requestpost = print_r($decodedData,true);
        foreach($decodedData as $key=>$value) {
            $keyclean = str_replace("archive_","",$key);
            Participants::where('participantid', $keyclean)
                ->update(['archive' => 1]);
        }
        return true;
    }
    public function bulkunarchive(Request $request){
        $decodedData = json_decode($request['formData'], true);
        $requestpost = print_r($decodedData,true);
        foreach($decodedData as $key=>$value) {
            $keyclean = str_replace("archive_","",$key);
            Participants::where('participantid', $keyclean)
                ->update(['archive' => 0]);
        }
        return true;
    }
}
