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

    public function store(Request $request)
    {
        $data = $request->validate([
            'lecture_id' => 'required|exists:lectures,id',
            'file_name' => 'required|string',
            'file_size' => 'required|integer',
            'duration' => 'required|integer',
        ]);

        $audioLecture = $this->audioLectureService->createAudioLecture($data);
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
