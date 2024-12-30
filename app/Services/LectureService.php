<?php

namespace App\Services;

use App\Models\Lecture;
use App\Models\Subject;
use App\Repositories\LectureRepository;
use App\Repositories\AudioLectureRepository;
use App\Repositories\PdfLectureRepository;
use App\Models\Code;
use App\Exceptions\ModelNotFoundException;
use App\Repositories\SubjectRepository;
use Auth;
use Exception;
use Laravel\Passport\Exceptions\AuthenticationException;
use Storage;
use getID3; // Assuming getID3 library is installed



class LectureService
{
    protected $lectureRepository;
    protected $pdfLectureRepository;
    protected $audioLectureRepository;
    protected $subjectRepository;

    public function __construct(
        LectureRepository $lectureRepository,
        AudioLectureRepository $audioLectureRepository,
        PdfLectureRepository $pdfLectureRepository,
        SubjectRepository $subjectRepository
    ) {
        $this->lectureRepository = $lectureRepository;
        $this->audioLectureRepository = $audioLectureRepository;
        $this->pdfLectureRepository = $pdfLectureRepository;
        $this->subjectRepository = $subjectRepository;
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

        $audioLecture = $lecture->audioLecture;
        $pdfLecture = $lecture->pdfLecture;

        return [
            'id' => $lecture->id,
            'name' => $lecture->name,
            'subject_id' => $lecture->subject_id,
            'audioLectureId' => $audioLecture?->id,
            'audioLectureDownloadLink' => $audioLecture ? route('api.lectures.audio-lectures.download', ['id' => $audioLecture->id]) : null,
            'pdfLectureId' => $pdfLecture?->id,
            'pdfLectureDownloadLink' => $pdfLecture ? route('api.lectures.pdf-lectures.download', ['id' => $pdfLecture->id]) : null,
        ];
    }


    // public function createLecture(array $data)
    // {
    //     try {
    //         return $this->lectureRepository->create($data);
    //     } catch (Exception $e) {
    //         throw new Exception("Failed to create lecture.", 500);
    //     }
    // }

    public function getLecturesBySubject($id){

        $subject = $this->subjectRepository->findById($id);
        if(!$subject){
            throw new ModelNotFoundException("There is no subject with ID {$id}.");
        }

        $lectures = $this->lectureRepository->findBySubject($id);
        return $lectures;
    }

    public function createLecture(array $data)
    {
        try {
            return $this->lectureRepository->create($data);
        } catch (Exception $e) {
            throw new Exception("Failed to create lecture: " . $e->getMessage(), 500);
        }
    }

    public function getLectureCountForSubject(int $subjectId)
    {
        $subject = $this->subjectRepository->findById($subjectId);
        if(!$subject){
            throw new ModelNotFoundException("There is no subject with id " . $subjectId);
        }
        // Get the lecture count from the repository
        return $this->lectureRepository->countLecturesBySubject($subjectId);
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
