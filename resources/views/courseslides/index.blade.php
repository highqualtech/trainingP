@extends('app', ['user' => $user])

@section('content')<form method="POST" action="{{ route('courseslides.create', ['id' => $course->courseid]) }}">
    @csrf
    @method('POST')
    <button type="submit" class="btn btn-warning float-end">Add new slide</button>
</form>
    <h1>Course Slides - {{ $course->course_title }}</h1>
@if (session('status') === 'courseslidedeleted')
    <br><br>
    <p class="p-3 mb-2 bg-success text-white">Slide Deleted</p>
@endif
    <ul class="list-unstyled" id="post_list_1">
    @foreach($courseslides as $cs)

            <li data-post-id="{{ $cs->courseslideid }}" style="width:100%">
                <div class="li-post-group">
                    <table class="table">
                        <tr>
                            <td>{{ $cs->slide_title }} (SLIDE ID {{ $cs->courseslideid }})</td>
                            <td style="width:150px" class="text-center"><a href="/courseslides/{{ $course->courseid }}/{{ $cs->courseslideid }}" class="btn btn-primary">Edit Slide</a></td>
                            <td style="width:150px" class="text-center"><a href="/courseslidepreview/{{ $course->courseid }}/{{ $cs->courseslideid }}" class="btn btn-success" target="_blank">Preview Slide</a></td>
                            <td style="width:150px" class="text-center">
                                <form method="POST" action="{{ route('courseslides.destroy', ['id' => $course->courseid, 'slideid'=>$cs->courseslideid]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete Slide</button>
                                </form>
                                </td>
                        </tr>
                    </table>


                </div>
            </li>

    @endforeach
    </ul>

@stop
@section('javascript')
    <script
        src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"
        integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0="
        crossorigin="anonymous"></script>
<script type="text/javascript">
    $( "#post_list_1" ).sortable({
        placeholder : "ui-state-highlight",
        update  : function(event, ui)
        {
            var post_order_ids = new Array();
            $('#post_list_1 li').each(function(){
                post_order_ids.push($(this).data("post-id"));
            });
            var CSRF_TOKENX = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url:"/updateslidesort/{{ $course->courseid }}",
                method:"POST",
                data:{_token: CSRF_TOKENX,post_order_ids:post_order_ids},
                success:function(data)
                {
                    if(data){
                        $(".alert-danger").hide();
                        $(".alert-success ").show();
                    }else{
                        $(".alert-success").hide();
                        $(".alert-danger").show();
                    }
                }
            });
        }
    });
</script>
@stop
