@extends('app', ['user' => $user])

@section('content')
    <div class="col-sm-12" style="height:50px;">
    <div class="float-end" style="width:600px;">
    <form action="/courseparticipantsredirect/{{ $coursedetails->courseid }}" method="post">
        @csrf
        <table class="table">
            <tr>
                <td>Intake Group Filter: </td>
                <td><select name="intakegroup" class="form-control">
                        <option value="0">Reset</option>
                        @foreach($intakegroups as $ig)
                            <option value="{{ $ig['intakegroupid'] }}">{{ $ig['groupname'] }}</option>
                        @endforeach
                    </select></td>
                <td><input type="submit" name="submit" value="Submit" class="btn btn-primary"></td>
            </tr>
        </table>



    </form>
</div>
    </div>
    <h1>Course Participants - {{ $coursedetails->course_title }}</h1>

    @if (session('status') === 'coursesent')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Participant Invited</p>
    @endif
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pendingcourses-tab" data-bs-toggle="tab" data-bs-target="#pendingcourses" type="button" role="tab" aria-controls="pendingcourses" aria-selected="true">Pending <span class="badge bg-secondary">{{ sizeof($pendingusersarray) }}</span></button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="notsent-tab" data-bs-toggle="tab" data-bs-target="#notsent" type="button" role="tab" aria-controls="notsent" aria-selected="false">Not Sent <span class="badge bg-secondary">{{ sizeof($notsentusersarray) }}</span></button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completepass-tab" data-bs-toggle="tab" data-bs-target="#completepass" type="button" role="tab" aria-controls="completepass" aria-selected="false">Complete (pass) <span class="badge bg-secondary">{{ sizeof($completepassusersarray) }}</span></button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completefail-tab" data-bs-toggle="tab" data-bs-target="#completefail" type="button" role="tab" aria-controls="completefail" aria-selected="false">Complete (fail) <span class="badge bg-secondary">{{ sizeof($completefailusersarray) }}</span></button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completemarking-tab" data-bs-toggle="tab" data-bs-target="#completemarking" type="button" role="tab" aria-controls="completemarking" aria-selected="false">Complete (pending marking) <span class="badge bg-secondary">{{ sizeof($completependingusersarray) }}</span></button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="invite-tab" data-bs-toggle="tab" data-bs-target="#invite" type="button" role="tab" aria-controls="invite" aria-selected="false">Invite Participant (New)</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="inviteexisting-tab" data-bs-toggle="tab" data-bs-target="#inviteexisting" type="button" role="tab" aria-controls="inviteexisting" aria-selected="false">Invite Participant (Existing)</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="pendingcourses" role="tabpanel" aria-labelledby="pendingcourses-tab">
            <table class="table table-striped" id="pendingcoursestable">
                <thead>
                <tr>
                    <th>Participant</th>
                    <th>Company</th>
                    <th>Date Sent</th>
                    <th>Progress</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if(sizeof($pendingusersarray)>0)
                @foreach($pendingusersarray as $p)
                    <tr>
                        <td>{{ $p['participantname'] }}</td>
                        <td>{{ $p['company'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($p['datesent'])->format("d-m-Y H:i") }}</td>
                        <td>{{ $p['progress'] }}%</td>
                        <td><form method="POST" action="{{ route('participantcourse.resend', ['id' => $p['courseallocateid'],'participantid' => $p['participantid']]) }}">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-info" onclick="return confirm('Are you sure?');">Resend Email</button>
                            </form></td>
                        <td><a class="copylink btn btn-primary" data-copylinkcode="{{ $p['coursekey'] }}">Copy Link</a><span id="copylink{{ $p['coursekey'] }}" style="display:none;">
                                /c/{{ $p['coursekey'] }}</span></td>
                        <td><form method="POST" action="{{ route('participantcourse.destroy', ['id' => $p['courseallocateid'],'participantid' => $p['participantid']]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Remove From Course</button>
                            </form></td>
                    </tr>
                @endforeach
                    @endif
                </tbody>

            </table>
        </div>
        <div class="tab-pane fade show" id="notsent" role="tabpanel" aria-labelledby="notsent-tab">
            <table class="table table-striped" id="notsenttable">
                <thead>
                <tr>
                    <th></th>
                    <th>Participant</th>
                    <th>Company</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if(sizeof($notsentusersarray)>0)
                @foreach($notsentusersarray as $p)
                    <tr>
                        <td><input type="checkbox" name="resend_{{ $p['courseallocateid'] }}" value="1" class="notsentitems"></td>
                        <td>{{ $p['participantname'] }}</td>
                        <td>{{ $p['company'] }}</td>
                        <td><form method="POST" action="{{ route('participantcourse.resend', ['id' => $p['courseallocateid'],'participantid' => $p['participantid']]) }}">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-info" onclick="return confirm('Are you sure?');">Resend Email</button>
                            </form></td>
                        <td><a class="copylink btn btn-primary" data-copylinkcode="{{ $p['coursekey'] }}">Copy Link</a><span id="copylink{{ $p['coursekey'] }}" style="display:none;">/c/{{ $p['coursekey'] }}</span></td>
                        <td><form method="POST" action="{{ route('participantcourse.destroy', ['id' => $p['courseallocateid'],'participantid' => $p['participantid']]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Remove From Course</button>
                            </form></td>
                    </tr>
                @endforeach
                @endif
                </tbody>

            </table>
            <button class="btn btn-success" id="sendinvites">Send Invites</button>
        </div>
        <div class="tab-pane fade show" id="completepass" role="tabpanel" aria-labelledby="completepass-tab">
            <table class="table table-striped" id="completepasstable">
                <thead>
                <tr>
                    <th>Participant</th>
                    <th>Company</th>
                    <th>Date Complete</th>
                    <th>Score</th>
                    <th>Certificate</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if(sizeof($completepassusersarray)>0)
                @foreach($completepassusersarray as $p)
                    <tr>
                        <td>{{ $p['participantname'] }}</td>
                        <td>{{ $p['company'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($p['datecomplete'])->format("d-m-Y H:i") }}</td>
                        <td>{{ $p['score'] }}%</td>
                        <td><a href="/certificate/{{ $p['coursecertificate'] }}" class="btn btn-warning">Certificate</a></td>
                        <td><form method="POST" action="{{ route('participantcourse.resend', ['id' => $p['courseallocateid'],'participantid' => $p['participantid']]) }}">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-info" onclick="return confirm('Are you sure?');">Resend Email</button>
                            </form></td>
                        <td><a class="copylink btn btn-primary" data-copylinkcode="{{ $p['coursekey'] }}">Copy Link</a><span id="copylink{{ $p['coursekey'] }}" style="display:none;">/c/{{ $p['coursekey'] }}</span></td>
                        <td><form method="POST" action="{{ route('participantcourse.destroy', ['id' => $p['courseallocateid'],'participantid' => $p['participantid']]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Remove From Course</button>
                            </form></td>
                    </tr>
                @endforeach
                @endif
                </tbody>

            </table>
        </div>
        <div class="tab-pane fade show" id="completefail" role="tabpanel" aria-labelledby="completefail-tab">
            <table class="table table-striped" id="completefailtable">
                <thead>
                <tr>
                    <th>Participant</th>
                    <th>Company</th>
                    <th>Date Complete</th>
                    <th>Score</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if(sizeof($completefailusersarray)>0)
                @foreach($completefailusersarray as $p)
                    <tr>
                        <td>{{ $p['participantname'] }}</td>
                        <td>{{ $p['company'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($p['datecomplete'])->format("d-m-Y H:i") }}</td>
                        <td>{{ $p['score'] }}%</td>
                        <td><form method="POST" action="{{ route('participantcourse.resend', ['id' => $p['courseallocateid'],'participantid' => $p['participantid']]) }}">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-info" onclick="return confirm('Are you sure?');">Resend Email</button>
                            </form></td>
                        <td><a class="copylink btn btn-primary" data-copylinkcode="{{ $p['coursekey'] }}">Copy Link</a><span id="copylink{{ $p['coursekey'] }}" style="display:none;">/c/{{ $p['coursekey'] }}</span></td>
                        <td><form method="POST" action="{{ route('participantcourse.destroy', ['id' => $p['courseallocateid'],'participantid' => $p['participantid']]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Remove From Course</button>
                            </form></td>
                    </tr>
                @endforeach
                @endif
                </tbody>

            </table>
        </div>
        <div class="tab-pane fade show" id="completemarking" role="tabpanel" aria-labelledby="completemarking-tab">
            <table class="table table-striped" id="completemarkingtable">
                <thead>
                <tr>
                    <th>Participant</th>
                    <th>Company</th>
                    <th>Date Complete</th>
                    <th>Score</th>
                    <th>Mark</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if(sizeof($completependingusersarray)>0)
                @foreach($completependingusersarray as $p)
                    <tr>
                        <td>{{ $p['participantname'] }}</td>
                        <td>{{ $p['company'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($p['datecomplete'])->format("d-m-Y H:i") }}</td>
                        <td>{{ $p['score'] }}%</td>
                        <td><a href="/courseparticipantsmark/{{ $coursedetails->courseid }}/{{ $p['coursekey'] }}" class="btn btn-primary">Mark</a></td>
                        <td><form method="POST" action="{{ route('participantcourse.resend', ['id' => $p['courseallocateid'],'participantid' => $p['participantid']]) }}">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-info" onclick="return confirm('Are you sure?');">Resend Email</button>
                            </form></td>
                        <td><a class="copylink btn btn-primary" data-copylinkcode="{{ $p['coursekey'] }}">Copy Link</a><span id="copylink{{ $p['coursekey'] }}" style="display:none;">/c/{{ $p['coursekey'] }}</span></td>
                        <td><form method="POST" action="{{ route('participantcourse.destroy', ['id' => $p['courseallocateid'],'participantid' => $p['participantid']]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Remove From Course</button>
                            </form></td>
                    </tr>
                @endforeach
                @endif
                </tbody>

            </table>
        </div>
        <div class="tab-pane fade show" id="invite" role="tabpanel" aria-labelledby="invite-tab">
            <form method="POST" action="{{ route('course.quickinvitesend') }}">
                @csrf
                @method('POST')
                <table class="table table-striped">


                    <tr>
                        <td>First Name</td>
                        <td><input type="text" name="firstname" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td><input type="text" name="lastname" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><input type="email" name="email" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td><input type="text" name="phone" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Select Company</td>
                        <td><select name="company" class="form-control js-select" style="width:100%;">
                                <option value="0">None</option>
                                @foreach($companies as $c)
                                    <option value="{{ $c->companyid }}">{{ $c->companyname }}</option>
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
                                <option value="0">None</option>
                                @foreach($intakegroups as $ig)
                                    <option value="{{ $ig->intakegroupid }}">{{ $ig->groupname }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><input type="checkbox" value="1" name="sendemail"> Send Invite</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="hidden" name="course" value="{{ $coursedetails->courseid }}">
                            <input type="hidden" name="coursepage" value="1">
                            <input type="submit" class="btn btn-success" value="SEND" name="submitform"></td>
                    </tr>
                </table>
            </form>
        </div>
        <div class="tab-pane fade show" id="inviteexisting" role="tabpanel" aria-labelledby="inviteexisting-tab">
            <form method="POST" action="{{ route('participantscourse.allocate') }}">
                @csrf
                @method('POST')
                <table class="table table-striped">
                    <tr>
                        <td>
                            <select name="participantid" class="form-control js-select" style="width:100%">
                                @foreach($participantarray as $pa)
                                    <option value="{{ $pa['participantid'] }}">{{ $pa['participantname'] }}@if($pa['company']!='') ({{ $pa['company'] }})@endif - {{ $pa['participantemail'] }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="checkbox" name="sendemail" value="1"> Send Email
                            <input type="hidden" name="courseinvite" value="1">
                            <input type="hidden" name="courseallocate" value="{{ $coursedetails->courseid }}">
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
        $("#sendinvites").click(function(){
            var formData = {};
            $('.notsentitems').each(function() {
                console.log($(this).val());
                if($(this).is(':checked')) {
                    formData[$(this).attr('name')] = $(this).val();
                }
            });
            var CSRF_TOKENX = $('meta[name="csrf-token"]').attr('content');
            console.log(JSON.stringify(formData));
            $.ajax({
                type: 'POST',
                url: '/bulkinvite', // Replace with your server endpoint
                data: {_token: CSRF_TOKENX, formData: JSON.stringify(formData)},
                dataType: 'JSON',
                success: function(response) {
                    // Handle the successful response from the server
                    console.log('Success:', response);
                    alert("Invites sent");
                },
                error: function(error) {
                    // Handle errors
                    console.error('Error:', error);
                }
            });
        });

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
        let table1 = new DataTable('#pendingcoursestable');
        let table2 = new DataTable('#notsenttable');
        let table3 = new DataTable('#completepasstable');
        let table4 = new DataTable('#completefailtable');
        let table5 = new DataTable('#completemarkingtable');
    </script>
@stop
