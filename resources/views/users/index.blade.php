@extends('app', ['user' => $user])

@section('content')
    <h1>Users</h1>
    @if (session('status') === 'userupdated')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">User Updated</p>
    @endif
    @if (session('status') === 'userdeleted')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">User Deleted</p>
    @endif
    @if (session('status') === 'usercreated')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">User Created</p>
    @endif

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="currentcompany-tab" data-bs-toggle="tab" data-bs-target="#currentcompany" type="button" role="tab" aria-controls="currentcompany" aria-selected="true">Current User</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="addcompany-tab" data-bs-toggle="tab" data-bs-target="#addcompany" type="button" role="tab" aria-controls="addcompany" aria-selected="false">Add User</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="currentcompany" role="tabpanel" aria-labelledby="currentcompany-tab">
            <table class="table table-striped" id="companytable">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td><a href="/user/{{ $u->id }}" class="btn btn-success">Edit</a></td>
                        <td><form method="POST" action="{{ route('user.destroy', ['id' => $u->id]) }}">
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
            <form method="POST" action="{{ route('user.create') }}">
                @csrf
                @method('POST')
                <table class="table">
                    <tr>
                        <td>Name</td>
                        <td><input type="text" name="username" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><input type="email" name="useremail" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><input type="text" name="userpassword" class="form-control" required></td>
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
