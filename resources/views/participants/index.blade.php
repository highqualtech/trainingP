@extends('app', ['user' => $user])

@section('content')<a href="/archivedparticipants" class="btn btn-info float-end">Archived Participants</a>
    <h1>Participants</h1>
    @if (session('status') === 'participantupdated')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Participant Updated</p>
    @endif
    @if (session('status') === 'participantdeleted')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Participant Deleted</p>
    @endif
    @if (session('status') === 'participantcreated')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Participant Created</p>
    @endif

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="currentcompany-tab" data-bs-toggle="tab" data-bs-target="#currentcompany" type="button" role="tab" aria-controls="currentcompany" aria-selected="true">Current Participants</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="addcompany-tab" data-bs-toggle="tab" data-bs-target="#addcompany" type="button" role="tab" aria-controls="addcompany" aria-selected="false">Add Participant</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="currentcompany" role="tabpanel" aria-labelledby="currentcompany-tab">
            <table class="table table-striped" id="companytable">
                <thead>
                <tr>
                    <th><input type="checkbox" name="selectallarchive" value="1" id="selectallarchive"></th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Company</th>
                    <th>Intake Group</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($participants as $p)
                    <tr>
                        <td><input type="checkbox" name="archive_{{ $p->participantid }}" value="1" class="notsentitems"></td>
                        <td>{{ $p->firstname }}</td>
                        <td>{{ $p->lastname }}</td>
                        <td>{{ $p->companyname }}</td>
                        <td>{{ $p->groupname }}</td>
                        <td>{{ $p->email }}</td>
                        <td>{{ $p->phone }}</td>
                        <td><a href="/participant/{{ $p->participantid }}" class="btn btn-success">Edit</a></td>
                        <td><form method="POST" action="{{ route('participant.destroy', ['id' => $p->participantid]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</button>
                            </form></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button class="btn btn-success" id="archiveparticipants">Archive Selected</button>
        </div>
        <div class="tab-pane fade" id="addcompany" role="tabpanel" aria-labelledby="addcompany-tab">
            <form method="POST" action="{{ route('participant.create') }}">
                @csrf
                @method('POST')
                <table class="table">
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
                        <td colspan="2"><button type="submit" class="btn btn-success">Save</button></td>
                    </tr>
                </table>

            </form>
        </div>
    </div>


@stop
@section('javascript')
    <script type="module">
        $(document).ready( function () {
            $('#companytable').DataTable()
        } );

    </script>
    <script type="text/javascript">

        $("#selectallarchive").click(function(){
            if($("#selectallarchive").is(':checked')){
                $(".notsentitems").prop('checked', true);
            }else{
                $(".notsentitems").prop('checked', false);
            }
        });

        $("#archiveparticipants").click(function(){
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
                url: '/bulkarchiveparticipants', // Replace with your server endpoint
                data: {_token: CSRF_TOKENX, formData: JSON.stringify(formData)},
                dataType: 'JSON',
                success: function(response) {
                    // Handle the successful response from the server
                    console.log('Success:', response);
                    alert("Participants Archived");
                    location.reload();
                },
                error: function(error) {
                    // Handle errors
                    console.error('Error:', error);
                }
            });
        });
    </script>
@stop
