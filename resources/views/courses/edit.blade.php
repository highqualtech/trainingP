@extends('app', ['user' => $user])

@section('content')
    <h1>Course Edit</h1>
    @if (session('status') === 'courseresent')
        <br><br>
        <p class="p-3 mb-2 bg-success text-white">Course Resent</p>
    @endif
    <form method="POST" action="{{ route('course.update', ['id' => $course->courseid]) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <table class="table table-striped">
            <tr>
                <td>Course Title</td>
                <td><input type="text" name="course_title" class="form-control" required value="{{ $course->course_title }}"></td>
            </tr>
            <tr>
                <td>Course Reference</td>
                <td><input type="text" name="course_reference" class="form-control" value="{{ $course->course_reference }}" required></td>
            </tr>
            <tr>
                <td colspan="2">Course Description<br>
                    <textarea name="course_description" class="form-control">{{ $course->course_description }}</textarea></td>
            </tr>
            <tr>
                <td>Course Instructor</td>
                <td><input type="text" name="instructor" class="form-control" required value="{{ $course->instructor }}"></td>
            </tr>
            <tr>
                <td>Course Type</td>
                <td>
                    <select name="coursettpe" id="coursettpe" class="form-control">
                        <option value="1" @if($course->coursettpe=='1') selected @endif>Attendance only</option>
                        <option value="2" @if($course->coursettpe=='2') selected @endif>Pass/Fail</option>
                    </select>
                </td>
            </tr>
            <tr class="passraterow" @if($course->coursettpe=='1') style="display:none;" @endif>
                <td>Passrate</td>
                <td><input type="text" name="passrate" id="passrate" class="form-control" value="{{ $course->passrate }}" @if($course->coursettpe=='2') required @endif></td>
            </tr>

            <tr>
                <td>Course Download @if($course->document!='')
                <br><a href="/storage/{{ $course->document }}" target="_blank">View current</a>
                @endif</td>
                <td><input type="file" name="document" class="form-control"></td>
            </tr>
            <tr>
                <td>Generate Certificate</td>
                <td><input type="checkbox" name="coursecertify" id="coursecertify" value="1" @if($course->coursecertify=='1') checked @endif></td>
            </tr>
            <tr class="certificaterow">
                <td>Certification Period</td>
                <td><input type="text" name="certification_period" id="certification_period" class="form-control" value="{{ $course->certification_period }}"  @if($course->coursecertify=='1') required @endif></td>
            </tr>
            <tr class="certificaterow"  @if($course->coursecertify!='1') style="display:none;" @endif>
                <td>Course Certificate Point 1</td>
                <td><input type="text" name="certificate1" class="form-control" value="{{ $course->keypoint1 }}"></td>
            </tr>
            <tr class="certificaterow" @if($course->coursecertify!='1') style="display:none;" @endif>
                <td>Course Certificate Point 2</td>
                <td><input type="text" name="certificate2" class="form-control" value="{{ $course->keypoint2 }}"></td>
            </tr>
            <tr class="certificaterow" @if($course->coursecertify!='1') style="display:none;" @endif>
                <td>Course Certificate Point 3</td>
                <td><input type="text" name="certificate3" class="form-control" value="{{ $course->keypoint3 }}"></td>
            </tr>
            <tr class="certificaterow" @if($course->coursecertify!='1') style="display:none;" @endif>
                <td>Course Certificate Point 4</td>
                <td><input type="text" name="certificate4" class="form-control" value="{{ $course->keypoint4 }}"></td>
            </tr>
            <tr class="certificaterow" @if($course->coursecertify!='1') style="display:none;" @endif>
                <td>Course Certificate Point 5</td>
                <td><input type="text" name="certificate5" class="form-control" value="{{ $course->keypoint5 }}"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit" class="btn btn-success" value="Update Course"></td>
            </tr>
        </table>
    </form>
@stop
@section('javascript')
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
