<?php

namespace App\Http\Controllers;

use App\Services\PdfLectureService;
use Illuminate\Http\Request;

class PdfLectureController extends Controller
{
    protected $pdfLectureService;

    public function __construct(PdfLectureService $pdfLectureService)
    {
        $this->pdfLectureService = $pdfLectureService;
    }

    public function index()
    {
        $pdfLectures = $this->pdfLectureService->getAllPdfLectures();
        return response()->json($pdfLectures);
    }

    public function show($id)
    {
        $pdfLecture = $this->pdfLectureService->getPdfLectureById($id);
        return response()->json($pdfLecture);
    }

    public function downloadPDF($id){
        return $this->pdfLectureService->downloadPdfLecture($id);
    }

    public function store(Request $request, $lectureId)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf'
        ]);

        // echo $lectureId;

        $pdfLecture = $this->pdfLectureService->addPdfLecture($lectureId, $request->file('pdf_file'));
        return response()->json($pdfLecture, 201);
    }

    public function update(Request $request, $id , $lectureId)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf'
        ]);

        $pdfLecture = $this->pdfLectureService->updatePdfLecture($id, $request->file('pdf_file'), $lectureId);
        return response()->json($pdfLecture);
    }

    public function destroy($id)
    {
        $this->pdfLectureService->deletePdfLecture($id);
        return response()->json(null, 204);
    }
}
