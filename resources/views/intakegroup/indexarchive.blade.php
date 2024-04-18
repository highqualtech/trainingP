@extends('app', ['user' => $user])

@section('content')
    <h1>Archived Intake Groups</h1>
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
        <p class="p-3 mb-2 bg-success text-white">Intake Group Un-Archived</p>
    @endif

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="currentcompany-tab" data-bs-toggle="tab" data-bs-target="#currentcompany" type="button" role="tab" aria-controls="currentcompany" aria-selected="true">Archived Intake Groups</button>
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
                        <td><form method="POST" action="{{ route('intake.unarchive', ['id' => $i->intakegroupid]) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure?');">Un-Archive</button>
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

    </div>


@stop
@section('javascript')
    <script type="module">
        $(document).ready( function () {
            $('#companytable').DataTable()
        } );

    </script>
@stop
