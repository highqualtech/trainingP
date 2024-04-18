@extends('app', ['user' => $user])

@section('content')
    <h1>Course Mark</h1>
    <table class="table table-striped">
        <tr>
            <td><strong>Course Title</strong></td>
            <td>{{ $returnarray['coursetitle'] }}</td>
        </tr>
        <tr>
            <td><strong>Participant</strong></td>
            <td>{{ $returnarray['participant'] }}</td>
        </tr>
    </table>
    <h2>Answers</h2>
    <form method="POST" action="{{ route('courseparticipants.coursemarksubmit', ['id' => $returnarray['courseid'],'coursekey' => $returnarray['coursekey']]) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
    @foreach($returnarray['answers'] as $raa )
        <strong>Question: {{ $raa['questiontext'] }}</strong><br>
        {{ $raa['useranswer'] }}
        <table class="table">
            <tr>
                <td>Points available: {{ $raa['pointsavailable'] }}</td>
                <td><select name="score[{{ $raa['questionid'] }}]" class="form-control">
                        <option value="0">0</option>
                        <?php
$counter = 1;
while ($counter <= $raa['pointsavailable']) {?><option value="{{ $counter }}">{{ $counter }}</option><?php $counter++; }?>
                    </select></td>
            </tr>
        </table>

    @endforeach

        <input type="submit" name="submitmarking" value="Submit Marking" class="btn btn-primary">
    </form>

@stop
@section('javascript')

@stop
