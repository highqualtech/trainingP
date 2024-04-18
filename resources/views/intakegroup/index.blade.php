@extends('app', ['user' => $user])

@section('content')<a href="/archivedintakegroups" class="btn btn-info float-end">Archived Intake Groups</a>
    <h1>Intake Groups</h1>
    @if (session('status') === 'companyupdated')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Intake Group Updated</p>
    @endif
    @if (session('status') === 'companydeleted')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Intake Group Deleted</p>
    @endif
    @if (session('status') === 'companycreated')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Intake Group Created</p>
    @endif
    @if (session('status') === 'grouparchived')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Intake Group Archived</p>
    @endif

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="currentcompany-tab" data-bs-toggle="tab" data-bs-target="#currentcompany" type="button" role="tab" aria-controls="currentcompany" aria-selected="true">Current Intake Groups</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="addcompany-tab" data-bs-toggle="tab" data-bs-target="#addcompany" type="button" role="tab" aria-controls="addcompany" aria-selected="false">Add Intake Group</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="currentcompany" role="tabpanel" aria-labelledby="currentcompany-tab">
            <table class="table table-striped" id="companytable">
                <thead>
                <tr>
                    <th>Intake Group Name</th>
                    <th>Intake Group ID</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($intakegroups as $i)
                    <tr>
                        <td>{{ $i->groupname }}</td>
                        <td>{{ $i->intakegroupid }}</td>
                        <td><a href="/intakegroup/{{ $i->intakegroupid }}" class="btn btn-success">Edit</a></td>
                        <td><form method="POST" action="{{ route('intake.archive', ['id' => $i->intakegroupid]) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure?');">Archive</button>
                            </form></td>
                        <td><form method="POST" action="{{ route('intake.destroy', ['id' => $i->intakegroupid]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</button>
                            </form></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="addcompany" role="tabpanel" aria-labelledby="addcompany-tab">
            <form method="POST" action="{{ route('intake.create') }}">
                @csrf
                @method('POST')
                <table class="table">
                    <tr>
                        <td>Intake Group Name</td>
                        <td><input type="text" name="groupname" class="form-control" required></td>
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
@stop
