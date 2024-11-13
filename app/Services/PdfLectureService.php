<?php

// app/Services/PdfLectureService.php
namespace App\Services;

use App\Repositories\PdfLectureRepository;
use App\Exceptions\ModelNotFoundException;

class PdfLectureService
{
    protected $pdfLectureRepository;

    public function __construct(PdfLectureRepository $pdfLectureRepository)
    {
        $this->pdfLectureRepository = $pdfLectureRepository;
    }

    public function getAllPdfLectures()
    {
        return $this->pdfLectureRepository->getAll();
    }

    public function getPdfLectureById($id)
    {
        $pdfLecture = $this->pdfLectureRepository->findById($id);
        if (!$pdfLecture) {
            throw new ModelNotFoundException("PDF Lecture with ID {$id} not found.");
        }
        return [
            'id' => $pdfLecture->id,
            'file_name' => $pdfLecture->file_name,
            'file_size' => $pdfLecture->file_size,
            'lecture_id' => $pdfLecture->lecture_id,
            'file_url' => route('api.lectures.pdf-lectures.download', ['id' => $id])
        ];
    }

    public function downloadPdfLecture($id)
{
    $pdfLecture = $this->pdfLectureRepository->findById($id);

    if (!$pdfLecture) {
        throw new ModelNotFoundException("PDF lecture with ID {$id} not found.");
    }

    // Adjust the path to reflect where files are actually stored in Laravel
    $filePath = storage_path("app/public/pdf_lectures/{$pdfLecture->file_name}");

    if (!file_exists($filePath)) {
        throw new ModelNotFoundException("File not found on server.");
    }

    return response()->download($filePath, $pdfLecture->file_name);
}


    public function createPdfLecture(array $data)
    {
        return $this->pdfLectureRepository->create($data);
    }

    public function updatePdfLecture($id, array $data)
    {
        $pdfLecture = $this->pdfLectureRepository->findById($id);
        if (!$pdfLecture) {
            throw new ModelNotFoundException("PDF Lecture with ID {$id} not found.");
        }
        return $this->pdfLectureRepository->update($pdfLecture, $data);
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
