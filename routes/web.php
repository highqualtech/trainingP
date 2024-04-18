<?php

use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\CourseSlidesController;
use App\Http\Controllers\CourseToUsersController;
use App\Http\Controllers\IntakeGroupsController;
use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', function () {
    // Redirect to a new URL
    return redirect('/courses');
});

Route::get('/dashboard', function () {
    // Redirect to a new URL
    return redirect('/courses');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/companies', '\App\Http\Controllers\CompaniesController@index');
    Route::post('/companies', [CompaniesController::class, 'createcompany'])->name('company.create');

    Route::get('/company/{id}', [CompaniesController::class, 'edit'])->name('company.edit');
    Route::patch('/company/{id}', [CompaniesController::class, 'update'])->name('company.update');
    Route::delete('/company/{id}', [CompaniesController::class, 'destroy'])->name('company.destroy');

    Route::get('/intakegroups', '\App\Http\Controllers\IntakeGroupsController@index');
    Route::post('/intakegroups', [IntakeGroupsController::class, 'createintake'])->name('intake.create');

    Route::get('/intakegroup/{id}', [IntakeGroupsController::class, 'edit'])->name('intake.edit');
    Route::patch('/intakegroup/{id}', [IntakeGroupsController::class, 'update'])->name('intake.update');
    Route::delete('/intakegroup/{id}', [IntakeGroupsController::class, 'destroy'])->name('intake.destroy');
    Route::get('/archivedintakegroups', [IntakeGroupsController::class, 'indexarchived'])->name('intake.archived');
    Route::patch('/intakegrouparchive/{id}', [IntakeGroupsController::class, 'indexarchive'])->name('intake.archive');
    Route::patch('/intakegroupunarchive/{id}', [IntakeGroupsController::class, 'indexunarchive'])->name('intake.unarchive');

    Route::get('/participants', '\App\Http\Controllers\ParticipantsController@index');
    Route::post('/participants', [ParticipantsController::class, 'createparticipant'])->name('participant.create');
    Route::get('/archivedparticipants', '\App\Http\Controllers\ParticipantsController@indexarchived');


    Route::get('/csvimport', '\App\Http\Controllers\ParticipantsController@csvimport');
    Route::post('/csvimport', [ParticipantsController::class, 'csvimportprocess'])->name('participant.csvimportprocess');

    Route::get('/participant/{id}', [ParticipantsController::class, 'edit'])->name('participant.edit');
    Route::patch('/participant/{id}', [ParticipantsController::class, 'update'])->name('participant.update');
    Route::delete('/participant/{id}', [ParticipantsController::class, 'destroy'])->name('participant.destroy');

    Route::post('/participantscourse', [CourseToUsersController::class, 'participantallocate'])->name('participantscourse.allocate');
    Route::post('/participantscourseresend/{id}/{participantid}', [CourseToUsersController::class, 'participantallocateresend'])->name('participantcourse.resend');
    Route::delete('/participantscourseresend/{id}/{participantid}', [CourseToUsersController::class, 'participantallocatedelete'])->name('participantcourse.destroy');

    Route::post('/bulkinvite', [CourseToUsersController::class, 'bulkinvite'])->name('participantscourse.bulkinvite');
    Route::post('/bulkarchiveparticipants', [ParticipantsController::class, 'bulkarchive'])->name('participants.bulkarchive');
    Route::post('/bulkunarchiveparticipants', [ParticipantsController::class, 'bulkunarchive'])->name('participants.bulkunarchive');



    Route::get('/courses', '\App\Http\Controllers\CoursesController@index');
    Route::post('/courses', [CoursesController::class, 'createcourse'])->name('course.create');

    Route::get('/archivedcourses', '\App\Http\Controllers\CoursesController@indexarchived');

    Route::get('/course/{id}', [CoursesController::class, 'edit'])->name('course.edit');
    Route::get('/previewcourse/{id}', [CoursesController::class, 'preview'])->name('course.preview');
    Route::patch('/course/{id}', [CoursesController::class, 'update'])->name('course.update');
    Route::patch('/coursearchive/{id}', [CoursesController::class, 'coursearchive'])->name('course.archive');
    Route::patch('/courseunarchive/{id}', [CoursesController::class, 'courseunarchive'])->name('course.unarchive');
    Route::delete('/course/{id}', [CoursesController::class, 'destroy'])->name('course.destroy');
    Route::get('/courseduplicate/{id}', [CoursesController::class, 'courseduplicate'])->name('course.duplicate');

    Route::get('/courseparticipants/{id}', [CourseToUsersController::class, 'index'])->name('courseparticipants.index');
    Route::get('/courseparticipantsfilter/{id}/{filer}', [CourseToUsersController::class, 'index'])->name('courseparticipants.index');
    Route::post('/courseparticipantsredirect/{id}', [CourseToUsersController::class, 'redirect'])->name('courseparticipants.redirect');



    Route::get('/courseparticipantsmark/{id}/{coursekey}', [CourseToUsersController::class, 'coursemark'])->name('courseparticipants.coursemark');
    Route::patch('/courseparticipantsmark/{id}/{coursekey}', [CourseToUsersController::class, 'coursemarksubmit'])->name('courseparticipants.coursemarksubmit');

    Route::get('/certificategenerate/{courseid}/{coursekey}', [CoursesController::class, 'participantgeneratecertificate'])->name('courseparticipants.generatecertificate');

    Route::get('/marking', [CourseToUsersController::class, 'marking'])->name('courseparticipants.marking');

    Route::get('/courseslides/{id}', [CourseSlidesController::class, 'index'])->name('courseslides.index');
    Route::post('/courseslides/{id}', [CourseSlidesController::class, 'createslide'])->name('courseslides.create');
    Route::get('/courseslides/{id}/{slideid}', [CourseSlidesController::class, 'editslide'])->name('courseslides.editslide');
    Route::get('/courseslidesdev/{id}/{slideid}', [CourseSlidesController::class, 'editslidedev'])->name('courseslides.editslidedev');
    Route::get('/courseslidepreview/{id}/{slideid}', [CourseSlidesController::class, 'previewslide'])->name('courseslides.previewslide');
    Route::patch('/courseslides/{id}/{slideid}', [CourseSlidesController::class, 'update'])->name('courseslides.update');
    Route::delete('/courseslides/{id}/{slideid}', [CourseSlidesController::class, 'destroy'])->name('courseslides.destroy');

    Route::post('/c_submitadmin/{courseid}/{slidekey}', [CoursesController::class, 'participantcoursemarkadmin'])->name('participantcourse.markadmin');

    Route::post('updateslidesort/{courseid}',[CourseSlidesController::class, 'courseslidesort'])->name('courseslides.sort');

    Route::get('/quickinvite',[CoursesController::class, 'quickinvite'])->name('course.quickinvite');
    Route::post('/quickinvite',[CoursesController::class, 'quickinvitesend'])->name('course.quickinvitesend');

    Route::get('/users',[ProfileController::class, 'listusers'])->name('users.listusers');
    Route::post('/users', [ProfileController::class, 'createuser'])->name('user.create');

    Route::get('/user/{id}', [ProfileController::class, 'edituser'])->name('user.edit');
    Route::patch('/user/{id}', [ProfileController::class, 'updateuser'])->name('user.update');
    Route::delete('/user/{id}', [ProfileController::class, 'destroyuser'])->name('user.destroy');

});

Route::get('/c/{coursekey}', [CoursesController::class, 'participantcourse'])->name('participantcourse.index');
Route::get('/c/{coursekey}/{slidekey}', [CoursesController::class, 'participantcourseslide'])->name('participantcourse.slide');
Route::post('/c/{coursekey}/{slidekey}', [CoursesController::class, 'participantcourseslideprocess'])->name('participantcourse.slideprocess');
Route::post('/c_submit/{coursekey}/{slidekey}', [CoursesController::class, 'participantcourseslidemark'])->name('participantcourse.slidemark');
Route::get('/complete/{coursekey}', [CoursesController::class, 'participantcoursecomplete'])->name('participantcourse.complete');

//Route::get('/certificate/{coursekey}', [CourseToUsersController::class, 'participantcertificate'])->name('participantcourse.certificate');

require __DIR__.'/auth.php';
