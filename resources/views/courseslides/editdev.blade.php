@extends('appdev', ['user' => $user])

@section('content')<a href="/courseslides/{{ $course->courseid }}" class="btn btn-success float-end">Back to course slides</a>
<h1>Course Slides - {{ $course->course_title }}</h1>
@if (session('status') === 'courseslideupdated')
    <br><br>
    <p class="p-3 mb-2 bg-success text-white">Slide Updated</p>
@endif
<form method="POST" action="{{ route('courseslides.update', ['id' => $course->courseid,'slideid' => $courseslide->courseslideid]) }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <table class="table">
        <tr>
            <td>Internal Title:</td>
            <td><input type="text" name="slide_title" class="form-control" value="{{ $courseslide->slide_title }}"></td>
        </tr>
        <tr>
            <td>Audio Description: @if($courseslide->audiofile !='')<br><a href="{{ asset('slideaudio/'.$courseslide->audiofile) }}" target="_blank">Current file</a> (remove)@endif</td>
            <td><input type="file" name="audiodescription" class="form-control"></td>
        </tr>
        <tr>
            <td>Slide Type: </td>
            <td><select name="slide_type" class="form-control" id="slide_type">
                    <option value="1" @if($courseslide->slide_type=='1') selected @endif>HTML Slide</option>
                    <option value="2" @if($courseslide->slide_type=='2') selected @endif>Image Only</option>
                    <option value="3" @if($courseslide->slide_type=='3') selected @endif>Question Only</option>
                    <option value="5" @if($courseslide->slide_type=='5') selected @endif>Question with HTML</option>
                    <option value="6" @if($courseslide->slide_type=='6') selected @endif>Question with Image</option>
                    <option value="4" @if($courseslide->slide_type=='4') selected @endif>Confirmation Slide</option>
                </select></td>
        </tr>
    </table>


    <div id="slidehtml" @if(($courseslide->slide_type!='1')&&($courseslide->slide_type!='5')) style="display:none" @endif>
        <table class="table">
            <tr>
                <td>Insert HTML Template</td>
                <td><select name="htmltemplate" id="htmltemplate" class="form-control">
                        <option value="0">Select</option>
                        <option value="1">Top image with text below</option>
                        <option value="2">Left images with text to the right</option>
                        <option value="3">Right images with text to the left</option>
                        <option value="4">Text at the top and image below</option>
                        <option value="5">No Image</option>
                    </select>
                    <button class="btn btn-primary" id="htmltemplatechange">Insert Template</button></td>
            </tr>
        </table>
        <textarea name="slidehtml" class="ckeditor" id="editor" style="height:800px;">{{ $courseslide->slidehtml }}</textarea>
    </div>
    <div id="slideimage" @if(($courseslide->slide_type!='2')&&($courseslide->slide_type!='6')) style="display:none" @endif>
        <input type="file" name="slideimage" class="form-control">
        @if($courseslide->slide_image!='')
            <img src="{{ asset('slideimage/'.$courseslide->slide_image) }}">
        @endif
    </div>
    <div id="slidequestion" @if(($courseslide->slide_type!='3')&&($courseslide->slide_type!='5')&&($courseslide->slide_type!='6')) style="display:none" @endif>
        <input type="radio" name="slidequestiontype" value="1" class="questiontype" @if($courseslide->slide_questiontype=='1') checked @endif> Non-test question - multi-choice - correct answer shown on first attempt<br>
        <input type="radio" name="slidequestiontype" value="2" class="questiontype" @if($courseslide->slide_questiontype=='2') checked @endif> Non-test question - multi-choice - correct answer not shown until correct<br>
        <input type="radio" name="slidequestiontype" value="4" class="questiontype" @if($courseslide->slide_questiontype=='4') checked @endif> Non-test question - participant enters text answer - logged by not marked<br>
        <input type="radio" name="slidequestiontype" value="3" class="questiontype" @if($courseslide->slide_questiontype=='3') checked @endif> Test question - multi-choice - correct answer not shown, score calculated and shown at the end of the test<br>
        <input type="radio" name="slidequestiontype" value="5" class="questiontype" @if($courseslide->slide_questiontype=='5') checked @endif> Test question - participant enters text answer - needs marking<br>

        <br><br>
        <table class="table table-striped">
            <tr class="multi-choice">
                <td></td>
                <td></td>
                <td>Correct Answer</td>
            </tr>
            <tr>
                <td>Question</td>
                <td><input type="text" name="question" value="{{ $courseslide->questiontext }}" class="form-control"></td>
                <td></td>
            </tr>
            <tr class="textscore" @if($courseslide->slide_questiontype!='5') style="display:none" @endif>
                <td>Marking Score</td>
                <td><select name="slide_points" class="form-control">
                        <option value="0" @if($courseslide->slide_points=='0') selected @endif>0</option>
                        <option value="1" @if($courseslide->slide_points=='1') selected @endif>1</option>
                        <option value="2" @if($courseslide->slide_points=='2') selected @endif>2</option>
                        <option value="3" @if($courseslide->slide_points=='3') selected @endif>3</option>
                        <option value="4" @if($courseslide->slide_points=='4') selected @endif>4</option>
                        <option value="5" @if($courseslide->slide_points=='5') selected @endif>5</option>
                        <option value="6" @if($courseslide->slide_points=='6') selected @endif>6</option>
                        <option value="7" @if($courseslide->slide_points=='7') selected @endif>7</option>
                        <option value="8" @if($courseslide->slide_points=='8') selected @endif>8</option>
                        <option value="9" @if($courseslide->slide_points=='9') selected @endif>9</option>
                        <option value="10" @if($courseslide->slide_points=='10') selected @endif>10</option>
                    </select></td>
                <td></td>
            </tr>

            <tr class="multi-choice" @if(($courseslide->slide_questiontype=='4')||($courseslide->slide_questiontype=='5')) style="display:none" @endif>
                <td>Answer 1:</td>
                <td><input type="text" name="answer1" value="{{ $courseslide->answertext1 }}" class="form-control"></td>
                <td><input type="radio" name="correctanswer" value="1" @if($courseslide->correct_answer=='1') checked @endif></td>
            </tr>
            <tr class="multi-choice" @if(($courseslide->slide_questiontype=='4')||($courseslide->slide_questiontype=='5')) style="display:none" @endif>
                <td>Answer 2:</td>
                <td><input type="text" name="answer2" value="{{ $courseslide->answertext2 }}" class="form-control"></td>
                <td><input type="radio" name="correctanswer" value="2" @if($courseslide->correct_answer=='2') checked @endif></td>
            </tr>
            <tr class="multi-choice" @if(($courseslide->slide_questiontype=='4')||($courseslide->slide_questiontype=='5')) style="display:none" @endif>
                <td>Answer 3:</td>
                <td><input type="text" name="answer3" value="{{ $courseslide->answertext3 }}" class="form-control"></td>
                <td><input type="radio" name="correctanswer" value="3" @if($courseslide->correct_answer=='3') checked @endif></td>
            </tr>
            <tr class="multi-choice" @if(($courseslide->slide_questiontype=='4')||($courseslide->slide_questiontype=='5')) style="display:none" @endif>
                <td>Answer 4:</td>
                <td><input type="text" name="answer4" value="{{ $courseslide->answertext4 }}" class="form-control"></td>
                <td><input type="radio" name="correctanswer" value="4" @if($courseslide->correct_answer=='4') checked @endif></td>
            </tr>
        </table>
    </div>
    <div id="slideconfirmation" @if($courseslide->slide_type!='4') style="display:none" @endif>
        <textarea name="slidehtml2" class="ckeditor" id="editor2" style="height:800px;">{{ $courseslide->slidehtml }}</textarea>
    </div>
    <input type="submit" name="submit" value="Save Slide" class="btn btn-success">
</form>
@stop
@section('javascript')

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/super-build/ckeditor.js"></script>

    <script type="text/javascript">
        const imageDowncast = (dispatcher) => {
            // Add a listener for the 'insert:image' event
            dispatcher.on('insert:image', (evt, data, conversionApi) => {
                // Get the view element (DOM) representing the image widget
                const imageElement = conversionApi.mapper.toViewElement(data.item);

                // Add your downcast logic here, for example, setting a class
                imageElement.classes.add('your-custom-image-class');
            });
        };

        CKEDITOR.ClassicEditor
            .create( document.querySelector( '#editor','' ), {
                // Editor configuration.
                htmlSupport: {
                    allow: [ {
                        name: 'div',styles:true,classes:true
                    }, ],
                    disallow: [ /* HTML features to disallow */ ]
                },
                ckfinder: {
                    uploadUrl: '/ckfinder/connector?command=QuickUpload&type=Files&responseType=json',
                    openerMethod: 'popup',
                },
                mediaEmbed: {
                    previewsInData: true
                },
                toolbar: {
                    items: [
                        'heading','|','bold', 'italic', 'underline','bulletedList', 'numberedList','alignment','|','fontBackgroundColor','fontColor','fontFamily','fontSize','highlight','|','link','ckfinder', 'insertTable', 'mediaEmbed','undo', 'redo','horizontalLine','sourceEditing'
                    ],
                },
                image: {
                    toolbar: ['imageTextAlternative']
                },

                removePlugins: [
                    // These two are commercial, but you can try them out without registering to a trial.
                    // 'ExportPdf',
                    // 'ExportWord',
                    'EasyImage',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'FormatPainter',
                    'SlashCommand',
                    'TableOfContents',
                    'Template',
                    'DocumentOutline',
                    'PasteFromOffice',
                    'PasteFromOfficeEnhanced',
                    'WProofreader',
                    // Careful, with the Mathtype plugin CKEditor will not load when loading this sample
                    // from a local file system (file://) - load this site via HTTP server if you enable MathType
                    'MathType'
                ]
            } )
            .then(editor => {
            })
            .then( editor => {
                window.editor = editor;
            } )
            .catch();
    </script>

    @include('ckfinder::setup')

    <script type="text/javascript">
        $(".questiontype").click(function(){
            if(($(this).val()=='4')||(($(this).val()=='5'))){
                $(".multi-choice").hide();
                if($(this).val()=='5') {
                    $(".textscore").show();
                }else{
                    $(".textscore").hide();
                }
            }else{
                $(".multi-choice").show();
                $(".textscore").hide();
            }
        });

        $("#slide_type").change(function(){
            if($("#slide_type").val()=='1'){
                $("#slidehtml").show();
                $("#slideimage").hide();
                $("#slidequestion").hide();
                $("#slideconfirmation").hide();
            }
            if($("#slide_type").val()=='2'){
                $("#slidehtml").hide();
                $("#slideimage").show();
                $("#slidequestion").hide();
                $("#slideconfirmation").hide();
            }
            if($("#slide_type").val()=='3'){
                $("#slidehtml").hide();
                $("#slideimage").hide();
                $("#slidequestion").show();
                $("#slideconfirmation").hide();
            }
            if($("#slide_type").val()=='4'){
                $("#slidehtml").hide();
                $("#slideimage").hide();
                $("#slidequestion").hide();
                $("#slideconfirmation").show();
            }
            if($("#slide_type").val()=='5'){
                $("#slidehtml").show();
                $("#slideimage").hide();
                $("#slidequestion").show();
                $("#slideconfirmation").hide();
            }
            if($("#slide_type").val()=='6'){
                $("#slidehtml").hide();
                $("#slideimage").show();
                $("#slidequestion").show();
                $("#slideconfirmation").hide();
            }
        });
        $("#htmltemplatechange").click(function(e){
            e.preventDefault();
            if($("#htmltemplate").val()=='1'){
                const domEditableElement = document.querySelector( '.ck-editor__editable' );
                const editorInstance = domEditableElement.ckeditorInstance;
                editorInstance.setData( '<div class="row"><div class="col-sm-12"><img src="/img/imageplaceholder.jpg" class="img-fluid mx-auto"></div><div class="col-sm-12"><h1>Slide Title</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div></div>' );
            }
            if($("#htmltemplate").val()=='2'){
                const domEditableElement = document.querySelector( '.ck-editor__editable' );
                const editorInstance = domEditableElement.ckeditorInstance;
                editorInstance.setData( '<div class="row"><div class="col-sm-6"><img src="/img/imageplaceholder.jpg" class="img-fluid"><img src="/img/imageplaceholder.jpg" class="img-fluid"></div><div class="col-sm-6"><h1>Slide Title</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div></div>' );
            }
            if($("#htmltemplate").val()=='3'){
                const domEditableElement = document.querySelector( '.ck-editor__editable' );
                const editorInstance = domEditableElement.ckeditorInstance;
                editorInstance.setData( '<div class="row"><div class="col-sm-6"><h1>Slide Title</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div><div class="col-sm-6"><img src="/img/imageplaceholder.jpg" class="img-fluid"><img src="/img/imageplaceholder.jpg" class="img-fluid"></div></div>' );
            }
            if($("#htmltemplate").val()=='4'){
                const domEditableElement = document.querySelector( '.ck-editor__editable' );
                const editorInstance = domEditableElement.ckeditorInstance;
                editorInstance.setData( '<div class="row"><div class="col-sm-12"><h1>Slide Title</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div><div class="col-sm-12"><img src="/img/imageplaceholder.jpg" class="img-fluid"></div></div>' );
            }
            if($("#htmltemplate").val()=='5'){
                const domEditableElement = document.querySelector( '.ck-editor__editable' );
                const editorInstance = domEditableElement.ckeditorInstance;
                editorInstance.setData( '<div class="row"><div class="col-sm-12"><h1>Slide Title</h1><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p></div></div>' );
            }
        });
    </script>

@stop
