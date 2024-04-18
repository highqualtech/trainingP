@extends('app', ['user' => $user])

@section('content')
    <h1>Participant Edit</h1>
    @if (session('status') === 'coursesent')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Course Allocated</p>
    @endif
    @if (session('status') === 'courseresent')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Course Resent</p>
    @endif
    @if (session('status') === 'coursedeleted')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Course Deleted</p>
    @endif
    <form method="POST" action="{{ route('participant.update', ['id' => $participant->participantid]) }}">
        @csrf
        @method('PATCH')
        <table class="table table-striped">
            <tr>
                <td>First Name</td>
                <td><input type="text" name="firstname" class="form-control" required value="{{ $participant->firstname }}"></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><input type="text" name="lastname" class="form-control" required value="{{ $participant->lastname }}"></td>
            </tr>

            <tr>
                <td>Email</td>
                <td><input type="email" name="email" class="form-control" required value="{{ $participant->email }}"></td>
            </tr>
            <tr>
                <td>Phone</td>
                <td><input type="text" name="phone" class="form-control" value="{{ $participant->phone }}"></td>
            </tr>
            <tr>
                <td>Company</td>
                <td><select name="company" class="form-control js-select" style="width:100%;">
                        <option value="0" @if($participant->company=='0') selected @endif>None</option>
                        @foreach($companies as $c)
                            <option value="{{ $c->companyid }}" @if($participant->company==$c->companyid) selected @endif>{{ $c->companyname }}</option>
                        @endforeach
                    </select></td>
            </tr>
            <tr>
                <td>Or Enter New Company</td>
                <td><input type="text" name="newcompany" class="form-control"></td>
            </tr>
            <tr>
                <td>Intake Group</td>
                <td>
                    <select name="intakegroup" class="form-control js-select" style="width:100%;">
                        <option value="0" @if($participant->intakegroup=='0') selected @endif>None</option>
                        @foreach($intakegroups as $ig)
                            <option value="{{ $ig->intakegroupid }}" @if($participant->intakegroup==$ig->intakegroupid) selected @endif>{{ $ig->groupname }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" value="Save" class="btn btn-primary"></td>
            </tr>
        </table>
    </form>
    <h1>Participant Courses</h1>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="currentcourses-tab" data-bs-toggle="tab" data-bs-target="#currentcourses" type="button" role="tab" aria-controls="currentcourses" aria-selected="true">Current Courses</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="allocatecourse-tab" data-bs-toggle="tab" data-bs-target="#allocatecourse" type="button" role="tab" aria-controls="allocatecourse" aria-selected="false">Allocate Course</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="currentcourses" role="tabpanel" aria-labelledby="currentcourses-tab">
            <table class="table table-striped" id="coursetable">
                <thead>
                <tr>
                    <th>Course Title</th>
                    <th>Date Sent</th>
                    <th>Progress</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($courseallocations as $ca)
                    <tr>
                        <td>{{ $ca['course_title'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($ca['sent'])->format("d-m-Y H:i") }}</td>
                        <td>@if(isset($courseprogress[$ca->coursekey])){{ $courseprogress[$ca->coursekey] }}%@endif</td>
                        <td><form method="POST" action="{{ route('participantcourse.resend', ['id' => $ca->courseallocateid,'participantid' => $participant->participantid]) }}">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-info" onclick="return confirm('Are you sure?');">Resend Email</button>
                            </form></td>
                        <td><a class="copylink btn btn-primary" data-copylinkcode="{{ $ca->coursekey }}">Copy Link</a><span id="copylink{{ $ca->coursekey }}" style="display:none;">/c/{{ $ca->coursekey }}</span></td>
                        <td><form method="POST" action="{{ route('participantcourse.destroy', ['id' => $ca->courseallocateid,'participantid' => $participant->participantid]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Remove From Course</button>
                            </form></td>
                        <td>@if($ca->coursecertificate!='')<a href="/certificate/{{ $ca->coursecertificate }}" class="btn btn-warning">Certificate</a>
                        @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="allocatecourse" role="tabpanel" aria-labelledby="allocatecourse-tab">
            <form method="POST" action="{{ route('participantscourse.allocate') }}">
                @csrf
                @method('POST')
            <table class="table table-striped">
                <tr>
                    <td>
                        <select name="courseallocate" class="form-control js-select" style="width:100%">
                            @foreach($courses as $c)
                                <option value="{{ $c->courseid }}">{{ $c->course_title }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" name="sendemail" value="1"> Send Email
                        <input type="hidden" name="participantid" value="{{ $participant->participantid }}">
                        <input type="submit" name="submit" value="Invite to course" class="btn btn-success">
                    </td>
                </tr>
            </table>
            </form>
        </div>

    </div>
@stop
@section('javascript')
<script type="text/javascript">
    $(".copylink").click(function(e){
        var bits = $(this).data('copylinkcode');

        var element = "#copylink"+bits;
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
        alert("Text copied");
    });
</script>
@stop
