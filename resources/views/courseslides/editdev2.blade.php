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

    <?php
    $volumeId = 'l1_';
    $path = '/var/www/vhosts/'; // without root path
//$path = 'path\\to\\target'; // use \ on windows server
    $hash = $volumeId . rtrim(strtr(base64_encode($path), '+/=', '-_.'), '.');
   // echo "HASH:".$hash;
    ?>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.2/super-build/ckeditor.js"></script>
    <!-- elFinder -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.min.css"/>
    <script src="/packages/barryvdh/elfinder/js/elfinder.full.js"></script>
    <script type="text/javascript">
        var editorconfig = {
            // Editor configuration.
            htmlSupport: {
                allow: [ {
                    name: 'div',styles:true,classes:true,
                },{name: 'img',styles:true,classes:true,} ],
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
        };

        // elfinder folder hash of the destination folder to be uploaded in this CKeditor 5
        const uploadTargetHash = '';

        // elFinder connector URL
        //const connectorUrl = '/elfinder/ckeditor';
        const connectorUrl = '/elfinder/connector';

        //https://github.com/Studio-42/elFinder/wiki/Integration-with-CKEditor-5

        CKEDITOR.ClassicEditor
            .create( document.querySelector( '#editor','' ), editorconfig)
            .then( editor => {

                const ckf = editor.commands.get('ckfinder'),
                    fileRepo = editor.plugins.get('FileRepository'),
                    ntf = editor.plugins.get('Notification'),
                    i18 = editor.locale.t,
                    // Insert images to editor window
                    insertImages = urls => {
                        const imgCmd = editor.commands.get('imageUpload');
                        if (!imgCmd.isEnabled) {
                            ntf.showWarning(i18('Could not insert image at the current position.'), {
                                title: i18('Inserting image failed'),
                                namespace: 'ckfinder'
                            });
                            return;
                        }
                        editor.execute('imageInsert', { source: urls });
                    },
                    // To get elFinder instance
                    getfm = open => {
                        return new Promise((resolve, reject) => {
                            // Execute when the elFinder instance is created
                            const done = () => {
                                if (open) {
                                    // request to open folder specify
                                    if (!Object.keys(_fm.files()).length) {
                                        // when initial request
                                        _fm.one('open', () => {
                                            _fm.file(open)? resolve(_fm) : reject(_fm, 'errFolderNotFound');
                                        });
                                    } else {
                                        // elFinder has already been initialized
                                        new Promise((res, rej) => {
                                            if (_fm.file(open)) {
                                                res();
                                            } else {
                                                // To acquire target folder information
                                                _fm.request({cmd: 'parents', target: open}).done(e =>{
                                                    _fm.file(open)? res() : rej();
                                                }).fail(() => {
                                                    rej();
                                                });
                                            }
                                        }).then(() => {
                                            // Open folder after folder information is acquired
                                            _fm.exec('open', open).done(() => {
                                                resolve(_fm);
                                            }).fail(err => {
                                                reject(_fm, err? err : 'errFolderNotFound');
                                            });
                                        }).catch((err) => {
                                            reject(_fm, err? err : 'errFolderNotFound');
                                        });
                                    }
                                } else {
                                    // show elFinder manager only
                                    resolve(_fm);
                                }
                            };

                            // Check elFinder instance
                            if (_fm) {
                                // elFinder instance has already been created
                                done();
                            } else {
                                // To create elFinder instance
                                _fm = $('<div/>').dialogelfinder({
                                    // dialog title
                                    title : 'File Manager',
                                    // connector URL
                                    url : connectorUrl,
                                    // start folder setting
                                    startPathHash : open? open : void(0),
                                    // Set to do not use browser history to un-use location.hash
                                    useBrowserHistory : false,
                                    // Disable auto open
                                    autoOpen : false,
                                    // elFinder dialog width
                                    width : '80%',
                                    // set getfile command options
                                    commandsOptions : {
                                        getfile: {
                                            oncomplete : 'close',
                                            multiple : true
                                        }
                                    },
                                    // Insert in CKEditor when choosing files
                                    getFileCallback : (files, fm) => {
                                        let imgs = [];
                                        fm.getUI('cwd').trigger('unselectall');
                                        $.each(files, function(i, f) {
                                            if (f && f.mime.match(/^image\//i)) {
                                                imgs.push(fm.convAbsUrl(f.url));
                                            } else {
                                                editor.execute('link', fm.convAbsUrl(f.url));
                                            }
                                        });
                                        if (imgs.length) {
                                            insertImages(imgs);
                                        }
                                    }
                                }).elfinder('instance');
                                done();
                            }
                        });
                    };

                // elFinder instance
                let _fm;

                if (ckf) {
                    // Take over ckfinder execute()
                    ckf.execute = () => {
                        getfm().then(fm => {
                            fm.getUI().dialogelfinder('open');
                        });
                    };
                }

                // Make uploader
                const uploder = function(loader) {
                    let upload = function(file, resolve, reject) {
                        getfm(uploadTargetHash).then(fm => {
                            let fmNode = fm.getUI();
                            fmNode.dialogelfinder('open');
                            fm.exec('upload', {files: [file], target: uploadTargetHash}, void(0), uploadTargetHash)
                                .done(data => {
                                    if (data.added && data.added.length) {
                                        fm.url(data.added[0].hash, { async: true }).done(function(url) {
                                            resolve({
                                                'default': fm.convAbsUrl(url)
                                            });
                                            fmNode.dialogelfinder('close');
                                        }).fail(function() {
                                            reject('errFileNotFound');
                                        });
                                    } else {
                                        reject(fm.i18n(data.error? data.error : 'errUpload'));
                                        fmNode.dialogelfinder('close');
                                    }
                                })
                                .fail(err => {
                                    const error = fm.parseError(err);
                                    reject(fm.i18n(error? (error === 'userabort'? 'errAbort' : error) : 'errUploadNoFiles'));
                                });
                        }).catch((fm, err) => {
                            const error = fm.parseError(err);
                            reject(fm.i18n(error? (error === 'userabort'? 'errAbort' : error) : 'errUploadNoFiles'));
                        });
                    };

                    this.upload = function() {
                        return new Promise(function(resolve, reject) {
                            if (loader.file instanceof Promise || (loader.file && typeof loader.file.then === 'function')) {
                                loader.file.then(function(file) {
                                    upload(file, resolve, reject);
                                });
                            } else {
                                upload(loader.file, resolve, reject);
                            }
                        });
                    };
                    this.abort = function() {
                        _fm && _fm.getUI().trigger('uploadabort');
                    };
                };

                // Set up image uploader
                fileRepo.createUploadAdapter = loader => {
                    return new uploder(loader);
                };


                editor.model.schema.extend('$text', { allowAttributes: 'class' });

                editor.conversion.for('downcast').add(dispatcher => {
                    dispatcher.on('element:img', (evt, data, conversionApi) => {
                        const imgElement = data.item;

                        if (!imgElement.hasClass('img-fluid')) {
                            conversionApi.writer.addClass('img-fluid', imgElement);
                        }
                    });
                });
            } )
            .catch(error => {
                console.error(error);
            });

        CKEDITOR.ClassicEditor
            .create( document.querySelector( '#editor2','' ), editorconfig).then(editor => {
        })
            .then( editor => {
                window.editor = editor;

            })
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
