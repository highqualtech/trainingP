@extends('app', ['user' => $user])

@section('content')<a href="/archivedcourses" class="btn btn-info float-end">Archived Courses</a>
    <h1>Courses</h1>

    @if (session('status') === 'coursearchived')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Course Archived</p>
    @endif
    @if (session('status') === 'courseduplicated')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Course Duplicated</p>
    @endif
    <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="currentcourses-tab" data-bs-toggle="tab" data-bs-target="#currentcourses" type="button" role="tab" aria-controls="currentcourses" aria-selected="true">Current Courses</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="addcourses-tab" data-bs-toggle="tab" data-bs-target="#addcourses" type="button" role="tab" aria-controls="addcourses" aria-selected="false">Add Course</button>
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
                        <td><a href="/previewcourse/{{ $c->courseid }}" class="btn btn-dark" target="_blank">Preview Course</a></td>
                        <td><a href="/courseduplicate/{{ $c->courseid }}" class="btn btn-secondary" onclick="return confirm('Are you sure?');">Duplicate</a></td>
                        <td><form method="POST" action="{{ route('course.archive', ['id' => $c->courseid]) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure?');">Archive</button>
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
        <div class="tab-pane fade" id="addcourses" role="tabpanel" aria-labelledby="addcourses-tab">
            <form method="POST" action="{{ route('course.create') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')
            <table class="table table-striped">
                <tr>
                    <td>Course Title</td>
                    <td><input type="text" name="course_title" class="form-control" required></td>
                </tr>
                <tr>
                    <td>Course Reference</td>
                    <td><input type="text" name="course_reference" class="form-control" required></td>
                </tr>
                <tr>
                    <td colspan="2">Course Description<br>
                    <textarea name="course_description" class="form-control"></textarea></td>
                </tr>
                <tr>
                    <td>Course Instructor</td>
                    <td><input type="text" name="instructor" class="form-control" required></td>
                </tr>
                <tr>
                    <td>Course Type</td>
                    <td>
                        <select name="coursettpe" id="coursettpe" class="form-control">
                            <option value="1">Attendance only</option>
                            <option value="2">Pass/Fail</option>
                        </select>
                    </td>
                </tr>
                <tr  class="passraterow" style="display:none;">
                    <td>Pass rate</td>
                    <td><input type="text" name="passrate" id="passrate" class="form-control"></td>
                </tr>

                <tr>
                    <td>Course Download</td>
                    <td><input type="file" name="document" class="form-control"></td>
                </tr>
                <tr>
                    <td>Generate Certificate</td>
                    <td><input type="checkbox" id="coursecertify" name="coursecertify" value="1"></td>
                </tr>
                <tr class="certificaterow" style="display:none;">
                    <td>Certification Period</td>
                    <td><input type="text" name="certification_period" id="certification_period" class="form-control"></td>
                </tr>
                <tr class="certificaterow" style="display:none;">
                    <td>Course Certificate Point 1</td>
                    <td><input type="text" name="certificate1" class="form-control"></td>
                </tr>
                <tr class="certificaterow" style="display:none;">
                    <td>Course Certificate Point 2</td>
                    <td><input type="text" name="certificate2" class="form-control"></td>
                </tr>
                <tr class="certificaterow" style="display:none;">
                    <td>Course Certificate Point 3</td>
                    <td><input type="text" name="certificate3" class="form-control"></td>
                </tr>
                <tr class="certificaterow" style="display:none;">
                    <td>Course Certificate Point 4</td>
                    <td><input type="text" name="certificate4" class="form-control"></td>
                </tr>
                <tr class="certificaterow" style="display:none;">
                    <td>Course Certificate Point 5</td>
                    <td><input type="text" name="certificate5" class="form-control"></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="submit" name="submit" class="btn btn-success" value="Create Course"></td>
                </tr>
            </table>
            </form>
        </div>

@stop
@section('javascript')
            <script type="module">
                $(document).ready( function () {
                    $('#coursestable').DataTable()
                } );

            </script>

            <script type="text/javascript">
                $("#coursettpe").change(function(){
                    if($("#coursettpe").val()=='2'){
                        $(".passraterow").show();
                        $("#passrate").prop("required", true);
                    }else{
                        $(".passraterow").hide();
                        $("#passrate").prop("required", false);
                    }
                });

                $("#coursecertify").click(function(){
                    if($("#coursecertify").is(":checked")){
                        $(".certificaterow").show();
                        $("#certification_period").prop("required", true);
                    }else{
                        $(".certificaterow").hide();
                        $("#certification_period").prop("required", false);
                    }
                })
            </script>
@stop
