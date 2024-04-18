@extends('app', ['user' => $user])

@section('content')
    <h1>Marking</h1>
    @if (session('status') === 'coursemarked')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Course Marked</p>
    @endif
    <table class="table table-striped" id="markingtable">
        <thead>
        <tr>
            <th>Participant</th>
            <th>Course Title</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($tomark as $tm)
            <tr>
                <td>{{ $tm['participant'] }}</td>
                <td>{{ $tm['coursetitle'] }}</td>
                <td><a href="/courseparticipantsmark/{{ $tm['courseid'] }}/{{ $tm['coursekey'] }}" class="btn btn-success">Mark</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

@stop
@section('javascript')

@stop
