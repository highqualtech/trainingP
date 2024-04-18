<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIntakeGroupsRequest;
use App\Http\Requests\UpdateIntakeGroupsRequest;
use App\Models\IntakeGroups;
use App\Models\Participants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntakeGroupsController extends Controller
{
    public function index(){
        $user = Auth::user();
        $intakegroups = IntakeGroups::select()
            ->where('grouparchive','=','0')
            ->orderBy('groupname','ASC')
            ->get();
        return view('intakegroup.index', compact('user','intakegroups'));
    }

    public function indexarchived(){
        $user = Auth::user();
        $intakegroups = IntakeGroups::select()
            ->where('grouparchive','=','1')
            ->orderBy('groupname','ASC')
            ->get();
        return view('intakegroup.indexarchive', compact('user','intakegroups'));
    }

    public function createintake(Request $request){
        $intakegroup = new IntakeGroups();
        $intakegroup->groupname = $request->input('groupname');
        $intakegroup->save();
        return redirect("/intakegroups")->with('status', 'groupcreated');
    }

    public function edit($id){
        $user = Auth::user();
        $intakegroup = IntakeGroups::select()
            ->where('intakegroupid','=',$id)
            ->first();
        return view('intakegroup.edit', compact('user','intakegroup'));
    }

    public function update(Request $request, $id){
        $user = Auth::user();
        $intakegroup = IntakeGroups::select()
            ->where('intakegroupid','=',$id)
            ->first();
        $intakegroup->groupname = $request->input('groupname');
        $intakegroup->save();
        return redirect("/intakegroups")->with('status', 'groupupdated');
    }

    public function destroy($id){
        IntakeGroups::where('intakegroupid', $id)->delete();
        Participants::where('intakegroup', $id)
            ->update(['intakegroup' => 0]);
        return redirect("/intakegroups")->with('status', 'groupdeleted');
    }

    public function indexarchive($id){
        $user = Auth::user();
        $intakegroup = IntakeGroups::select()
            ->where('intakegroupid','=',$id)
            ->first();
        $intakegroup->grouparchive = 1;
        $intakegroup->save();
        return redirect("/intakegroups")->with('status', 'grouparchived');
    }
    public function indexunarchive($id){
        $user = Auth::user();
        $intakegroup = IntakeGroups::select()
            ->where('intakegroupid','=',$id)
            ->first();
        $intakegroup->grouparchive = 0;
        $intakegroup->save();
        return redirect("/archivedintakegroups")->with('status', 'grouparchived');
    }
}
