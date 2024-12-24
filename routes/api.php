<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\PdfLectureController;
use App\Http\Controllers\AudioLectureController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\AuthController;


Route::prefix('v1')->group(function () {

    // Route::middleware('auth:api')->get('/protected-route', function (Request $request) {
    //     return response()->json(['message' => 'You are authorized']);
    // });



    Route::post('/register-or-login', [AuthController::class, 'registerOrLogin']);

    Route::post('/admin-login', [AuthController::class, 'loginForAdmin']);


    // Route::post('login' , [AuthController::class , 'registerOrLogin']);
    Route::get('subjects', [SubjectController::class, 'index']);
    Route::post('codes', [CodeController::class, 'generateCodes']);
    Route::get("users-in-blocks/{id}" , action: [CodeController::class , "getNumberofUsersInBlock"] );
    Route::get("all-users" , [AuthController::class , "getAllUserInfo"]);


    Route::middleware('auth:api')->group(function () {

    // Routes for Code
    Route::get('codes', [CodeController::class, 'index']);
    Route::post('codes/to-user' , [CodeController::class , 'assignCodeToUser']);
    Route::get('codes/{id}', [CodeController::class, 'show']);
    Route::put('codes/{id}', [CodeController::class, 'update']);
    Route::delete('codes/{id}', [CodeController::class, 'destroy']);


    // Routes for Subjects
    Route::post('subjects', [SubjectController::class, 'store']);
    Route::get('subjects/by-codes' , [SubjectController::class , 'getSubjectsForUser']);
    Route::get("subjects/number-of-user" , [SubjectController::class , "getUserCountBySubject"]);

    Route::get('subjects/{id}', [SubjectController::class, 'show']);
    Route::put('subjects/{id}', [SubjectController::class, 'update']);
    Route::delete('subjects/{id}', [SubjectController::class, 'destroy']);
    Route::get('subjects/by-code/{code}', [SubjectController::class, 'getAllSubjectsByCode']);

    // Routes for PDF Lectures
    Route::get('lectures/pdf-lectures', [PdfLectureController::class, 'index']);
    Route::post('lectures/{lectureId}/pdf-lectures', [PdfLectureController::class, 'store']);
    Route::get('lectures/pdf-lectures/{id}', [PdfLectureController::class, 'show']);
    Route::get('lectures/pdf-lectures/download/{id}', [PdfLectureController::class ,'downloadPDF'])->name('api.lectures.pdf-lectures.download');
    Route::put('lectures/{lectureId}/pdf-lectures/{id}', [PdfLectureController::class, 'update']);
    Route::delete('lectures/pdf-lectures/{id}', [PdfLectureController::class, 'destroy']);

    // Routes for Audio Lectures
    Route::get('lectures/audio-lectures', [AudioLectureController::class, 'index']);
    Route::post('lectures/{lectureId}/audio-lectures', [AudioLectureController::class, 'addAudioLecture']);
    Route::get('lectures/audio-lectures/{id}', [AudioLectureController::class, 'show']);
    Route::get('lectures/audio-lectures/download/{id}', [AudioLectureController::class ,'downloadAudioFile'])->name('api.lectures.audio-lectures.download');
    Route::put('lectures/audio-lectures/{id}', [AudioLectureController::class, 'update']);
    Route::delete('lectures/audio-lectures/{id}', [AudioLectureController::class, 'destroy']);

    // Routes for Lectures (put this after specific routes)
    Route::get('lectures', [LectureController::class, 'index']);
    Route::post('lectures', [LectureController::class, 'createLecture']);
    // Route::get('lectures/by-codes' , [LectureController::class , 'getLectureForUser']);
    Route::get('lectures/{id}', [LectureController::class, 'show']);
    Route::get('lectures/get-by-subject/{subjectId}' , [LectureController::class , 'getBySubjectId']);
    Route::put('lectures/{id}', [LectureController::class, 'update']);
    Route::delete('lectures/{id}', [LectureController::class, 'destroy']);

    });
});
