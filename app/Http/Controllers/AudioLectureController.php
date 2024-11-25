<?php

namespace App\Http\Controllers;

use App\Services\AudioLectureService;
use Illuminate\Http\Request;

class AudioLectureController extends Controller
{
    protected $audioLectureService;

    public function __construct(AudioLectureService $audioLectureService)
    {
        $this->audioLectureService = $audioLectureService;
    }

    public function index()
    {
        $audioLectures = $this->audioLectureService->getAllAudioLectures();
        return response()->json($audioLectures);
    }

    public function show($id)
    {
        $audioLecture = $this->audioLectureService->getAudioLectureById($id);
        return response()->json($audioLecture);
    }
    public function downloadAudioFile($id){
        return $this->audioLectureService->downloadAudioLecture($id);
    }

    public function addAudioLecture(Request $request, $lectureId)
    {
        // echo $lectureId;
        $request->validate([
            'audio_file' => 'required|file|mimes:mp3,wav,aac' // Adjust allowed formats as needed
        ]);
        // echo $lectureId;

        $audioLecture = $this->audioLectureService->addAudioLecture($lectureId, $request->file('audio_file'));
        return response()->json($audioLecture, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'file_name' => 'sometimes|required|string',
            'file_size' => 'sometimes|required|integer',
            'duration' => 'sometimes|required|integer',
        ]);

        $audioLecture = $this->audioLectureService->updateAudioLecture($id, $data);
        return response()->json($audioLecture);
    }

    public function destroy($id)
    {
        $this->audioLectureService->deleteAudioLecture($id);
        return response()->json(null, 204);
    }
}
