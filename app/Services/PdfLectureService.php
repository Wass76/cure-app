<?php

// app/Services/PdfLectureService.php
namespace App\Services;

use App\Exceptions\DublicatedFileNameException;
use App\Repositories\LectureRepository;
use App\Repositories\PdfLectureRepository;
use App\Exceptions\ModelNotFoundException;

use App\Repositories\SubjectRepository;
use Exception;
use Illuminate\Support\Facades\Storage;

class PdfLectureService
{
    protected $pdfLectureRepository;
    protected $lectureRepository;
    protected $subjectRepository;

    public function __construct(
        PdfLectureRepository $pdfLectureRepository,
         LectureRepository $lectureRepository,
         SubjectRepository $subjectRepository
         )
    {
        $this->pdfLectureRepository = $pdfLectureRepository;
        $this->lectureRepository = $lectureRepository;
        $this->subjectRepository = $subjectRepository;
    }

    protected function formatPdfLecture($pdfLecture)
    {
        return [
            'id' => $pdfLecture->id,
            'file_name' => $pdfLecture->file_name,
            'file_size' => $pdfLecture->file_size,
            'lecture_id' => $pdfLecture->lecture_id,
            'file_url' => route('api.lectures.pdf-lectures.download', ['id' => $pdfLecture->id])
        ];
    }

    public function getAllPdfLectures()
    {
        $pdfLectures = $this->pdfLectureRepository->getAll();

        // Format each PdfLecture using the helper method
        return $pdfLectures->map(function ($pdfLecture) {
            return $this->formatPdfLecture($pdfLecture);
        });
    }

    public function getPdfLectureById($id)
    {
        $pdfLecture = $this->pdfLectureRepository->findById($id);
        if (!$pdfLecture) {
            throw new ModelNotFoundException("PDF Lecture with ID {$id} not found.");
        }
        return $this->formatPdfLecture($pdfLecture);
    }

    public function downloadPdfLecture($id)
    {
        $pdfLecture = $this->pdfLectureRepository->findById($id);

        if (!$pdfLecture) {
            throw new ModelNotFoundException("PDF lecture with ID {$id} not found.");
        }

        $filePath = storage_path("app/public/pdf_lectures/{$pdfLecture->file_name}");

        if (!file_exists($filePath)) {
            throw new ModelNotFoundException("File not found on server.");
        }

        return response()->download($filePath, $pdfLecture->file_name);
    }

public function addPdfLecture(int $lectureId, $file)
    {
        try {
            // echo $lectureId;
            $lecture = $this->lectureRepository->findById($lectureId);
            if (!$lecture)
             throw new ModelNotFoundException("Lecture not found.");

            $subject = $this->subjectRepository->findById($lecture['subject_id']);
            if (!$subject)
             throw new ModelNotFoundException("Lecture not found.");

            $fileName = $lecture['name'] .'-' . $subject['name'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/pdf_lectures', $fileName);

            $existedName = $this->pdfLectureRepository->findByName($fileName);
            if($existedName->isNotEmpty()){
                throw new DublicatedFileNameException('dublicated name for pdf file.');
            }

            // Calculate file size
            $fileSize = Storage::size($path);

            // Create the PdfLecture record
            $pdfLecture = $this->pdfLectureRepository->create([
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'lecture_id' => $lectureId
            ]);
            return $this->formatPdfLecture($pdfLecture);

        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException($e->getMessage(), 404);
        }
        catch(DublicatedFileNameException $e){
            throw new DublicatedFileNameException($e->getMessage());
        }
         catch (Exception $e) {
            throw new Exception("Failed to add PDF lecture: " . $e->getMessage(), 500);
        }
    }

    public function updatePdfLecture($id, $file , $lectureId)
    {
        try {
            $pdfLecture = $this->pdfLectureRepository->findById($id);
            if(!$pdfLecture) throw new ModelNotFoundException("pdf lecture not found");


            $lecture = $this->lectureRepository->findById($lectureId);
            if (!$lecture) throw new ModelNotFoundException("Lecture not found.");

            $subject = $this->subjectRepository->findById($lecture['subject_id']);
            if (!$subject) throw new ModelNotFoundException("Lecture not found.");

            $fileName = $lecture['name'] .'-' . $subject['name'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/pdf_lectures', $fileName);

            // Calculate file size
            $fileSize = Storage::size($path);
            $pdfLecture = $this->pdfLectureRepository->update($pdfLecture, [
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'lecture_id' => $lectureId
            ]);
            return $this->formatPdfLecture($pdfLecture);
        }catch (ModelNotFoundException $e) {
            throw new Exception($e->getMessage(), 404);
        }
        catch (Exception $e) {
            throw new Exception("Failed to add PDF lecture: " . $e->getMessage(), 500);
        }
    }

    public function deletePdfLecture($id)
    {
        $pdfLecture = $this->pdfLectureRepository->findById($id);
        if (!$pdfLecture) {
            throw new ModelNotFoundException("PDF Lecture with ID {$id} not found.");
        }
        return $this->pdfLectureRepository->delete($pdfLecture);
    }
}
