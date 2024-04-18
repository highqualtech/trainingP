@extends('app', ['user' => $user])

@section('content')
    <h1>Intake Group Edit</h1>
    <form method="POST" action="{{ route('intake.update', ['id' => $intakegroup->intakegroupid]) }}">
        @csrf
        @method('PATCH')
        <table class="table table-striped">
            <tr>
                <td>Intake Group Name: </td>
                <td><input type="text" name="groupname" value="{{ $intakegroup->groupname }}" class="form-control" required></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" value="Save" class="btn btn-primary"></td>
            </tr>
        </table>
    </form>
@stop
@section('javascript')

@stop
