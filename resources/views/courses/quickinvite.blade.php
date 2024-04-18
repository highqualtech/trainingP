@extends('app', ['user' => $user])

@section('content')
<h1>Quick Invite</h1>
@if (session('status') === 'coursesent')
    <br><br>
    <p class="p-3 mb-2 bg-success text-white">Participant Created</p>
@endif
<form method="POST" action="{{ route('course.quickinvitesend') }}">
    @csrf
    @method('POST')
    <table class="table table-striped">
        <tr>
            <td>Course</td>
            <td><select name="course" class="form-control">
                    @foreach($courses as $course)
                        <option value="{{ $course['courseid'] }}">{{ $course['course_title'] }}</option>
                    @endforeach
                </select></td>
        </tr>


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
            <td><select name="company" class="form-control">
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
            <td colspan="2"><input type="submit" class="btn btn-success" value="SEND" name="submitform"></td>
        </tr>
    </table>
</form>
@stop
@section('javascript')

@stop
