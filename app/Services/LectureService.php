<?php

namespace App\Services;

use App\Repositories\LectureRepository;
use App\Repositories\AudioLectureRepository;
use App\Repositories\PdfLectureRepository;
use App\Models\Code;
use App\Exceptions\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Storage;
use getID3; // Assuming getID3 library is installed


class LectureService
{
    protected $lectureRepository;
    protected $pdfLectureRepository;
    protected $audioLectureRepository;

    public function __construct(
        LectureRepository $lectureRepository,
        AudioLectureRepository $audioLectureRepository,
        PdfLectureRepository $pdfLectureRepository
    ) {
        $this->lectureRepository = $lectureRepository;
        $this->audioLectureRepository = $audioLectureRepository;
        $this->pdfLectureRepository = $pdfLectureRepository;
    }

    public function getAllLectures()
    {
        return $this->lectureRepository->getAll();
    }

    // public function getAllLecturesByCode(string $code)
    // {
    //     try {
    //         $code = Code::where('activation_code', $code)->firstOrFail();
    //         return $code->lectures;
    //     } catch (ModelNotFoundException $e) {
    //         throw new Exception("Code with activation code {$code} not found.", 404);
    //     }
    // }

    public function getLectureById($id)
    {
        $lecture = $this->lectureRepository->findById($id);
        if (!$lecture) {
            throw new ModelNotFoundException("Lecture with ID {$id} not found.");
        }
        return $lecture;
    }

    // public function createLecture(array $data)
    // {
    //     try {
    //         return $this->lectureRepository->create($data);
    //     } catch (Exception $e) {
    //         throw new Exception("Failed to create lecture.", 500);
    //     }
    // }

    public function createLecture(array $data)
    {
        try {
            return $this->lectureRepository->create($data);
        } catch (Exception $e) {
            throw new Exception("Failed to create lecture: " . $e->getMessage(), 500);
        }
    }

    // Add an audio file to an existing Lecture
    public function addAudioLecture(int $lectureId, $file)
    {
        try {
            $lecture = $this->lectureRepository->findById($lectureId);
            if (!$lecture) throw new ModelNotFoundException("Lecture not found.");

            // Generate a unique file name and store the file
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/audio_lectures', $fileName);

            // Calculate file size
            $fileSize = Storage::size($path);

            // Calculate duration using getID3
            $getID3 = new getID3();
            $fileInfo = $getID3->analyze(storage_path('app/' . $path));
            $duration = $fileInfo['playtime_seconds'] ?? 0;

            // Create the AudioLecture record
            return $this->audioLectureRepository->create([
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'duration' => $duration,
                'lecture_id' => $lectureId
            ]);
        } catch (ModelNotFoundException $e) {
            throw new Exception($e->getMessage(), 404);
        } catch (Exception $e) {
            throw new Exception("Failed to add audio lecture: " . $e->getMessage(), 500);
        }
    }

    // Add a PDF file to an existing Lecture
    public function addPdfLecture(int $lectureId, $file)
    {
        try {
            // echo $lectureId;
            $lecture = $this->lectureRepository->findById($lectureId);
            if (!$lecture) throw new ModelNotFoundException("Lecture not found.");


            // Generate a unique file name and store the file
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/pdf_lectures', $fileName);
            // echo $lectureId;

            // Calculate file size
            $fileSize = Storage::size($path);
            // echo $fileSize + " " + $fileName ;

            // Create the PdfLecture record
            return $this->pdfLectureRepository->create([
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'lecture_id' => $lectureId
            ]);
        } catch (ModelNotFoundException $e) {
            throw new Exception($e->getMessage(), 404);
        } catch (Exception $e) {
            throw new Exception("Failed to add PDF lecture: " . $e->getMessage(), 500);
        }
    }

    public function updateLecture($id, array $data)
    {
        $lecture = $this->getLectureById($id); // Throws exception if not found
        return $this->lectureRepository->update($lecture, $data);
    }

    public function deleteLecture($id)
    {
        $lecture = $this->getLectureById($id); // Throws exception if not found
        return $this->lectureRepository->delete($lecture);
    }
}
