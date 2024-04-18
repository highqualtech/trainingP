@extends('app', ['user' => $user])

@section('content')
    <h1>Archived Courses</h1>
    @if (session('status') === 'coursearchived')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Course Un-Archived</p>
    @endif
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="currentcourses-tab" data-bs-toggle="tab" data-bs-target="#currentcourses" type="button" role="tab" aria-controls="currentcourses" aria-selected="true">Archived Courses</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="currentcourses" role="tabpanel" aria-labelledby="currentcourses-tab">
            <table class="table table-striped" id="coursestable">
                <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Course Ref</th>
                    <th>Course ID</th>
                    <th>Participants</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($courses as $c)
                    <tr>
                        <td>{{ $c->course_title }}</td>
                        <td>{{ $c->course_reference }}</td>
                        <td>{{ $c->courseid }}</td>
                        <td><a href="/courseparticipants/{{ $c->courseid }}" class="btn btn-info">Participants</a></td>
                        <td><a href="/course/{{ $c->courseid }}" class="btn btn-success">Edit</a></td>
                        <td><a href="/courseslides/{{ $c->courseid }}" class="btn btn-primary">Slides</a></td>
                        <td><a href="/courseduplicate/{{ $c->courseid }}" class="btn btn-secondary" onclick="return confirm('Are you sure?');">Duplicate</a></td>
                        <td><form method="POST" action="{{ route('course.unarchive', ['id' => $c->courseid]) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure?');">Un-Archive</button>
                            </form></td>
                        <td><form method="POST" action="{{ route('course.destroy', ['id' => $c->courseid]) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</button>
                            </form></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>


        @stop
        @section('javascript')
            <script type="module">
                $(document).ready( function () {
                    $('#coursestable').DataTable()
                } );

            </script>
@stop
