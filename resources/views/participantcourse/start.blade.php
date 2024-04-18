@extends('frontapp')

@section('content')
    <h1>Welcome to the Training Portal</h1>
    <br><br>
    <table class="table table-striped">
        <tr>
            <td style="width:250px;"><strong>Participant Name: </strong></td>
            <td>{{ $participantdetails->firstname }} {{ $participantdetails->lastname }}</td>
        </tr>
        <tr>
            <td><strong>Course Title: </strong></td>
            <td>{{ $coursedetails->course_title }}</td>
        </tr>
        @if($coursedetails->course_description !='')
            <tr>
                <td><strong>Course Description: </strong></td>
                <td>{{ $coursedetails->course_description }}</td>
            </tr>
        @endif
        @if($coursedetails->document !='')
            <tr>
                <td><strong>Course Download: </strong></td>
                <td><a href="/storage/{{ $coursedetails->document }}" class="btn btn-success" target="_blank">Download</a></td>
            </tr>
        @endif
    </table><br><br>
    @if($inprogress=='1')
        You have already started this course. Please click on the button below to continue.<br><br>
        <a href="/c/{{ $coursekey }}/{{ $nextquestion }}" class="btn btn-success">START</a>
    @else
        To start the course, please click on the button below.<br><br>
    <a href="/c/{{ $coursekey }}/{{ $nextquestion }}" class="btn btn-success">START</a>
    @endif
@stop
@section('javascript')

@stop
