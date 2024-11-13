<?php

namespace App\Http\Controllers;

use App\Services\LectureService;
use Illuminate\Http\Request;

class LectureController extends Controller
{
    protected $lectureService;

    public function __construct(LectureService $lectureService)
    {
        $this->lectureService = $lectureService;
    }

    public function index()
    {
        return response()->json($this->lectureService->getAllLectures());
    }

    // public function getLectureByCode(Request $request){
    //     $code = $request->get('activationCode');
    //     $lectures = $this->lectureService->getAllLecturesByCode($request);

    //     // Check if the lectures were found
    //     if (is_null($lectures)) {
    //         return response()->json(['error' => 'Code not found'], 404);
    //     }

    //     return response()->json($lectures, 200);
    // }

    public function show($id)
    {
        return response()->json(data: $this->lectureService->getLectureById($id));
    }

    public function createLecture(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id'
        ]);

        $lecture = $this->lectureService->createLecture($data);
        return response()->json($lecture, 201);
    }

    public function addAudioLecture(Request $request, $lectureId)
{
    $request->validate([
        'audio_file' => 'required|file|mimes:mp3,wav,aac' // Adjust allowed formats as needed
    ]);

    $audioLecture = $this->lectureService->addAudioLecture($lectureId, $request->file('audio_file'));
    return response()->json($audioLecture, 201);
}

public function addPdfLecture(Request $request, $lectureId)
{
    // echo $lectureId;

    $request->validate([
        'pdf_file' => 'required|file|mimes:pdf'
    ]);

    $pdfLecture = $this->lectureService->addPdfLecture($lectureId, $request->file('pdf_file'));
    return response()->json($pdfLecture, 201);
}

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'string|max:255',
            'subject_id' => 'exists:subjects,id',
        ]);

        return response()->json($this->lectureService->updateLecture($id, $data));
    }

    public function destroy($id)
    {
        $this->lectureService->deleteLecture($id);
        return response()->json(null, 204);
    }
}
