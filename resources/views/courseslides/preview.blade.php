@extends('frontapp')

@section('content')

@if(($courseslide->slide_type=='1')||($courseslide->slide_type=='5'))
{!!  $courseslide->slidehtml !!}
@endif
@if(($courseslide->slide_type=='2')||($courseslide->slide_type=='6'))
    <img src="/slideimage/{{ $courseslide->slide_image }}" class="img-fluid">
@endif
@if(($courseslide->slide_type=='3')||($courseslide->slide_type=='5')||($courseslide->slide_type=='6'))
    @if(($courseslide->slide_questiontype=='1')||($courseslide->slide_questiontype=='2')||($courseslide->slide_questiontype=='3'))
        <h1>Question</h1>
        <div id="answerresponse" style="display:none;"></div>
        <strong>{{ $courseslide->questiontext }}</strong><br><br>
        There are {{ $courseslide->correct_answernumber }} correct answer(s).<br><br>
        @if($courseslide->answertext1!='')
            <div style="clear:both;width:100%;padding:10px; border:1px solid #666" class="questionanswer" id="answer1div">{{ $courseslide->answertext1 }}<input type="checkbox" name="answer1Checkbox" id="answer1Checkbox" value="1" style="display:none;" class="limitbox"></div>
        @endif
        @if($courseslide->answertext2!='')
            <div style="clear:both;width:100%;padding:10px; border:1px solid #666" class="questionanswer"  id="answer2div">{{ $courseslide->answertext2 }}<input type="checkbox" name="answer2Checkbox" id="answer2Checkbox" value="1" style="display:none;" class="limitbox"></div>
        @endif
        @if($courseslide->answertext3!='')
            <div style="clear:both;width:100%;padding:10px; border:1px solid #666" class="questionanswer" id="answer3div">{{ $courseslide->answertext3 }}<input type="checkbox" name="answer3Checkbox" id="answer3Checkbox" value="1" style="display:none;" class="limitbox"></div>
        @endif
        @if($courseslide->answertext4!='')
            <div style="clear:both;width:100%;padding:10px; border:1px solid #666" class="questionanswer" id="answer4div">{{ $courseslide->answertext4 }}<input type="checkbox" name="answer4Checkbox" id="answer4Checkbox" value="1" style="display:none;" class="limitbox"></div>
        @endif
        @if($courseslide->answertext5!='')
            <div style="clear:both;width:100%;padding:10px; border:1px solid #666" class="questionanswer" id="answer5div">{{ $courseslide->answertext5 }}<input type="checkbox" name="answer5Checkbox" id="answer5Checkbox" value="1" style="display:none;" class="limitbox"></div>
        @endif
        @if($courseslide->answertext6!='')
            <div style="clear:both;width:100%;padding:10px; border:1px solid #666" class="questionanswer" id="answer6div">{{ $courseslide->answertext6 }}<input type="checkbox" name="answer6Checkbox" id="answer6Checkbox" value="1" style="display:none;" class="limitbox"></div>
        @endif
        <div class="btn btn-success float-end submitanswers">SUBMIT ANSWER(S)</div>
    @elseif(($courseslide->slide_questiontype=='4')||($courseslide->slide_questiontype=='5'))
        <h1>Question</h1>
        <strong>{{ $courseslide->questiontext }}</strong><br><br>
        <strong>Answer:</strong>
        <textarea class="form-control" name="answer" required rows="10"></textarea>
        <input type="submit" name="submit" value="SUBMIT ANSWER" class="btn btn-success float-end">
        @endif
    @endif
@if($courseslide->slide_type=='4')
    {!!  $courseslide->slidehtml  !!}<br>
    <input type="checkbox" name="confirm" value="1" required> {{ $courseslide->confirmation_text }}<br>
    <input type="submit" name="submit" value="CONTINUE" class="btn btn-success float-end">
    @endif

@stop
@section('javascript')
    @if(($courseslide->slide_questiontype=='1')||($courseslide->slide_questiontype=='2')||($courseslide->slide_questiontype=='3')||($courseslide->slide_questiontype=='4'))
        <script type="text/javascript">
            $('.questionanswer').on('click', function(e){
                if((e.target.id=='answer1div')&&($('#answer1Checkbox').is(':checked'))){
                    console.log("answer11 is checked");
                    $('#answer1Checkbox').prop('checked', false);
                    $(this).toggleClass('bg-secondary');
                }else if((e.target.id=='answer1div')&&(!$('#answer1Checkbox').is(':checked'))){
                    $('#answer1Checkbox').prop('checked', true);
                    $(this).toggleClass('bg-secondary');
                }
                if((e.target.id=='answer2div')&&($('#answer2Checkbox').is(':checked'))){
                    console.log("answer11 is checked");
                    $('#answer2Checkbox').prop('checked', false);
                    $(this).toggleClass('bg-secondary');
                }else if((e.target.id=='answer2div')&&(!$('#answer2Checkbox').is(':checked'))){
                    $('#answer2Checkbox').prop('checked', true);
                    $(this).toggleClass('bg-secondary');
                }
                if((e.target.id=='answer3div')&&($('#answer3Checkbox').is(':checked'))){
                    console.log("answer11 is checked");
                    $('#answer3Checkbox').prop('checked', false);
                    $(this).toggleClass('bg-secondary');
                }else if((e.target.id=='answer3div')&&(!$('#answer3Checkbox').is(':checked'))){
                    $('#answer3Checkbox').prop('checked', true);
                    $(this).toggleClass('bg-secondary');
                }
                if((e.target.id=='answer4div')&&($('#answer4Checkbox').is(':checked'))){
                    console.log("answer11 is checked");
                    $('#answer4Checkbox').prop('checked', false);
                    $(this).toggleClass('bg-secondary');
                }else if((e.target.id=='answer4div')&&(!$('#answer4Checkbox').is(':checked'))){
                    $('#answer4Checkbox').prop('checked', true);
                    $(this).toggleClass('bg-secondary');
                }
                if((e.target.id=='answer5div')&&($('#answer5Checkbox').is(':checked'))){
                    console.log("answer11 is checked");
                    $('#answer5Checkbox').prop('checked', false);
                    $(this).toggleClass('bg-secondary');
                }else if((e.target.id=='answer5div')&&(!$('#answer5Checkbox').is(':checked'))){
                    $('#answer5Checkbox').prop('checked', true);
                    $(this).toggleClass('bg-secondary');
                }
                if((e.target.id=='answer6div')&&($('#answer6Checkbox').is(':checked'))){
                    console.log("answer11 is checked");
                    $('#answer6Checkbox').prop('checked', false);
                    $(this).toggleClass('bg-secondary');
                }else if((e.target.id=='answer6div')&&(!$('#answer6Checkbox').is(':checked'))){
                    $('#answer6Checkbox').prop('checked', true);
                    $(this).toggleClass('bg-secondary');
                }

                var checkedCheckboxes = $('.limitbox:checked');
                var maxCheckboxes = {{ $courseslide->correct_answernumber }};

                if (checkedCheckboxes.length > maxCheckboxes) {

                    if(e.target.id=='answer1div'){$('#answer1Checkbox').prop('checked', false)}
                    if(e.target.id=='answer2div'){$('#answer2Checkbox').prop('checked', false)}
                    if(e.target.id=='answer3div'){$('#answer3Checkbox').prop('checked', false)}
                    if(e.target.id=='answer4div'){$('#answer4Checkbox').prop('checked', false)}
                    if(e.target.id=='answer5div'){$('#answer5Checkbox').prop('checked', false)}
                    if(e.target.id=='answer6div'){$('#answer6Checkbox').prop('checked', false)}
                    $(this).toggleClass('bg-secondary');
                    alert("You can only select {{ $courseslide->correct_answernumber }} answers");
                }
            });


            $(".submitanswers").click(function(){
                $(".submitanswers").hide();
                @if(($courseslide->slide_questiontype=='1')||($courseslide->slide_questiontype=='2')||($courseslide->slide_questiontype=='4'))
                var checkedCheckboxes = $('.limitbox:checked');
                var maxCheckboxes = {{ $courseslide->correct_answernumber }};

                if (checkedCheckboxes.length != maxCheckboxes) {
                    alert("You must select {{ $courseslide->correct_answernumber }} answer(s)");
                    $(".submitanswers").show();
                }else {
                    @endif
                    var CSRF_TOKENX = $('meta[name="csrf-token"]').attr('content');
                    @if($courseslide->answertext1!='')
                    if ($('#answer1Checkbox').is(':checked')) {
                        var answercode1 = 1;
                    } else {
                        var answercode1 = 0;
                    }
                    @else
                    var answercode1 = '0';
                    @endif
                        @if($courseslide->answertext2!='')
                    if ($('#answer2Checkbox').is(':checked')) {
                        var answercode2 = 1;
                    } else {
                        var answercode2 = 0;
                    }
                    @else
                    var answercode2 = '';
                    @endif
                        @if($courseslide->answertext3!='')
                    if ($('#answer3Checkbox').is(':checked')) {
                        var answercode3 = 1;
                    } else {
                        var answercode3 = 0;
                    }
                    @else
                    var answercode3 = '';
                    @endif
                        @if($courseslide->answertext4!='')
                    if ($('#answer4Checkbox').is(':checked')) {
                        var answercode4 = 1;
                    } else {
                        var answercode4 = 0;
                    }
                    @else
                    var answercode4 = '';
                    @endif
                        @if($courseslide->answertext5!='')
                    if ($('#answer5Checkbox').is(':checked')) {
                        var answercode5 = 1;
                    } else {
                        var answercode5 = 0;
                    }
                    @else
                    var answercode5 = '';
                    @endif
                        @if($courseslide->answertext6!='')
                    if ($('#answer6Checkbox').is(':checked')) {
                        var answercode6 = 1;
                    } else {
                        var answercode6 = 0;
                    }
                    @else
                    var answercode6 = '';
                    @endif

                    $("#answerresponse").hide();
                    $(this).toggleClass("alert-primary");
                    $.ajax({
                        url: "/c_submitadmin/{{ $course->courseid }}/{{ $courseslide->slide_key }}",
                        type: "post",
                        data: {
                            _token: CSRF_TOKENX,
                            answercode1: answercode1,
                            answercode2: answercode2,
                            answercode3: answercode3,
                            answercode4: answercode4,
                            answercode5: answercode5,
                            answercode6: answercode6
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            $("#answerresponse").removeClass("alert-danger");
                            //console.log("response"+response);
                            if (response.status == 'success') {
                                @if(($courseslide->slide_questiontype=='1')||($courseslide->slide_questiontype=='2'))
                                console.log("Success:", response);
                                var answersplit = response.message.split('!!');
                                var answerscorrect = answersplit[1].split(';');

                                if (answerscorrect.indexOf('1') !== -1) {
                                    console.log("ErrorAnswer:1");
                                    $('#answer1div').addClass("alert-success");
                                    $('#answer1div').removeClass("bg-secondary");
                                }
                                if (answerscorrect.indexOf('2') !== -1) {
                                    console.log("ErrorAnswer:2");
                                    $('#answer2div').addClass("alert-success");
                                    $('#answer2div').removeClass("bg-secondary");
                                }
                                if (answerscorrect.indexOf('3') !== -1) {
                                    console.log("ErrorAnswer:3");
                                    $('#answer3div').addClass("alert-success");
                                    $('#answer3div').removeClass("bg-secondary");
                                }
                                if (answerscorrect.indexOf('4') !== -1) {
                                    console.log("ErrorAnswer:4");
                                    $('#answer4div').addClass("alert-success");
                                    $('#answer4div').removeClass("bg-secondary");
                                }
                                if (answerscorrect.indexOf('5') !== -1) {
                                    $('#answer5div').addClass("alert-success");
                                    $('#answer5div').removeClass("bg-secondary");
                                }
                                if (answerscorrect.indexOf('6') !== -1) {
                                    $('#answer6div').addClass("alert-success");
                                    $('#answer6div').removeClass("bg-secondary");
                                }

                                $("#answerresponse").toggleClass("alert-success");
                                $("#answerresponse").show();
                                $("#answerresponse").html("Your answer is correct. You will now be redirected to the next question.");

                                @endif
                                @if(($courseslide->slide_questiontype=='3')||($courseslide->slide_questiontype=='4')||($courseslide->slide_questiontype=='5'))
                                console.log("Success:", response);
                                $("#answerresponse").toggleClass("alert-primary");
                                $("#answerresponse").show();
                                $("#answerresponse").html("Your answer has been logged. You will now be redirected to the next question.");

                                @endif
                            } else {
                                @if($courseslide->slide_questiontype=='1')
                                console.log("Error:", response);
                                var answersplit = response.message.split('!!');
                                console.log("Error1:", answersplit[0] + "**" + answersplit[1]);
                                var answersplit1 = answersplit[1];
                                var answersplittext = answersplit1.split('_');
                                console.log("Error2:", answersplittext[0]);
                                var answerscorrect = answersplittext[1].split(';');
                                console.log("Error3:", answerscorrect[0]);
                                //    $("#"+answercode).toggleClass("alert-danger");
                                console.log(answerscorrect);
                                if (answerscorrect.indexOf('1') !== -1) {
                                    console.log("ErrorAnswer:1");
                                    $('#answer1div').addClass("alert-success");
                                    $('#answer1div').removeClass("bg-secondary");
                                } else {
                                    if (answercode1 == '1') {
                                        $('#answer1div').addClass("alert-danger");
                                        $('#answer1div').removeClass("bg-secondary");
                                    }
                                    console.log("ErrorNotAnswer:1");
                                }
                                if (answerscorrect.indexOf('2') !== -1) {
                                    console.log("ErrorAnswer:2");
                                    $('#answer2div').addClass("alert-success");
                                    $('#answer2div').removeClass("bg-secondary");
                                } else {
                                    if (answercode2 == '1') {
                                        $('#answer2div').addClass("alert-danger");
                                        $('#answer2div').removeClass("bg-secondary");
                                    }
                                    console.log("ErrorNotAnswer:2");
                                }
                                if (answerscorrect.indexOf('3') !== -1) {
                                    console.log("ErrorAnswer:3");
                                    $('#answer3div').addClass("alert-success");
                                    $('#answer3div').removeClass("bg-secondary");
                                } else {
                                    if (answercode3 == '1') {
                                        $('#answer3div').addClass("alert-danger");
                                        $('#answer3div').removeClass("bg-secondary");
                                    }
                                    console.log("ErrorNotAnswer:3");
                                }
                                if (answerscorrect.indexOf('4') !== -1) {
                                    console.log("ErrorAnswer:4");
                                    $('#answer4div').addClass("alert-success");
                                    $('#answer4div').removeClass("bg-secondary");
                                } else {
                                    if (answercode4 == '1') {
                                        $('#answer4div').addClass("alert-danger");
                                        $('#answer4div').removeClass("bg-secondary");
                                    }
                                    console.log("ErrorNotAnswer:4");
                                }
                                if (answerscorrect.indexOf('5') !== -1) {
                                    $('#answer5div').addClass("alert-success");
                                    $('#answer5div').removeClass("bg-secondary");
                                } else {
                                    if (answercode5 == '1') {
                                        $('#answer5div').addClass("alert-danger");
                                        $('#answer5div').removeClass("bg-secondary");
                                    }
                                    console.log("ErrorNotAnswer:5");
                                }
                                if (answerscorrect.indexOf('6') !== -1) {
                                    $('#answer6div').addClass("alert-success");
                                    $('#answer6div').removeClass("bg-secondary");
                                } else {
                                    if (answercode6 == '1') {
                                        $('#answer6div').addClass("alert-danger");
                                        $('#answer6div').removeClass("bg-secondary");
                                    }
                                    console.log("ErrorNotAnswer:6");
                                }
                                console.log("Correct answer:" + answersplittext[1]);
                                $("#answerresponse").toggleClass("alert-danger");
                                $("#answerresponse").show();
                                $("#answerresponse").html("Your answer is in-correct. The correct answer will now be shown and you will be redirected to the next question");

                                @endif
                                @if($courseslide->slide_questiontype=='2')
                                //    $("#"+answercode).toggleClass("alert-danger");
                                console.log(response.message);
                                var answersplit = response.message.split('!!');
                                var correctwrongsplit = answersplit[2].split('_');
                                $("#answerresponse").toggleClass("alert-danger");
                                $("#answerresponse").show();
                                $("#answerresponse").html("Your answer is in-correct. You got "+correctwrongsplit[1]+" of "+correctwrongsplit[0]+" correct. Please try again.");

                                $(".questionanswer").removeClass('bg-secondary');
                                $('#answer1Checkbox').prop('checked', false);
                                $('#answer2Checkbox').prop('checked', false);
                                $('#answer3Checkbox').prop('checked', false);
                                $('#answer4Checkbox').prop('checked', false);
                                $('#answer5Checkbox').prop('checked', false);
                                $('#answer6Checkbox').prop('checked', false);

                                $(".submitanswers").show();
                                @endif
                            }
                        },
                        error: function (xhr, status, error) {
                            // Handle the error response
                            console.log("Error:", xhr.responseText);
                            // Display an error message to the user or take other actions
                        }
                    });
                    @if(($courseslide->slide_questiontype=='1')||($courseslide->slide_questiontype=='2')||($courseslide->slide_questiontype=='4'))
                }
                @endif
            });
        </script>
    @endif
@stop
