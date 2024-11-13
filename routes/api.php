<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

use App\Http\Controllers\PdfLectureController;
use App\Http\Controllers\AudioLectureController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\LectureController;


Route::prefix('v1')->group(function () {
    // Routes for Code
    Route::get('codes', [CodeController::class, 'index']);
    Route::post('codes', [CodeController::class, 'generateCodes']);
    Route::get('codes/{id}', [CodeController::class, 'show']);
    Route::put('codes/{id}', [CodeController::class, 'update']);
    Route::delete('codes/{id}', [CodeController::class, 'destroy']);

    // Routes for Subjects
    Route::get('subjects', [SubjectController::class, 'index']);
    Route::post('subjects', [SubjectController::class, 'store']);
    Route::get('subjects/{id}', [SubjectController::class, 'show']);
    Route::put('subjects/{id}', [SubjectController::class, 'update']);
    Route::delete('subjects/{id}', [SubjectController::class, 'destroy']);
    Route::get('subjects/by-code/{code}' , [SubjectController::class, 'getAllSubjectsByCode']);

    // Routes for Lectures
    Route::get('lectures', [LectureController::class, 'index']);
    Route::post('lectures', [LectureController::class, 'createLecture']);
    Route::get('lectures/{id}', [LectureController::class, 'show']);
    Route::put('lectures/{id}', [LectureController::class, 'update']);
    Route::delete('lectures/{id}', [LectureController::class, 'destroy']);

    // Routes for PDF Lectures
    Route::get('lectures/pdf-lectures', [PdfLectureController::class, 'index']);
    Route::post('lectures/{lectureId}/pdf-lectures', [LectureController::class, 'addPdfLecture']);
    Route::get('lectures/pdf-lectures/{id}', [PdfLectureController::class, 'show']);
    Route::get('lectures/pdf-lectures/download/{id}', [PdfLectureController::class ,'downloadPDF'])->name('api.lectures.pdf-lectures.download');
    Route::put('lectures/pdf-lectures/{id}', [PdfLectureController::class, 'update']);
    Route::delete('lectures/pdf-lectures/{id}', [PdfLectureController::class, 'destroy']);

    // Routes for Audio Lectures
    Route::get('lectures/audio-lectures', [AudioLectureController::class, 'index']);
    Route::post('lectures/{lectureId}/audio-lectures', [LectureController::class, 'addAudioLecture']);
    Route::get('lectures/audio-lectures/{id}', [AudioLectureController::class, 'show']);
    Route::put('lectures/audio-lectures/{id}', [AudioLectureController::class, 'update']);
    Route::delete('lectures/audio-lectures/{id}', [AudioLectureController::class, 'destroy']);
});
