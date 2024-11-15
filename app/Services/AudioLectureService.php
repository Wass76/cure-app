<?php

namespace App\Services;

use App\Repositories\AudioLectureRepository;
use App\Exceptions\ModelNotFoundException;
use App\Exceptions\DublicatedFileNameException;
use App\Repositories\LectureRepository;
use App\Repositories\SubjectRepository;
use Exception;
use Storage;
use getID3; // Assuming getID3 library is installed

class AudioLectureService
{
    protected $audioLectureRepository;
    protected $lectureRepository;
    protected $subjectRepository;

    public function __construct(
        AudioLectureRepository $audioLectureRepository,
        LectureRepository $lectureRepository,
        SubjectRepository $subjectRepository
        )
    {
        $this->audioLectureRepository = $audioLectureRepository;
        $this->lectureRepository = $lectureRepository;
        $this->subjectRepository = $subjectRepository;
    }

    protected function formatAudioLecture($audioLecture)
    {
        return [
            'id' => $audioLecture->id,
            'file_name' => $audioLecture->file_name,
            'file_size' => $audioLecture->file_size,
            'duration' => $audioLecture->duration,
            'lecture_id' => $audioLecture->lecture_id,
            'file_url' => route('api.lectures.audio-lectures.download', ['id' => $audioLecture->id])
        ];
    }

    public function getAllAudioLectures()
    {
        $audioLecture = $this->audioLectureRepository->getAll();
        return $audioLecture->map(function ($pdfLecture) {
            return $this->formatAudioLecture($pdfLecture);
        });
    }

    public function getAudioLectureById($id)
    {
        $data= $this->audioLectureRepository->findById($id);
        if(!$data)
            throw new ModelNotFoundException("audio file with id:" . $id . "not found");
        return $this->formatAudioLecture($data);
    }

    // public function createAudioLecture(array $data)
    // {
    //     return $this->audioLectureRepository->create($data);
    // }

    public function downloadAudioLecture($id)
    {
        $pdfLecture = $this->audioLectureRepository->findById($id);

        if (!$pdfLecture) {
            throw new ModelNotFoundException("PDF lecture with ID {$id} not found.");
        }

        $filePath = storage_path("app/public/audio_lectures/{$pdfLecture->file_name}");

        if (!file_exists($filePath)) {
            throw new ModelNotFoundException("File not found on server.");
        }

        return response()->download($filePath, $pdfLecture->file_name);
    }

    public function addAudioLecture(int $lectureId, $file)
    {
        try {
            $lecture = $this->lectureRepository->findById($lectureId);
            if (!$lecture) throw new ModelNotFoundException("Lecture not found.");

            $subject = $this->subjectRepository->findById($lecture['subject_id']);
            if (!$subject) throw new ModelNotFoundException("Lecture not found.");

            $fileName = $lecture['name'] .'-' . $subject['name'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/pdf_lectures', $fileName);

            $existedName = $this->audioLectureRepository->findByName($fileName);
            if($existedName->isNotEmpty()){
                throw new DublicatedFileNameException('dublicated name for pdf file.');
            }

            // Calculate file size
            $fileSize = Storage::size($path);

            // Calculate duration using getID3
            $getID3 = new getID3();
            $fileInfo = $getID3->analyze(storage_path('app/' . $path));
            $duration = $fileInfo['playtime_seconds'] ?? 0;

            // Create the AudioLecture record
            $audioLecture= $this->audioLectureRepository->create([
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'duration' => $duration,
                'lecture_id' => $lectureId
            ]);
            return $this->formatAudioLecture($audioLecture);
        }
        catch(DublicatedFileNameException $e){
            throw new DublicatedFileNameException($e->getMessage());
        }
        catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException($e->getMessage(), 404);
        } catch (Exception $e) {
            throw new Exception("Failed to add audio lecture: " . $e->getMessage(), 500);
        }
    }

    public function updateAudioLecture($id, array $data)
    {
        $audioLecture = $this->audioLectureRepository->findById($id);
        return $this->audioLectureRepository->update($audioLecture, $data);
    }

    public function deleteAudioLecture($id)
    {
        $audioLecture = $this->audioLectureRepository->findById($id);
        return $this->audioLectureRepository->delete($audioLecture);
    }
}
