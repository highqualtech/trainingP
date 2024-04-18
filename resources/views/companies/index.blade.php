@extends('app', ['user' => $user])

@section('content')
    <h1>Companies</h1>
    @if (session('status') === 'companyupdated')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Company Updated</p>
    @endif
    @if (session('status') === 'companydeleted')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Company Deleted</p>
    @endif
    @if (session('status') === 'companycreated')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Company Created</p>
    @endif

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="currentcompany-tab" data-bs-toggle="tab" data-bs-target="#currentcompany" type="button" role="tab" aria-controls="currentcompany" aria-selected="true">Current Companies</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="addcompany-tab" data-bs-toggle="tab" data-bs-target="#addcompany" type="button" role="tab" aria-controls="addcompany" aria-selected="false">Add Company</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="currentcompany" role="tabpanel" aria-labelledby="currentcompany-tab">
            <table class="table table-striped" id="companytable">
                <thead>
                <tr>
                    <th>Company Name</th>
                    <th>Company ID</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($companies as $c)
                    <tr>
                        <td>{{ $c->companyname }}</td>
                        <td>{{ $c->companyid }}</td>
                        <td><a href="/company/{{ $c->companyid }}" class="btn btn-success">Edit</a></td>
                        <td><form method="POST" action="{{ route('company.destroy', ['id' => $c->companyid]) }}">
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
            <form method="POST" action="{{ route('company.create') }}">
                @csrf
                @method('POST')
            <table class="table">
                <tr>
                    <td>Company Name</td>
                    <td><input type="text" name="companyname" class="form-control" required></td>
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
