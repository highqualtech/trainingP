@extends('frontapp')

@section('content')
    <h1>Course Error</h1>
    There was an error loading your course. The team at have been notified and will be in touch.<br><br><em>ERROR CODE {{ $reason }}</em>
@stop
@section('javascript')

@stop
