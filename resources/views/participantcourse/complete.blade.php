@extends('frontapp')

@section('content')
    <h1>Course Complete</h1>

    @if($courseallocation->completed=='1')
        @if(($courseallocation->coursecertificate!='')&&($coursedetails->coursecertify=='1'))
            Thank you, the course is now complete. <a href="/certificate/{{ $courseallocation->coursecertificate }}" target="_blank">Click here to download your certificate.</a>
        @endif
    @elseif($courseallocation->completed=='2')
        You have not passed this course. Please contact the Training Team on 015396 26251
        @elseif($courseallocation->completed=='3')
        Thank you, your course is currently being marked. We will be in touch shortly.
    @endif
    @if($coursedetails->document!='')
    <br><br>
    <a href="/storage/{{ $coursedetails->document }}" class="btn btn-success" target="_blank">Click here to download the course download.</a>@endif
@stop
@section('javascript')

@stop
